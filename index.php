<?php

  ini_set('default_socket_timeout', 120);
  date_default_timezone_set('Europe/Helsinki');

  // Basic settings
  $token =                  ""; // Your Telegram BOT Token
  $allowed_chats_file =     "logs/allowed_chats.txt";
  $admins =                 []; // Array of usernames allowed to perform admin actions on the bot (e.g. ["@olaviinha", "@juhlamokka"])
  $botname =                ""; // Username of this bot (e.g. @CewlBot)
  $allow_everywhere =       false;

  $enable_gpt =             true;
  $enable_lastseen =        true;
  $disable_all_logging =    false;
  $logs_dir =               "logs/";

  $separator =              ";;";

  // ------------------------------------------------------------------------------------------------

  $allowed_chats = file_exists($allowed_chats_file) ? explode("\n", @file_get_contents($allowed_chats_file)) : array();
  $tg_endpoint = "https://api.telegram.org/bot".$token;
  $update = json_decode(file_get_contents("php://input"), TRUE);
  $admin_mode = false; $allowed = false; $sender = null; $username = null; $chat_id = null;

  include_once "sys_global_functions.php";
  include_once "sys_locale.php";

  if(isset($update) && (array_key_exists('message', $update) || array_key_exists('callback_query', $update))) {

    // Parse information accordingly from sent message (/command or regular message) or sent callback query (button click)
    $cbq = array_key_exists('callback_query', $update) ? strip_tags($update["callback_query"]["data"]) : null;
    $cbq_message = $cbq ? $update["callback_query"]["message"] : null;
    $update_message = array_key_exists('message', $update) ? $update["message"] : null;
    $check_msg = $cbq ? $cbq_message : $update_message;
    $chat_id = $check_msg["chat"]["id"];
    $msg_id = $check_msg["message_id"];
    $sender = array_key_exists('username', $check_msg["from"]) ? strip_tags($check_msg["from"]["username"]) : "unknown";
    $username = $sender;
    $message = array_key_exists('text', $check_msg) ? strip_tags($check_msg["text"]) : null;

    // Check if admin and/or allowed in chat
    $admin_mode = count($admins) > 0 && in_array($sender, $admins);
    $allowed = $allow_everywhere || in_array($chat_id, $allowed_chats);

    // --------------------------------------------------------------------------------
    //
    // Admin commands (allowed anywhere)
    //
    // --------------------------------------------------------------------------------

    if($admin_mode){

      // Test that bot works in the current chat
      if(cmd_is(["/allowed", "/test", "/chatid"])){
        $response = "<code>".$chat_id."</code>\n";
        $response .= $allowed ? $msg['already_allowed'] : $msg['not_allowed'];
        reply($response);
      }

      // Send message to a chat (/msg <chat_id> <message>)
      if(cmd_is("/msg")) { reply(arg("all", 2), arg("first")); reply($msg['message_sent']); }

      // Add/remove chat from allowed chats
      if(cmd_is(["/add", "/allow", "/enable"]))         include_once "commands/cmd_allow_chat.php";
      if(cmd_is(["/remove", "/disallow", "/disable"]))  include_once "commands/cmd_disallow_chat.php";

    }
    
    // --------------------------------------------------------------------------------
    //
    // Public commands (where allowed or if sent by admin)
    //
    // --------------------------------------------------------------------------------

    if($allowed || $admin_mode){

      // Callbacks
      if(cbq_is("delete_this_and"))               include 'commands/cbq_delete_msgs.php';

      // Unconditional commands
      if(cmd_is("/halp"))                         include "commands/cmd_help.php";
      if(cmd_is(["/8", "/kasi"]))                 include "commands/cmd_eightball.php";
      if(cmd_is("/audio"))                        send_audio("some.mp3");
      
      // GPT
      if($enable_gpt){
        include "sys_gpt.php";
        if(cmd_is(["/c"])) reply(gpt(arg("all")));
      }

      // Logging based features
      if(!$disable_all_logging){
        
        // Last seen
        if($enable_last_seen){
          include "features/lastseen.php";
          if(cmd_is(["/lastseen", "/seen", "/rippasko"])) include "commands/cmd_lastseen.php";
        }

      }

    }

  } else {
    error_log('No message or callback query');
  }

?>
