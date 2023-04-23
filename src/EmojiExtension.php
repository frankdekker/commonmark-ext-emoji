<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class EmojiExtension implements ExtensionInterface
{
    private EmojiDataProviderInterface $emojiDataProvider;

    public function __construct(?EmojiDataProviderInterface $emojiDataProvider = null)
    {
        $this->emojiDataProvider = $emojiDataProvider ?? EmojiDataProvider::light();
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser(new EmojiParser($this->emojiDataProvider));
    }
}

