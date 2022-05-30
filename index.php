<?php
error_reporting(0);
require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$data = array();

use GuzzleHttp\Client;
$a = false;
if(isset($_POST['indietro']))
{
    $offset = $_GET['offset'];
    if($offset>=20)
    {
        $offset = $offset-20;
        $_GET['offset'] = $offset;
    }    
    
    $a = true;
}

if(isset($_POST['avanti']))
{
    $offset = $_GET['offset'];
    $offset = $offset+20;
    $_GET['offset'] = $offset;
    $a = true;
}

if(isset($_POST['submit']) || $a=true)
{
    if(isset($_GET['offset']))
    {
        $offset = $_GET['offset'];
    }
    else
    {
        $offset=0;
    }
    
    
    
    $URL = 'https://api.giphy.com/v1/gifs';
    $API_KEY = "D9GAFfowrRQ12GFjOBQsMkXS1xQyrZoB";
    $client = new Client([
        'base_uri' => $URL,
        "verify" => false
    ]);
    $Testo = $_GET['testo'];

    $response = $client->request(
        'GET', 
        $URL . "/search", 
        [
            'query' => ['api_key' => $API_KEY, "q" => $Testo, 'offset' => $offset]
        ]
        );
    
    $data = json_decode(
        $response->getBody()->getContents(),
        true
    );



}



echo $twig->render('form.html.twig', [
    'testo' => $Testo,
    'offset' => $offset,
    'data' => $data['data']
    
]);
?>