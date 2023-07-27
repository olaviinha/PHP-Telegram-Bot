<?php

$openai_key = ""; // Your OpenAI API key
$auth = "Authorization: Bearer ".$openai_key;

$gpt_v = 4;
$openai_url = $gpt_v==4 ? "https://api.openai.com/v1/chat/completions" : "https://api.openai.com/v1/completions";
$dalle_url = "https://api.openai.com/v1/images/generations";

function gpt($prompt, $long=false, $temperature=0.7, $stop_sequence="\\n") {
  global $auth, $openai_url, $gpt_v;
  typing();
  if($gpt_v == 3) {
    $postobj = array(
        "model" => "text-davinci-003",
        "prompt" => $prompt,
        "max_tokens" => $long ? 800 : 100,
        "temperature" => $temperature,
        "top_p" => 1,
        "n" => 1,
        "stream" => false,
        "stop" => $stop_sequence,
        "frequency_penalty" => 1
    );
  }

  if($gpt_v == 4) {
    $postobj = array(
      "model" => "gpt-4",
      "messages" => array(
        array(
          "role" => "system", 
          "content" => $prompt 
        )
      ),
      "max_tokens" => $long ? 800 : 100,
      "temperature" => 0.7,
      "top_p" => 1,
      "n" => 1,
      "stream" => false,
      "frequency_penalty" => 1
    );
  }
  
  $pjson = json_encode($postobj);
  $ch = curl_init($openai_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $auth ));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $pjson);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);
  curl_close($ch);

  $dcd = json_decode($result);
  $response = $gpt_v == 3 ? $dcd->choices[0]->text : $dcd->choices[0]->message->content;
  return $response;
  
}

?>
