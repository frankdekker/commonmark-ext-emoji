<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji\Tests\Integration;

use FD\CommonMarkEmoji\EmojiExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversNothing;
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
        $environment->addExtension(new EmojiExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * @throws CommonMarkException
     */
    public function testConvert(): void
    {
        static::assertSame("<p>👍</p>\n", $this->converter->convert('(y)')->getContent());
        static::assertSame("<p>👍</p>\n", $this->converter->convert('(thumbsup)')->getContent());

        // support multiple emoji's
        static::assertSame("<p>👎👍</p>\n", $this->converter->convert('(n)(thumbsup)')->getContent());
        static::assertSame("<p>😃😃</p>\n", $this->converter->convert(':):)')->getContent());

        // support for slack notation
        static::assertSame("<p>👍</p>\n", $this->converter->convert(':thumbsup:')->getContent());

        // ignore emoji's inside code blocks
        static::assertSame("<pre><code>(y)\n</code></pre>\n", $this->converter->convert("```\n(y)\n```")->getContent());
    }
}
