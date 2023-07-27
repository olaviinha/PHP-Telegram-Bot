<?php
  date_default_timezone_set('Europe/Helsinki');
  $usr = strtolower($sender);
  $data = array(
    'username' => $sender,
    'date' => date("Y-m-d H:i:s"),
    'stamp' => time(),
    'last_words' => $message
  );
  file_put_contents("${logs_dir}${chat_id}${usr}.txt", json_encode($data));
?>
