<?php
  $msg_cmd = explode(":", $cbq)[1];
  $msg_rpl = $cbq_message["message_id"];
  delete_msg([$msg_cmd, $msg_rpl]);
?>
