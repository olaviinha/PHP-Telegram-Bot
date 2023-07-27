<?php

  date_default_timezone_set('Europe/Helsinki');

  // Show exact time vs. fuzzy time
  $exact_time = false;

  $seek_user = str_replace("@", "", arg("first"));
  $search_query = str_replace("@", "", arg("all"));

  // You may interpret e.g. first name as username here
  $seek_user = interpret($seek_user, "jaakko", "SatanicJaakko666");
  $seek_user = interpret($seek_user, "pertti", "TommiLantinen76");
  
  $usr = strtolower($seek_user);
  $file = "${logs_dir}${chat_id}${usr}.txt";

  if(file_exists($file)) {
    $data = json_decode(file_get_contents($file));
    $now = time();
    $ago = $now - intval($data->stamp);
    $hrs = gmdate("G", $ago);
    $mins = ltrim(gmdate("i", $ago), "0");
    $secs = ltrim(gmdate("s", $ago), "0");

    if(strlen($mins) == 2 && $mins[0] == "0") $mins = substr($mins, 1);
    if(strlen($secs) == 2 && $secs[0] == "0") $secs = substr($secs, 1);

    $hrs = intval($hrs);
    $mins = intval($mins);
    $secs = intval($secs);
    $last_words = $data->last_words;

    // Build answer
    $answer = ucfirst($search_query);
    $answer .= cmd_is("/rippasko") ? " haudas " : " idlannu ";
    if($exact_time){
      if($hrs > 0) $answer .= $hrs == 1 ? "1 tunnin, " : "${hrs} tuntia, ";
      if($mins > 0) $answer .= $mins == 1 ? "1 minuutin" : "${mins} minuuttia";
      if($secs == 0) $answer .= ".";
      if(($hrs > 0 || $mins > 0) && $secs > 0) $answer .= " ja";
      if($secs > 0) $answer .= $secs == 1 ? " yhden sekunnin." : " ${secs} sekuntia.";
    } else {
      if($hrs == 0 && $mins == 0 && $secs > 0) $answer .= "${secs} sekuntia.";
      if($hrs == 0 && $mins == 1) $answer .= "minuutin.";
      if($hrs == 0 && $mins > 1 && $mins <= 50) $answer .= "${mins} minuuttia.";
      if($hrs == 0 && $mins >= 50) $answer .= "kohta tunnin.";
      if($hrs == 1 && $mins <= 15) $answer .= "yli tunnin.";
      if($hrs == 1 && $mins >= 15 && $mins <= 30) $answer .= "kohta puolitoista tuntia.";
      if($hrs == 1 && $mins >= 30 && $mins <= 45) $answer .= "yli puolitoista tuntia.";
      if($hrs == 1 && $mins >= 45) $answer .= "melkein 2 tuntia.";
      if($hrs > 1) $answer .= "yli ${hrs} tuntia... RIP.";
    }
    $answer .= "\nViimeiset sanat: ${last_words}";
    reply($answer);
  } else {
    $answer = "Ei oo näkynyt...";
    reply($answer);
  }

?>
