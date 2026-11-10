<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji\Tests\Integration;

use FD\CommonMarkEmoji\EmojiDataProvider;
use FD\CommonMarkEmoji\EmojiExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class EmojiParserTest extends TestCase
{
    private MarkdownConverter $converter;

    public function setUp(): void
    {
        parent::setUp();
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new EmojiExtension(EmojiDataProvider::full()));

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * @throws CommonMarkException
     */
    public function testConvert(): void
    {
        static::assertSame("<p>👍</p>\n", $this->converter->convert('(y)')->getContent());
        static::assertSame("<p>👍</p>\n", $this->converter->convert('(thumbsup)')->getContent());

        // support case-insensitive
        static::assertSame("<p>👍</p>\n", $this->converter->convert('(Y)')->getContent());
        static::assertSame("<p>😛 😛</p>\n", $this->converter->convert(':P :p')->getContent());

        // only replace on word boundary
        static::assertSame("<p>😒</p>\n", $this->converter->convert(':s')->getContent());
        static::assertSame("<p>test😒</p>\n", $this->converter->convert('test:s')->getContent());
        static::assertSame("<p>parent::skip</p>\n", $this->converter->convert('parent::skip')->getContent());

        // support multiple emoji's
        static::assertSame("<p>👎👍</p>\n", $this->converter->convert('(n)(thumbsup)')->getContent());
        static::assertSame("<p>😃😃</p>\n", $this->converter->convert(':):)')->getContent());

        // support for slack notation
        static::assertSame("<p>👍</p>\n", $this->converter->convert(':thumbsup:')->getContent());

        // ignore emoji's inside code blocks
        static::assertSame("<pre><code>(y)\n</code></pre>\n", $this->converter->convert("```\n(y)\n```")->getContent());
    }

    #[DataProvider('provideNamesAndShortcuts')]
    public function testNamesAndShortcuts(string $markdown, string $expected): void
    {
        static::assertSame('<p>' . $expected . "</p>\n", $this->converter->convert($markdown)->getContent());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideNamesAndShortcuts(): iterable
    {
        yield 'd prefix' => [':dog:', '🐶'];
        yield 'o prefix' => [':open_mouth:', '😮'];
        yield 'p prefix' => [':pig:', '🐷'];
        yield 'shrug' => [':shrug:', '🤷'];
        yield 'smile' => [':smile:', '😄'];
        yield 'smirk' => [':smirk:', '😏'];
        yield 'z prefix' => [':zebra:', '🦓'];
        yield 'unaffected name' => [':rwanda:', '🇷🇼'];
        yield 'standalone shortcuts' => [':s :z :d :o :p', '😒 😒 😄 😮 😛'];
        yield 'shortcut prefixes in text' => [
            ':something :zoom :dog :ordinary :plain parent::skip',
            ':something :zoom :dog :ordinary :plain parent::skip'
        ];
        yield 'parenthesized names' => ['(shrug) (smile) (zebra)', '🤷 😄 🦓'];
        yield 'unknown names' => [':some_unknown_emoji: (some_unknown_emoji)', ':some_unknown_emoji: (some_unknown_emoji)'];
        yield 'neighboring text' => ['before:shrug::smile:(zebra):d after', 'before🤷😄🦓😄 after'];
    }

    public function testExcludedShortcuts(): void
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new EmojiExtension(EmojiDataProvider::full([':*', ':s', '(y)'])));
        $converter = new MarkdownConverter($environment);

        static::assertSame(
            "<p>:* :s (y) 🤷 😄 🦓 👍 😄</p>\n",
            $converter->convert(':* :s (y) :shrug: :smile: :zebra: (thumbsup) :d')->getContent()
        );
    }

    public function testAllSupportedColonNames(): void
    {
        /** @var array<int|string, string> $emojis */
        $emojis = require __DIR__ . '/../../resources/full.php';
        foreach ($emojis as $name => $emoji) {
            // Other dataset keys (such as +1) are outside the named-emoji syntax.
            if (preg_match('/^[\w-]+$/', (string)$name) !== 1) {
                continue;
            }

            static::assertSame(
                '<p>' . $emoji . "</p>\n",
                $this->converter->convert(':' . $name . ':')->getContent(),
                (string)$name
            );
        }
    }
}
