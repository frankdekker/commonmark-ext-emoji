<?php
declare(strict_types=1);

namespace FD\CommonMarkEmoji;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class EmojiExtension implements ExtensionInterface
{
    public const MODE_LIGHT = 'light';
    public const MODE_FULL  = 'full';

    /**
     * @param self::MODE_* $mode either light or full mode for supported emoticons. See /resources/ for the list of support emoticons.
     */
    public function __construct(private readonly string $mode = self::MODE_LIGHT)
    {
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser(new EmojiParser());
    }
}

