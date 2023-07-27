<?php

$response = "
<b>Available commands:</b>
/seen <i>user</i> - Last seen <i>user</i>
/8 - Eightball
/c - Converse with ChatGPT
/audio - Share a good song
";

reply_with_delete($response);

?>
