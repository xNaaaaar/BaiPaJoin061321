<?php
  include("extensions/functions.php");

  $img_address = array();
  $img_name = array();

  array_push($img_address,
             'images/welcome-bg.jpg',
             'images/main-logo-green.png',
             'images/welcome-img.jpg');

  array_push($img_name,
             'background',
             'logo',
             'main');

  $message = "
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset='utf-8'>
        <title>BaiPaJoin | Welcome</title>
      </head>
      <body style='background:url(\"cid:background\");font:normal 15px/20px Verdana,sans-serif;'>
        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
          <div class='contents' style='text-align:center;color:#1a1a1a;'>
            <figure class='main-logo'>
              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
            </figure>
            <figure >
              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
            </figure>
            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Welcome, Roberto Mangalabas</h1>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>You're all set! Now you can enjoy convenient and hassle-free adventures in a click. We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
            <a href='localhost/BaipaJoin42/login.php' style='display:block;width:140px;height:35px;background:#7fdcd3;margin:0 auto;border-radius:10px;line-height:35px;color:#fff;text-decoration:none;'>Login Here</a>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions, please send an email to <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
          </div>
        </div>
      </body>
    </html>
    ";

  //send_email("narancit@gmail.com", "Welcome Message", $message, $img_address, $img_name);
?>
<!DOCTYPE html>
    <html>
      <head>
        <meta charset='utf-8'>
        <title>BaiPaJoin | Welcome</title>
      </head>
      <body style='background:url(\"cid:background\");font:normal 15px/20px Verdana,sans-serif;'>
        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
          <div class='contents' style='text-align:center;color:#1a1a1a;'>
            <figure class='main-logo'>
              <img src='cid:logo' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
            </figure>
            <figure >
              <img src='cid:main' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
            </figure>
            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Welcome, Roberto Mangalabas</h1>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>You're all set! Now you can enjoy convenient and hassle-free adventures in a click. We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
            <a href='localhost/BaipaJoin42/login.php' style='display:block;width:140px;height:35px;background:#7fdcd3;margin:0 auto;border-radius:10px;line-height:35px;color:#fff;text-decoration:none;'>Login Here</a>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions, please send an email to <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]</a> </p>
          </div>
        </div>
      </body>
    </html>
