<?php

  include("extensions/functions.php");
  require_once("extensions/db.php");

  header('Content-Type: application/json');

  $request = file_get_contents('php://input');
  $payload = json_decode($request, true);

  //This code will allow you to view the JSON object payload.

  $type = $payload['data']['attributes']['type'];

  if(!empty($request)) {
    if(!file_exists('logs\ewallet_webhook')) {
      mkdir('logs\ewallet_webhook', 0777, true);
    }
    $log_file_data = 'logs\\ewallet_webhook\\log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, date('h:i:sa').' => '. $type .' : '. $request . "\n" . "\n", FILE_APPEND);
  } //This code will a log.txt file to get the response of the cURL command

  
  //If event type is source.chargeable, call the createPayment API
  if ($type == 'source.chargeable') {

    $amount = $payload['data']['attributes']['data']['attributes']['amount'];
    $id = $payload['data']['attributes']['data']['id'];

    process_paymongo_ewallet_payment($amount, $id);
  }

  if($type == 'payment.paid') {

    $mobile = '09755315755';
    $name = 'Melnar Ancit';
    
    /*$mobile = $payload['data']['attributes']['data']['attributes']['billing']['phone'];   
    $name = $payload['data']['attributes']['data']['attributes']['billing']['name'];  */  
    $amount = $payload['data']['attributes']['data']['attributes']['amount'];
    $currency = $payload['data']['attributes']['data']['attributes']['currency'];
    $method = $payload['data']['attributes']['data']['attributes']['source']['type'];

    $amount = number_format($amount, 2, '', '');
    
    $message = "Hello " . $name . "! Your payment for " . $amount . " " . $currency . " thru " . $method . " was successful. Thank you.";

    send_sms($mobile, $message);
  }