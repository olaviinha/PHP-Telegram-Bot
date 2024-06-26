# PHP Telegram Bot

## Minimal bot example

[minimal_tg_bot_example.php](https://github.com/olaviinha/PHP-Telegram-Bot/blob/main/minimal_tg_bot_example.php) is a fully functional Telegram bot written in PHP with a single example command. Leaving it here solely because a file like this would have helped me when I started to make a bot from scratch, so hopefully it will help someone else. To make the file function as a bot in Telegram, see [Setup](#setup).

## Regular bot example

The rest of the files in this repository represent a fully functional, rationally structured bot written in PHP. Note that this bot uses flat files (in `logs/` dir) instead of a database for all features that require logging or saving data of any kind.

- All the important settings and commands are listed neatly in `index.php`.
- Code of each command (when needed) is listed  in, and included from, `commands/` dir.
- `sys_gpt.php` contains the _necessities_ to utilize GPT3 or GPT4 (chatGPT). I think it currently does not contain e.g. remembering past convos – will add that later.
- `sys_global_functions.php` contains all the necessary PHP functions.
- Localization can be found in `sys_locale.php` – it is perhaps not the best way to do localization out there, but it works as long as your bot has one main language.

## Setup

Whether you use just `minimal_tg_bot_example.php` or the whole repository, you must setup your bot as follows:

1. Create a new bot in Telegram using [@BotFather](https://t.me/BotFather) (Telegram's own official bot for that purpose).
2. @BotFather will provide you a token. Place token in the PHP file.
3. Place PHP file on a public server.
4. Set write permission to `logs` dir (i.e. `chown -R www-data:www-data logs`) if you want to enable features and commands that require any kind of logging.
5. In the URL below, replace `{my_bot_token}` with your token, `{url_to_this_PHP_file}` with the URL to the PHP file located on your server (index.php or even_simpler.php from this repo) and just open the full URL in your browser:
```
https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_this_PHP_file}/
```

