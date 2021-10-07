<?php
  /*include("extensions/functions.php");

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
    ";*/

  //send_email("narancit@gmail.com", "Welcome Message", $message, $img_address, $img_name);
?>

<!DOCTYPE html>

    <!-- ## THIS IS FOR RESCHEDULE -->
    <!-- <html>
      <head>
        <meta charset='utf-8'>
        <title>BaiPaJoin | Reschedule</title>
      </head>
      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url("images/resched-bg.jpg") no-repeat center;background-position:50% 360%;font:normal 15px/20px Verdana,sans-serif;'>
        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
          <div class='contents' style='text-align:center;color:#1a1a1a;'>
            <figure class='main-logo'>
              <img src='images/main-logo-green.png' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
            </figure>
            <figure >
              <img src='images/resched-img.jpg' style='max-width:100%;width:300px;height:200px;margin:0 auto;'>
            </figure>
            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Hooray! Adventure Rescheduled!</h1>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>Your adventure has been successfully rescheduled from DATE to DATE. We're happy to serve you again! Enjoy your BaiPaJoin Adventure!</p>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com]. Thank you!</a> </p>
          </div>
        </div>
      </body>
    </html> -->

    <!-- ## THIS IS FOR CANCEL -->
    <!-- <html>
      <head>
        <meta charset='utf-8'>
        <title>BaiPaJoin | Cancelation</title>
      </head>
      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url("images/cancel-bg.jpg") no-repeat center;background-position:50% 110%;background-size:350px 300px;font:normal 15px/20px Verdana,sans-serif;'>
        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
          <div class='contents' style='text-align:center;color:#1a1a1a;'>
            <figure class='main-logo'>
              <img src='images/main-logo-green.png' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
            </figure>
            <figure >
              <img src='images/cancel-img.jpg' style='max-width:100%;width:300px;height:270px;margin:0 auto;'>
            </figure>
            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Oooh No! Adventure Cancelled!</h1>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>I'm sorry to hear that you have cancel your adventure. We've recieved your request and it's being reviewed. In the meanwhile, please check your EMAIL and SMS for the updates. Stay safe and thank you for using BaiPaJoin!</p>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or did not make this changes, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
          </div>
        </div>
      </body>
    </html> -->


    <!-- ## THIS IS FOR TRANSACTION RECEIPT -->
    <!-- <html>
      <head>
        <meta charset='utf-8'>
        <title>BaiPaJoin | TRANSACTION RECEIPT</title>
      </head>
      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url("images/receipt-bg.png") no-repeat center;background-position:50% 300%;background-size:450px 450px;font:normal 15px/20px Verdana,sans-serif;'>
        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
          <div class='contents' style='text-align:center;color:#1a1a1a;'>
            <figure class='main-logo'>
              <img src='images/main-logo-green.png' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
            </figure>
            <figure >
              <img src='images/receipt-img.jpg' style='max-width:100%;width:350px;height:225px;margin:0 auto;'>
            </figure>
            <h1 style='margin:-40px 0 60px;color:#000;font-size:30px;'>Payment Successful</h1>
            <table style='width:500px;max-width:100%;margin:0 auto;background:gray;line-height:25px;'>
              <h2>Transaction Receipt</h2>
              <tr>
                <td>1</td>
                <td>[DATA]</td>
              </tr>
              <tr>
                <td>2</td>
                <td>[DATA]</td>
              </tr>
              <tr>
                <td>3</td>
                <td>[DATA]</td>
              </tr>
              <tr>
                <td>4</td>
                <td>[DATA]</td>
              </tr>
              <tr>
                <td>5</td>
                <td>[DATA]</td>
              </tr>
              <tr>
                <td>6</td>
                <td>[DATA]</td>
              </tr>
              <tr>
                <td>6</td>
                <td>[DATA]</td>
              </tr>
            </table>
            <p style='margin:50px 0 0;'>Powered by: <span style='display:block;'><img src="images/receipt-bg.png" style='width:150px;height:150px;margin:0 auto;'> </span> </p>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:0 auto;'>If you have any questions or dispute, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
          </div>
        </div>
      </body>
    </html> -->


    <!-- ## THIS IS FOR RESET PASSWORD -->
    <!-- <html>
      <head>
        <meta charset='utf-8'>
        <title>BaiPaJoin | Reset Password</title>
      </head>
      <body style='background:linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)), url("images/reset-pass-bg.jpg") no-repeat center;background-position:50% 100%;background-size:250px 250px;font:normal 15px/20px Verdana,sans-serif;'>
        <div class='wrapper' style='width:100%;max-width:1390px;margin:0 auto;position:relative;'>
          <div class='contents' style='text-align:center;color:#1a1a1a;'>
            <figure class='main-logo'>
              <img src='images/main-logo-green.png' style='max-width:100%;width:100px;height:80px;margin:0 auto;'>
            </figure>
            <figure >
              <img src='images/reset-pass-img.jpg' style='max-width:100%;width:300px;height:240px;margin:0 auto;'>
            </figure>
            <h1 style='margin:-20px 0 80px;color:#000;font-size:30px;'>Temporary Password</h1>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>We've got you covered. Don't worry! Here's your temporary password [PASSWORD]. Please make sure to change your password as soon as you've login to protect your self from hacking. </p>
            <p style='line-height:20px;width:1000px;max-width:100%;margin:50px auto;'>If you have any questions or didn't make this change, please send us an email at <a href='#' style='text-decoration:underline;color:#1a1a1a;'>[teambaipajoincebu@gmail.com].  </a> </p>
          </div>
        </div>
      </body>
    </html> -->
