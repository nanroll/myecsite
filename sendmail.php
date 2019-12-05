<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <?php
      mb_language("Japanese");
      mb_internal_encoding("UTF-8");
    //   $to = $_POST['to'];
      $to = "aoichan.com@gmail.com";
      $title = $_POST['title'];
      $content = $_POST['content'];
      $enveroop = "From: aoichan.com@gmail.com";
      
      if(mb_send_mail($to, $title, $content, $enveroop)){
        echo "メールを送信しました";
      } else {
        echo "メールの送信に失敗しました";
      };
    ?>
  </body>
</html>