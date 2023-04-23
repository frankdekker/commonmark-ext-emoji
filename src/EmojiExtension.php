<?php
declare(strict_types=1);

namespace FD\CommonMarkEmoji;


use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class EmojiExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addInlineParser(new EmojiParser(), 20);
        //->addRenderer(Emoji::class, new EmojiRenderer());
    }
}

