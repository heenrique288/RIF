<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

if (!function_exists('suap_download_profile_pic')) {

    /**
     * Faz login no SUAP e baixa a foto de perfil do aluno.
     *
     * @param string $username Usuário SUAP
     * @param string $password Senha
     * @param string|null $target Matrícula do aluno (opcional, default = $username)
     * @return string Caminho do arquivo salvo
     * @throws \Exception
     */
    function suap_download_profile_pic(string $target): string
    {
        $username = env("suap.matricula");
        $password = env("suap.senha");

        $target = $target;

        if (!$target) {
            throw new \InvalidArgumentException('Informe a matrícula do aluno.');
        }

        helper('filesystem');

        $login_url = "https://suap.ifro.edu.br/accounts/login/";
        $base_url  = "https://suap.ifro.edu.br";

        $client = new Client();

        $crawler = suap_login($client, $login_url, $username, $password);
        if (!$crawler) {
            throw new \Exception('Falha no login.');
        }

        $url = $base_url . "/edu/aluno/" . $target;
        $imageUrl = suap_find_image_url($client, $url, $base_url);
        if (!$imageUrl) {
            throw new \Exception('Não foi possível localizar a foto de perfil.');
        }

        return $imageUrl;
    }
}

if (!function_exists('suap_login')) {
    function suap_login(Client $client, string $url, string $username, string $password): ?Crawler
    {
        $crawler = $client->request('GET', $url);

        try {
            $csrfToken = $crawler->filter('input[name="csrfmiddlewaretoken"]')->attr('value');
        } catch (\Exception $e) {
            throw new \Exception("Token CSRF não encontrado na página de login.");
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
            return $crawler;
        } else {
            try {
                $errorMessage = $crawler->filter('errornote')->innerText();
                throw new \Exception("Erro no login: " . $errorMessage);
            } catch (\Exception $e) {
                throw new \Exception("Erro no login: usuário ou senha inválidos.");
            }
        }
    }
}

if (!function_exists('suap_find_image_url')) {
    function suap_find_image_url(Client $client, string $url, string $base_url): ?string
    {
        $crawler = $client->request("GET", $url);

        $imgTag = $crawler->filter('div.photo-circle.big')->filter("img");

        if ($imgTag->count() > 0 && $imgTag->attr('src')) {
            $imgRelativeUrl = $imgTag->attr('src');

            if (preg_match('#^https?://#i', $imgRelativeUrl)) {
                return $imgRelativeUrl;
            }

            $path = '/' . ltrim($imgRelativeUrl, '/');
            return rtrim($base_url, '/') . $path;
        }

        throw new \Exception("Não foi possível localizar a foto de perfil.");
    }
}