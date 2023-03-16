# PHP Telegram Bot core

Minimalistic PHP Telegram bot core setup & example

1. Create a new bot in Telegram using [@BotFather](https://t.me/BotFather) (Telegram's own official bot for that purpose).
2. @BotFather will provide you a token. Place token in the PHP file.
3. Place PHP file on a public server.
4. In the URL below, replace `{my_bot_token}` with your token, `{url_to_this_PHP_file}` with the URL to the PHP file located on your server (index.php from this repo) and just open the full URL in your browser:
```
https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_this_PHP_file}/
```

