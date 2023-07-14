<?php
  $token = ''; // Telegram bot token
  $path = "https://api.telegram.org/bot".$token;
  $update = json_decode(file_get_contents("php://input"), TRUE);

  if(array_key_exists('message', $update)){
    $chat_id = $update["message"]["chat"]["id"];
    if(array_key_exists('text', $update["message"])) $message = strip_tags($update["message"]["text"]);
  }

  if (strpos($message, "/somecommand") === 0) {
    // Do a thing
    $answer = "I just did a thing.";
    file_get_contents($path."/sendmessage?chat_id=".$chat_id."&text=".$answer."&parse_mode=html");
  }
?>
