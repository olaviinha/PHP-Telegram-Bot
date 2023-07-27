# PHP Telegram Bot

## Minimal bot example

[minimal_tg_bot_example.php](https://github.com/olaviinha/PHP-Telegram-Bot/blob/main/minimal_tg_bot_example.php) is a fully functional Telegram bot written in PHP with a single example command. Leaving it here solely because a file like this would have helped me when I started to make a bot from scratch, so hopefully it will help someone else. To make the file function as a bot in Telegram, see [Setup](#setup).

## Setup

1. Create a new bot in Telegram using [@BotFather](https://t.me/BotFather) (Telegram's own official bot for that purpose).
2. @BotFather will provide you a token. Place token in the PHP file.
3. Place PHP file on a public server.
4. In the URL below, replace `{my_bot_token}` with your token, `{url_to_this_PHP_file}` with the URL to the PHP file located on your server (index.php or even_simpler.php from this repo) and just open the full URL in your browser:
```
https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_this_PHP_file}/
```

