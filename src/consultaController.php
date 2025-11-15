<?php

$termo = $_POST ['termo'];
$url_base = 'https://api.dicionario-aberto.net/word/';

class dicionario
{

    public $termo;
    public $url_base;

    //$termo = $_post['termo'];

    public function __construct($termo, $url_base)
    {
        $this->termo = $termo;
        $this->url_base = $url_base;
    }

    public function consulta()
    {
        $url_api = $this->url_base . $this->termo;

        // Inicializa cURL
        $api_consume = curl_init();
        curl_setopt_array($api_consume, [
            CURLOPT_URL => $url_api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($api_consume);

        if (curl_errno($api_consume)) {
            return json_encode([
                "erro" => "Erro na requisição da API: " . curl_error($api_consume)
            ]);
        }

        curl_close($api_consume);

        // Decodifica JSON em array
        $data = json_decode($response, true);

        if (!isset($data[0]["xml"])) {
            return json_encode([
                "erro" => "Termo não encontrado."
            ]);
        }

        $xml = $data[0]["xml"];

        // LIMPEZA E FORMATAÇÃO
        $xml = trim($xml);                 // Remove espaços antes/depois
        $xml = nl2br($xml);                // Quebra de linha → <br>
        $xml = preg_replace('/_([^_]+)_/', '<i>$1</i>', $xml); // _palavra_ → itálico

        // Título em negrito
        $xml = preg_replace('/^<br \/>*(.*?)<br \/>/', '<h2>$1</h2>', $xml);

        // Remove quebra de linha excessiva
        $xml = preg_replace('/(<br \/>){3,}/', '<br><br>', $xml);

        return $xml;
    }

}

$api = new dicionario($termo, $url_base);
$api->consulta();

echo $api->consulta();