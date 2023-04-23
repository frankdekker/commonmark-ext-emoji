<?php
declare(strict_types=1);

namespace FD\CommonMarkEmoji;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class EmojiParser implements InlineParserInterface
{
    /**
     * @param EmojiExtension::MODE_* $mode
     */
    public function __construct(private readonly string $mode = EmojiExtension::MODE_LIGHT)
    {
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::string(':)');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $inlineContext->getContainer()->appendChild(new Text('😀'));

        return true;
    }
}
