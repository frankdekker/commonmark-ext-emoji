<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji;

interface EmojiDataProviderInterface
{
    /**
     * Return an array of supported emoji syntax's
     * @return string[]
     */
    public function getSupportedEmojis(): array;

    /**
     * Convert key to the native utf8 emoji
     * @return string|null returns null if the conversion couldn't be made
     */
    public function convert(string $key): ?string;
}
