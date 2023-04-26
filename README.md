[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
![Run tests](https://github.com/frankdekker/commonmark-ext-emoji/workflows/Run%20checks/badge.svg)

# Emoji Extension for CommonMark 
[The PHPLeague/CommonMark](https://github.com/thephpleague/commonmark) extension for emoji's support. 

### Support emoji's:
- [EmojiDataProvider::full()](https://github.com/frankdekker/commonmark-ext-emoji/blob/main/resources/full.php)
- [EmojiDataProvider::light()](https://github.com/frankdekker/commonmark-ext-emoji/blob/main/resources/light.php)
- [Shortcuts](https://github.com/frankdekker/commonmark-ext-emoji/blob/main/resources/shortcuts.php)


## Installation
Include the library as dependency in your own project via:
```php
composer require "fdekker/commonmark-ext-emoji"
```

## Usage:

```php
// Define your configuration, if needed
$config = [];

// Configure the Environment with all the CommonMark and GFM parsers/renderers
$environment = new Environment($config);
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new GithubFlavoredMarkdownExtension());
$environment->addExtension(new EmojiExtension(EmojiDataProvider::light()));

$converter = new MarkdownConverter($environment);
echo $converter->convert('Works (y) :thumbsup: (thumbsup)');
```
Outputs:
```text
Works 👍 👍 👍
```

To read more about configuring extension visit: https://commonmark.thephpleague.com/2.4/extensions/github-flavored-markdown/


## Customization

You can implement `EmojiDataProviderInterface` to supply your own emoji's list.
