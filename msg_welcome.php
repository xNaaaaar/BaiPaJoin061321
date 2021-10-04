<?php
  include("extensions/functions.php");

  $message = "
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset='utf-8'>
      <title>BaiPaJoin | Welcome</title>
      <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
        *{padding:0;margin:0;box-sizing:border-box;}
        body{background:url('images/welcome-bg.jpg');}
        .wrapper{width:100%;max-width:1390px;margin:0 auto;position:relative;}
        .contents{text-align:center;font:400 25px/100% Bebas Neue,cursive;color:#1a1a1a;}
        .contents h1{margin:-20px 0 50px;color:#7fdcd3;font-size:60px;}
        .contents p{line-height:35px;width:1000px;max-width:100%;margin:10px auto;}
        .contents a{display:block;width:200px;height:50px;background:#7fdcd3;margin:40px auto;border-radius:10px;line-height:50px;color:#fff;text-decoration:none;}
        .main-logo img{max-width:100%;width:200px;height:200px;background:#000;margin:0 auto;}
        figure img{max-width:100%;width:600px;height:400px;background:#000;margin:0 auto;}
      </style>
    </head>

    <body>
      <div class='wrapper'>
        <div class='contents'>
          <figure class='main-logo'>
            <img src='images/main-logo.png'>
          </figure>
          <figure >
            <img src='images/welcome-img.jpg'>
          </figure>
          <h1>Welcome, [user name]</h1>
          <p>You're all set! Now you can enjoy convenient and hassle-free adventures in a click. We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
          <a href='login.php'>Login Here</a>
          <p>If you have any questions, please send an email to [baipajoin email]</p>
        </div>
      </div>
    </body>
  </html>
  ";

  //send_email("narancit@gmail.com", "Welcome Message", $message);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <title>BaiPaJoin | Welcome</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
      *{padding:0;margin:0;box-sizing:border-box;}
      body{background:url('images/welcome-bg.jpg');}
      .wrapper{width:100%;max-width:1390px;margin:0 auto;position:relative;}
      .contents{text-align:center;font:400 25px/100% Bebas Neue,cursive;color:#1a1a1a;}
      .contents h1{margin:-20px 0 50px;color:#7fdcd3;font-size:60px;}
      .contents p{line-height:35px;width:1000px;max-width:100%;margin:10px auto;}
      .contents a{display:block;width:200px;height:50px;background:#7fdcd3;margin:40px auto;border-radius:10px;line-height:50px;color:#fff;text-decoration:none;}
      .main-logo img{max-width:100%;width:200px;height:200px;background:#000;margin:0 auto;}
      figure img{max-width:100%;width:600px;height:400px;background:#000;margin:0 auto;}
    </style>
  </head>

  <body>
    <div class='wrapper'>
      <div class='contents'>
        <figure class='main-logo'>
          <img src='images/main-logo.png'>
        </figure>
        <figure >
          <img src='images/welcome-img.jpg'>
        </figure>
        <h1>Welcome, [user name]</h1>
        <p>You're all set! Now you can enjoy convenient and hassle-free adventures in a click. We are incredibly excited to have you here. Want to start your adventure? Click the button below to login.</p>
        <a href='login.php'>Login Here</a>
        <p>If you have any questions, please send an email to [baipajoin email]</p>
      </div>
    </div>
  </body>
</html>
