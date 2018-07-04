<?php
/**
 * Created by PhpStorm.
 * User: Dityara
 * Date: 04/07/2018
 * Time: 16.57
 */

require __DIR__. 'vendor/autoload.php';
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

//set false for production
$pass_signiture = true;

// set LINE channel_access_token and channel_secret
$channel_access_token = "8/xqC0T2m+iqcu+26+iFXF3lYdTW/qclAbX1xj82XZT/uS56HeuuyvylKKGkFwngEzZEsJd/jvrlUW9TkkLupmXLBKeOgNLVY+xqvpJsO9fbFL9fDEc1U1fvhjgM/px4qURBzTguMEBPh3crbhy1hgdB04t89/1O/w1cDnyilFU=";
$channel_secret = "94f3771f05bd0f942e02cdc8856e4bda";

// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

$configs =  [
    'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

// buat route untuk url homepage
$app->get('/', function($req, $res)
{
    echo "Welcome at Slim Framework";
});
// buat route untuk webhook

$app->post('/webhook', function ($request, $response, $channel_secret) use ($bot, $pass_signature)
{
    // get request body and line signature header
    $body        = file_get_contents('php://input');
    $signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : '';

    // log body and signature
    file_put_contents('php://stderr', 'Body: '.$body);

    if($pass_signature === false)
    {
        // is LINE_SIGNATURE exists in request header?
        if(empty($signature)){
            return $response->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if(! SignatureValidator::validateSignature($body, $channel_secret, $signature)){
            return $response->withStatus(400, 'Invalid signature');
        }
    }

    // kode aplikasi nanti disini

});

$app->run();