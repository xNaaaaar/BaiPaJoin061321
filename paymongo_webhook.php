<?php

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

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);


    $fields = array("data" => array ("attributes" => array ("amount" => $amount, "source" => array ("id" => $id, "type" => "source"), "currency" => "PHP")));

    $jsonFields = json_encode($fields);
      
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.paymongo.com/v1/payments",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $jsonFields,
      CURLOPT_HTTPHEADER => array(
          'Authorization: Basic c2tfdGVzdF9ITFJ0NHRmYUZlMjNGUE5iZVppcmtyZXA6',
          'Content-Type: application/json'
        ),
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if(!empty($response)) {
      if(!file_exists('logs\ewallet_payment')) {
        mkdir('logs\ewallet_payment', 0777, true);
      }
      $log_file_data = 'logs\\ewallet_payment\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => '. $response . "\n" . "\n", FILE_APPEND);
    } //This code will a log.txt file to get the response of the cURL command

    curl_close($curl);
  }

  if($type == 'payment.paid') {

    /*$mobile = $payload['data']['attributes']['data']['attributes']['billing']['phone'];*/
    $mobile = '09239688932';
    /*$name = $payload['data']['attributes']['data']['attributes']['billing']['name'];*/
    $name = 'Alexis Salvador';
    $amount = $payload['data']['attributes']['data']['attributes']['amount'];
    $currency = $payload['data']['attributes']['data']['attributes']['currency'];
    $method = $payload['data']['attributes']['data']['attributes']['source']['type'];

    $amount = $amount/100; // This will ensure that cents will be separated.
    
    $message = "Hello " . $name . "! Your payment for " . $amount . " " . $currency . " thru " . $method . " was successful. Thank you.";

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.itexmo.com/php_api/api.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('1' => $mobile ,'2' => $message,'3' => 'TR-ALEXI688932_MPXBC','passwd' => '&9in[7}wh3'),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if(!empty($response)) {
      if(!file_exists('logs\sms')) {
        mkdir('logs\sms', 0777, true);
      }
      $log_file_data = 'logs\\sms\\log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, date('h:i:sa').' => Response Code: '. json_decode($response) . "\n" . " + + + Message Sent: ". $message . "\n" . "\n", FILE_APPEND);
      //This code will a log.txt file to get the response of the cURL command
    }

    curl_close($curl);
  }