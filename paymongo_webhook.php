<?php

  include("extensions/functions.php");
  require_once("extensions/db.php");

  header('Content-Type: application/json');

  $request = file_get_contents('php://input');
  $payload = json_decode($request, true);

  //This code will allow you to view the JSON object payload.

  $type = $payload['data']['attributes']['type'];
  $pay_method = $payload['data']['attributes']['data']['attributes']['source']['type'];

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

  if($type == 'payment.paid' && $pay_method != 'card') {
      
    $name = $payload['data']['attributes']['data']['attributes']['billing']['name']; 
    $mobile = $payload['data']['attributes']['data']['attributes']['billing']['phone']; 
    $email = $payload['data']['attributes']['data']['attributes']['billing']['email'];    
    $amount = ($payload['data']['attributes']['data']['attributes']['amount'] / 100);
    $currency = $payload['data']['attributes']['data']['attributes']['currency'];
    $method = $payload['data']['attributes']['data']['attributes']['source']['type'];
    $transaction_id = $payload['data']['attributes']['data']['id'];
    
    $sms_message = "Hello " . $name . "! Your payment for " . $amount . " " . $currency . " thru " . $method . " was successful. Thank you.";

    $email_subject = 'BOOKING CONFIRMATION';
    $email_message = 'Dear '.$name.',  Hooray! Your payment for '.$amount.' '.$currency.' thru '.$method. ' was successful. Transaction id '.$transaction_id.' . Enjoy your BaiPaJoin Adventure! Thank you! THIS IS A TEST. DO NOT REPLY!';    

    send_sms($mobile, $sms_message);
    send_email($email, $email_subject, $email_message);
  }