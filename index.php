<?php

  // Once this file is configured and online, create webhook by opening the following URL in browser.
  // Replace {my_bot_token} and {url_to_this_PHP_file} accordingly.
  // https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_this_PHP_file}

  ini_set('default_socket_timeout', 120);
  $token = '';          // Telegram bot token
  $allowed_chats = [];  // Chat IDs where this bot works (optional)
  $admins = [];         // Telegram usernames (optional)
  
  $path = "https://api.telegram.org/bot".$token;
  $update = json_decode(file_get_contents("php://input"), TRUE);
  $admin_mode = false; $allowed = false;
  
  if(array_key_exists('message', $update)){
    $chat_id = $update["message"]["chat"]["id"];
    $sender = "<unknown sender>";
    if(array_key_exists('username', $update["message"]["from"])) $sender = strip_tags($update["message"]["from"]["username"]);
    if(array_key_exists('text', $update["message"])) $message = strip_tags($update["message"]["text"]);
    $admin_mode = count($admins) > 0 && in_array($sender, $admins);
    $allowed = count($allowed_chats) == 0 || in_array($chatId, $allowed_chats);
  }

  function reply($answer, $chat_id=$chat_id, $path=$path) {
    file_get_contents($path."/sendmessage?chat_id=".$chat_id."&text=".$answer."&parse_mode=html");
  }

  // Commands that work from admins only.
  if($allowed && $admin_mode){
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
  if($allowed)
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
