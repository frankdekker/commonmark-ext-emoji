<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji;

use function array_keys;
use function implode;
use function preg_quote;

class EmojiDataProvider implements EmojiDataProviderInterface
{
    /** @var string|null */
    private ?string $supportedEmojis = null;

    /** @var array<string, string>|null */
    private ?array $emojis = null;

    /** @var array<string, string>|null */
    private ?array $shortcuts = null;

    private function __construct(private readonly string $emojiPath, private readonly string $shortcutsPath)
    {
    }

    public static function full(): EmojiDataProvider
    {
        return new EmojiDataProvider(__DIR__ . '/../resources/full.php', __DIR__ . '/../resources/shortcuts.php');
    }

    public static function light(): EmojiDataProvider
    {
        return new EmojiDataProvider(__DIR__ . '/../resources/light.php', __DIR__ . '/../resources/shortcuts.php');
    }

    public function getSupportedEmojis(): string
    {
        if ($this->supportedEmojis !== null) {
            return $this->supportedEmojis;
        }

        $this->emojis    ??= require $this->emojiPath;
        $this->shortcuts ??= require $this->shortcutsPath;

        $shortcuts = [];
        foreach (array_keys($this->shortcuts) as $key) {
            $shortcuts[] = preg_quote((string)$key, '/');
        }

        return $this->supportedEmojis = implode('|', $shortcuts) . '|\\(([\w-]+)\\)|:([\w-]+):';
    }

    public function convert(string $key): ?string
    {
        $this->emojis    ??= require $this->emojiPath;
        $this->shortcuts ??= require $this->shortcutsPath;

        // convert shortcut to key
        $key = $this->shortcuts[$key] ?? $key;

        // remove any leading and trailing ()
        if ((str_starts_with($key, '(') && str_ends_with($key, ')')) || (str_starts_with($key, ':') && str_ends_with($key, ':'))) {
            $key = substr($key, 1, -1);
        }

        // convert key to emoji
        return $this->emojis[$key] ?? null;
    }
}
