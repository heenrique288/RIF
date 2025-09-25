<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;

class SuapDownloadProfilePic extends BaseCommand
{
    protected $group       = 'custom';
    protected $name        = 'getpic';
    protected $description = 'Faz login no SUAP e baixa a foto de perfil do aluno.';

    protected $usage = 'getpic [username] [password] [target]';

    protected $arguments = [
        'username' => 'Usuário do SUAP',
        'password' => 'Senha do SUAP',
        'target'   => 'A matrícula do aluno a ter a imagem baixada',
    ];

    protected $login_url = "https://suap.ifro.edu.br/accounts/login/";
    protected $base_url = "https://suap.ifro.edu.br";

    public function run(array $params)
    {
        $username = $params[0] ?? null;
        $password = $params[1] ?? null;
        $target   = $params[2] ?? $username; //caso nao seja fornecido, utiliza a propria matricula

        if (!$username || !$password) {
            CLI::error('Informe username e password.');
            return;
        }

        helper('filesystem');

        $client = new Client();

        $crawler = $this->login($client, $this->login_url, $username, $password);
        if(!$crawler) return null;

        $url = "https://suap.ifro.edu.br/edu/aluno/" . $target;
        $imageUrl = $this->findImageUrl($client, $url);
        if(!$imageUrl) return null;

        $this->downloadImage($imageUrl);
    }

    function login(Client $client, string $url, string $username, string $password): ?Crawler
    {
        $crawler = $client->request('GET', $url);

        $csrfToken = null;

        try {
            $csrfToken = $crawler->filter('input[name="csrfmiddlewaretoken"]')->attr('value');
        } catch (\Exception $e) {
            CLI::error("Erro: Token CSRF não encontrado na página de login");
            return null;
        }

        $formData = [
            'csrfmiddlewaretoken' => $csrfToken,
            'username' => $username,
            'password' => $password,
            'this_is_the_login_form' => '1',
            'next' => ''
        ];

        $crawler = $client->submit($crawler->selectButton('Acessar')->form(), $formData);

        $currentUrl = $client->getInternalRequest()->getUri();

        if (strpos(strtolower($crawler->html()), "sair") !== false || $currentUrl !== $url) {
            CLI::write("Login bem-sucedido!", 'green');
            return $crawler;
        } else {
            try {
                $errorMessage = $crawler->filter('errornote')->innerText();
                CLI::error("Erro: " . $errorMessage);
            } catch (\Exception $e) {
                // No errorlist found
            }

            return null;
        }
    }

    function findImageUrl(Client $client, string $url): ?string
    {
        $crawler = $client->request("GET", $url);

        $imgTag = $crawler->filter('div.photo-circle.big')->filter("img");

        if($imgTag->count() > 0 && $imgTag->attr('src')) {
            $imgRelativeUrl = $imgTag->attr('src');

            if (preg_match('#^https?://#i', $imgRelativeUrl)) {
                return $imgRelativeUrl;
            }

            $base_url = rtrim($this->base_url, '/');

            $path = '/' . ltrim($imgRelativeUrl, '/');

            return $base_url . $path;
        } else {
            CLI::error("Não foi possivel localizar a foto de perfil");
        }

        return null;
    }

    function downloadImage(string $url) {
        $outputDir = WRITEPATH . 'images';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $filePath = $outputDir . DIRECTORY_SEPARATOR . basename(parse_url($url, PHP_URL_PATH));

        try {
            $guzzleClient = new GuzzleClient(['cookies' => true]);
            $response = $guzzleClient->request('GET', $url, ['sink' => $filePath]);

            if ($response->getStatusCode() === 200) {
                CLI::write('Foto salva em: ' . $filePath, 'green');
            } else {
                CLI::error('Erro ao baixar a imagem. Status HTTP: ' . $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            CLI::error('Erro ao baixar a imagem: ' . $e->getMessage());
        }
    }
}
