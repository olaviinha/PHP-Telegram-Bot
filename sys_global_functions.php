<?php

// Check if command starts with x (i.e. /commandthing is accepted as /command)
function cmd_is($cmds, $exact=false) {
  global $message, $sender, $botname;
  if($sender == $botname) return false;
  $cmds = is_array($cmds) ? $cmds : array($cmds);
  foreach($cmds as $cmd){
    if(!$exact && strpos($message, $cmd) === 0) return true;
    if($exact){
      $incoming = strpos($message, " ") ? explode(" ", $message)[0] : $message;
      if($cmd == $incoming) return true;
    }
  }
  return false;
}

// Check if callback query starts with x (i.e. delete:123 is accepted as delete)
function cbq_is($cmd) {
  global $cbq, $sender, $botname;
  if($sender == $botname) return false;
  return strpos($cbq, $cmd) === 0 ? true : false;
}

// Get command args, eats
// $no = "all" (default), number, "first" or "last"
// $from = start from arg
function arg($no="all", $from=1){
  global $message;
  if(strpos($message, " ")) {
    $pcs = explode(" ", $message);
    $pcs = array_slice($pcs, $from);
    $all_args = implode(" ", $pcs);
    // $all_args = str_replace($pcs[0]." ", "", $message);
    $args = strpos($all_args, " ") > -1 ? explode(" ", $all_args) : array($all_args);
    if(is_numeric($no)) return $args[$no];
    if($no=="first") return $args[0];
    if($no=="last") return array_pop($pcs);
    if($no=="all") return $all_args;
  } else {
    return false;
  }
}

// Check if arg starts with x
function arg_is($arg, $position="first") {
  global $message;
  return $arg && strpos($message, $arg) === 0;
}

// Pick random from array
function one_of($arr){
  if(is_array($arr)){
    return array_rand(array_flip($arr));
  } else {
    return $arr;
  }
}

// Send "<bot> is typing..." status
function typing(){
  global $chat_id, $tg_endpoint;
  file_get_contents($tg_endpoint."/sendchataction?chat_id=".$chat_id."&action=typing");
}

// Interpret word1|word2 as word3 (i.e. fix faulty input to correct input)
function interpret($haystack, $typoed, $fixed, $fuzzy=true){
  if(!is_array($typoed)) $typoed = strpos($typoed, "|") > -1 ? explode("|", $typoed) : array($typoed);
  foreach($typoed as $input){
    if(!$fuzzy && strtolower($haystack) == strtolower($input)) {
      return $fixed;
    }
    if($fuzzy && strpos(strtolower($haystack), strtolower($input)) > -1) {
      return $fixed;
    }
  }
  return $haystack;
}

// Delete message(s), eats message id or array of message ids
function delete_msg($msgs){
  global $chat_id, $tg_endpoint;
  $msgs = is_array($msgs) ? $msgs : array($msgs);
  foreach($msgs as $msgx){
    file_get_contents($tg_endpoint."/deletemessage?chat_id=".$chat_id."&message_id=".$msgx);
  }
}

// Reply with a delete button
function reply_with_delete($response){
  global $chat_id, $tg_endpoint, $msg_id, $msg;
  $keyboard = json_encode([
    'inline_keyboard' => [
      [['text' => one_of($msg['ok_remove']), 'callback_data' => "delete_this_and:${msg_id}"]]
    ]
  ]);
  $response = $response ? $response : one_of($msg['error']);
  reply($response, null, false, true, $keyboard);
}

// Send textual response to Telegram
function reply($answer, $to=null, $preview=false, $url_encode=true, $keyboard=null) {
  global $chat_id, $tg_endpoint;
  $to = $to != null ? $to : $chat_id;
  $answer = $url_encode ? urlencode($answer) : $answer;
  $url = $tg_endpoint."/sendmessage?chat_id=".$to."&text=".$answer."&parse_mode=html&disable_web_page_preview=".!$preview."&reply_markup=".$keyboard;
  $contents = @file_get_contents($url);
  for($i=0; $i < 5; $i++){
    if($contents === FALSE){
      if ($i > 0) sleep(1);
      $contents = @file_get_contents($url);
      $success = true;
    } else {
      $success = false;
      break;
    }
  }
  return $success ? $contents : false;
}

// Perform CURL with given data
function curl_req($url, $data, $file_path) {
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type:multipart/form-data"
  ));
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
  curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path));
  $output = curl_exec($ch);
  // return $output;
}

// Send image
function send_image($file_path, $caption=null){
  global $tg_endpoint, $chat_id;
  curl_req(
    $tg_endpoint."/sendphoto?chat_id=".$chat_id."caption=".$caption,
    array(
      'chat_id' => $chat_id,
      'caption' => $caption,
      'photo'   => new CURLFile(realpath($file_path))
    ),
    $file_path
  );
}

// Send audio
function send_audio($file_path) {
  global $tg_endpoint, $chat_id;
  curl_req(
    $tg_endpoint."/sendaudio?chat_id=".$chat_id,
    array(
      'chat_id' => $chat_id,
      'audio'   => new CURLFile(realpath($file_path))
    ),
    $file_path
  );
}

// Send voice message
// Note that voice message needs to be an .OGG file encoded with OPUS
function send_voice($file_path) {
  global $tg_endpoint, $chat_id;
  curl_req(
    $tg_endpoint."/sendvoice?chat_id=".$chat_id,
    array(
      'chat_id' => $chat_id,
      'audio'   => new CURLFile(realpath($file_path))
    ),
    $file_path
  );
}

// Shortcut for reply(one_of($msg['ok']));
function ok() {
  global $msg;
  reply(one_of($msg['ok']));
}

// Shortcut for reply_with_delete(one_of($msg['ok']));
function ok_del() {
  global $msg;
  reply_with_delete(one_of($msg['ok']));
}

// Shortcut for reply(one_of($msg['error']));
function fail() {
  global $msg;
  reply(one_of($msg['error']));
}

// Shortcut for reply(one_of($msg['not_understood']));
function what() {
  global $msg;
  reply(one_of($msg['not_understood']));
}

?>
