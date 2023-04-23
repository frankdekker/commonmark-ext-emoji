<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji\Tests\Unit;

use FD\CommonMarkEmoji\EmojiDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmojiDataProvider::class)]
class EmojiDataProviderTest extends TestCase
{
    public function testLight(): void
    {
        $dataProvider = EmojiDataProvider::light();
        static::assertStringContainsString('[\w-]+', $dataProvider->getSupportedEmojis());
        static::assertStringContainsString('[\w-]+', $dataProvider->getSupportedEmojis());
    }

    public function testFull(): void
    {
        $dataProvider = EmojiDataProvider::full();
        static::assertStringContainsString('[\w-]+', $dataProvider->getSupportedEmojis());
        static::assertStringContainsString('[\w-]+', $dataProvider->getSupportedEmojis());
    }

    public function testConvert(): void
    {
        $dataProvider = EmojiDataProvider::light();
        static::assertSame('👍', $dataProvider->convert('(y)'));
        static::assertSame('👍', $dataProvider->convert('(thumbsup)'));
    }
}
