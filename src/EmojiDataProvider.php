<?php
declare(strict_types=1);

namespace FD\CommonMarkEmoji;

class EmojiDataProvider implements EmojiDataProviderInterface
{
    /** @var string[]|null */
    private ?array $supportedEmojis = null;

    /** @var array<string, string>|null */
    private ?array $emojis = null;

    /** @var array<string, string>|null */
    private ?array $shortcuts = null;

    private function __construct(private readonly string $emojiPath, private readonly string $shortcutsPath)
    {
    }

    public function getSupportedEmojis(): array
    {
        if ($this->supportedEmojis !== null) {
            return $this->supportedEmojis;
        }

        $this->emojis    ??= require $this->emojiPath;
        $this->shortcuts ??= require $this->shortcutsPath;

        /** @var string[] $keys */
        $keys = array_keys($this->shortcuts);
        foreach (array_keys($this->emojis) as $key) {
            $keys[] = '(' . $key . ')';
        }

        return $this->supportedEmojis = $keys;
    }

    public function convert(string $key): ?string
    {
        $this->emojis    ??= require $this->emojiPath;
        $this->shortcuts ??= require $this->shortcutsPath;

        // convert shortcut to key
        $key = $this->shortcuts[$key] ?? $key;

        // convert key to emoji
        return $this->emojis[$key] ?? null;
    }

    public static function full(): EmojiDataProvider
    {
        return new EmojiDataProvider(__DIR__ . '/../resources/full.php', __DIR__ . '/../resources/shortcuts.php');
    }

    public static function light(): EmojiDataProvider
    {
        return new EmojiDataProvider(__DIR__ . '/../resources/light.php', __DIR__ . '/../resources/shortcuts.php');
    }
}
