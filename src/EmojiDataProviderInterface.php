<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji;

interface EmojiDataProviderInterface
{
    /**
     * Returns the regex for the supported emoji's
     */
    public function getSupportedEmojis(): string;

    /**
     * Convert key to the native utf8 emoji
     * @return string|null returns null if the conversion couldn't be made
     */
    public function convert(string $key): ?string;
}
