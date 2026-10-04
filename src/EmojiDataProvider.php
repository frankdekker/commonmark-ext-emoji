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

    /**
     * @param list<string> $excludeShortcuts
     */
    private function __construct(
        private readonly string $emojiPath,
        private readonly string $shortcutsPath,
        private readonly array $excludeShortcuts
    ) {
    }

    /**
     * @param list<string> $excludeShortcuts
     */
    public static function full(array $excludeShortcuts = []): EmojiDataProvider
    {
        return new EmojiDataProvider(__DIR__ . '/../resources/full.php', __DIR__ . '/../resources/shortcuts.php', $excludeShortcuts);
    }

    /**
     * @param list<string> $excludeShortcuts
     */
    public static function light(array $excludeShortcuts = []): EmojiDataProvider
    {
        return new EmojiDataProvider(__DIR__ . '/../resources/light.php', __DIR__ . '/../resources/shortcuts.php', $excludeShortcuts);
    }

    public function getSupportedEmojis(): string
    {
        if ($this->supportedEmojis !== null) {
            return $this->supportedEmojis;
        }

        $this->emojis ??= require $this->emojiPath;

        $shortcuts = [];
        foreach (array_keys($this->getShortcuts()) as $key) {
            $shortcuts[] = preg_quote((string)$key, '/');
        }

        return $this->supportedEmojis = implode('|', $shortcuts) . '|\\(([\w-]+)\\)|:([\w-]+):';
    }

    public function convert(string $key): ?string
    {
        $this->emojis ??= require $this->emojiPath;
        $shortcuts    = $this->getShortcuts();

        // normalize key
        $key = strtolower($key);

        // convert shortcut to key
        $key = $shortcuts[$key] ?? $key;

        // remove any leading and trailing ()
        if ((str_starts_with($key, '(') && str_ends_with($key, ')')) || (str_starts_with($key, ':') && str_ends_with($key, ':'))) {
            $key = substr($key, 1, -1);
        }

        // convert key to emoji
        return $this->emojis[$key] ?? null;
    }

    /**
     * @return string[]
     */
    private function getShortcuts(): array
    {
        if ($this->shortcuts !== null) {
            return $this->shortcuts;
        }

        $shortcuts = require $this->shortcutsPath;
        foreach ($this->excludeShortcuts as $excludeShortcut) {
            unset($shortcuts[$excludeShortcut]);
        }

        return $this->shortcuts = $shortcuts;
    }
}
