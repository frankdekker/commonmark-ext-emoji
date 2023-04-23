<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji\Tests\Unit;

use FD\CommonMarkEmoji\EmojiDataProviderInterface;
use FD\CommonMarkEmoji\EmojiParser;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use League\CommonMark\Reference\ReferenceMapInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmojiParser::class)]
class EmojiParserTest extends TestCase
{
    private MockObject&EmojiDataProviderInterface $dataProvider;
    private EmojiParser $parser;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->dataProvider = $this->createMock(EmojiDataProviderInterface::class);
        $this->parser       = new EmojiParser($this->dataProvider);
    }

    public function testGetMatchDefinition(): void
    {
        $this->dataProvider->expects(self::once())->method('getSupportedEmojis')->willReturn(['foo', 'bar']);

        static::assertEquals(InlineParserMatch::oneOf('foo', 'bar'), $this->parser->getMatchDefinition());
    }

    /**
     * @throws Exception
     */
    public function testParseShouldSkipNonMatch(): void
    {
        $context = (new InlineParserContext(
            $this->createMock(Cursor::class),
            $this->createMock(AbstractBlock::class),
            $this->createMock(ReferenceMapInterface::class)
        ));
        $context = $context->withMatches(['foobar']);

        $this->dataProvider->expects(self::once())->method('convert')->with('foobar')->willReturn(null);

        static::assertFalse($this->parser->parse($context));
    }

    /**
     * @throws Exception
     */
    public function testParseShouldMatch(): void
    {
        $cursor    = $this->createMock(Cursor::class);
        $container = $this->createMock(AbstractBlock::class);

        $context = (new InlineParserContext(
            $cursor,
            $container,
            $this->createMock(ReferenceMapInterface::class)
        ))->withMatches(['foobar']);

        $this->dataProvider->expects(self::once())->method('convert')->with('foobar')->willReturn('emoji');
        $cursor->expects(self::once())->method('advanceBy')->with(strlen('foobar'));
        $container->expects(self::once())->method('appendChild')->with(new Text('emoji'));

        static::assertTrue($this->parser->parse($context));
    }
}
