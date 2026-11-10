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

    public function testNamedEmojiMatchPrecedesShortcut(): void
    {
        foreach ([EmojiDataProvider::light(), EmojiDataProvider::full()] as $dataProvider) {
            $pattern = '/' . $dataProvider->getSupportedEmojis() . '/i';
            static::assertSame(1, preg_match($pattern, ':smile:', $matches));
            static::assertSame([':smile:', '', 'smile'], $matches);
            static::assertSame(1, preg_match($pattern, '(smile)', $matches));
            static::assertSame(['(smile)', 'smile'], $matches);
            static::assertSame(1, preg_match($pattern, ':s', $matches));
            static::assertSame([':s'], $matches);
        }
    }

    public function testConvertWithShortcutFilter(): void
    {
        $dataProvider = EmojiDataProvider::light(['(y)']);
        static::assertNull($dataProvider->convert('(y)'));
        static::assertSame('👍', $dataProvider->convert('(thumbsup)'));
    }
}
