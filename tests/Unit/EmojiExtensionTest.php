<?php

declare(strict_types=1);

namespace FD\CommonMarkEmoji\Tests\Unit;

use FD\CommonMarkEmoji\EmojiDataProviderInterface;
use FD\CommonMarkEmoji\EmojiExtension;
use FD\CommonMarkEmoji\EmojiParser;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmojiExtension::class)]
class EmojiExtensionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRegister(): void
    {
        $dataProvider = $this->createMock(EmojiDataProviderInterface::class);
        $extension    = new EmojiExtension($dataProvider);

        $environmentBuilder = $this->createMock(EnvironmentBuilderInterface::class);
        $environmentBuilder->expects(self::once())->method('addInlineParser')->with(new EmojiParser($dataProvider));

        $extension->register($environmentBuilder);
    }
}
