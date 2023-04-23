[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF)](https://php.net/)
![Run tests](https://github.com/frankdekker/commonmark-ext-emoji/workflows/Run%20checks/badge.svg)

# Emoji Extension for CommonMark 
[The PHPLeague/CommonMark](https://github.com/thephpleague/commonmark) extension for emoji's support. 

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
$environment->addExtension(new EmojiExtension());

$converter = new MarkdownConverter($environment);
echo $converter->convert('Works (y) :thumbsup: (thumbsup)');
```
Outputs:
```text
Works 👍 👍 👍
```

Read more about adding an extension: https://commonmark.thephpleague.com/2.4/extensions/github-flavored-markdown/
