<?php

  // Create webhook. Replace {my_bot_token} with your bot token, and {url_to_this_PHP_file} with the url of this file
  // and open the full URL in your browser. Webhook should be set and bot should work.
  // https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_this_PHP_file}

  ini_set('default_socket_timeout', 120);
  $token = '';          // Telegram bot token
  $allowed_chats = [];  // Chat IDs (optional)
  $admins = [];         // Telegram username (optional)
  
  $path = "https://api.telegram.org/bot".$api_key;
  $update = json_decode(file_get_contents("php://input"), TRUE);

  if(array_key_exists('message', $update)){
    $chat_id = $update["message"]["chat"]["id"];
    if(array_key_exists('username', $update["message"]["from"])) $sender = strip_tags($update["message"]["from"]["username"]);
    if(array_key_exists('text', $update["message"])) $message = strip_tags($update["message"]["text"]);
  }

  function reply($answer) {
    global $chat_id, $chat;
    file_get_contents($path."/sendmessage?chat_id=".$chat_id."&text=".$answer."&parse_mode=html");
  }

  if(count($admins) > 0 && in_array($sender, $admins)) $admin_mode = true;

  // Commands that work from admins only.
  if($admin_mode){
    if(strpos($message, "/admincommand1") === 0) {
      // Do something
      reply("You successfully executed an admin command, /admincommand1");
    }
    if(strpos($message, "/admincommand2") === 0) {
      // Do something else
      reply("You successfully executed an admin command, /admincommand2");
    }
  }

  // Commands that work from anybody.
  if(count($allowed_chats) == 0 || in_array($chatId, $allowed_chats))
    if (strpos($message, "/publiccommand1") === 0) {
      // Do something
      reply("You successfully executed a public command, /publiccommand1");
    }
    if (strpos($message, "/publiccommand2") === 0) {
      // Do something else
      reply("You successfully executed a public command, /publiccommand2");
    }
  }

?>
