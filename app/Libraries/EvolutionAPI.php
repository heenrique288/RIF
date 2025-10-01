<?php

namespace App\Libraries;

class EvolutionAPI
{
    public $WPP_DEVICE_TOKEN;
    public $API_KEY;

    public $client;

    public function __construct()
    {
        $this->API_KEY = env('API_KEY'); 
        $this->WPP_DEVICE_TOKEN = env('WPP_DEVICE_TOKEN');
        $this->client = \Config\Services::curlrequest();        
    }

    public function sendMessage($number,$message)
    {
        if(strlen($number) > 9) //validar melhor os números
        {
            try 
            {
                //$this->client->request('POST', 'http://103.14.27.53:8090/message/sendText/ChatBotIF', [
                $this->client->request('POST', 'http://103.14.27.53:8090/message/sendText/Isabela', [
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'apikey'	=> $this->API_KEY
                    ],
                    'json' => [
                        'number'	=> "55" . $number, //ajustar para poder aceitar numeros internacionais também
                        'text' => $message
                    ]
                ]); 
            }
            catch(\Exception  $e) {
                //trabalhar observabilidade aqui
            }                   
        }
    }

    public function findMessages($where)
    {        
        try 
        {
            $response = $this->client->request('POST', 'http://103.14.27.53:8090/chat/findChats/Isabela', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'apikey'	=> $this->API_KEY
                ]
            ]);

            echo "<pre>";
            print_r(json_decode($response->getBody(),JSON_PRETTY_PRINT));
            echo "</pre>";
        }
        catch(\Exception  $e) {
            exit($e->getMessage());
        }        
    }
   
}
