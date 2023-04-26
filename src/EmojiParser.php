<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class EmojiParser implements InlineParserInterface
{
    public function __construct(private readonly EmojiDataProviderInterface $emojiDataProvider)
    {
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex($this->emojiDataProvider->getSupportedEmojis());
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $match = $inlineContext->getFullMatch();
        if ($match === '') {
            return false;
        }

        // peek at the next following char, if it's an alphanum char and last char as well. skip replacement
        $nextChar = $inlineContext->getCursor()->peek(strlen($match));
        if ($nextChar !== null && preg_match('/^\w{2}$/', $nextChar . substr($match, -1)) === 1) {
            return false;
        }

        $emoji = $this->emojiDataProvider->convert($match);
        if ($emoji === null) {
            return false;
        }

        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $inlineContext->getContainer()->appendChild(new Text($emoji));

        return true;
    }
}
