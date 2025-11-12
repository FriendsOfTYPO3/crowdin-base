<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Settings;

use FriendsOfTYPO3\CrowdinBase\Settings\PathResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PathResolver::class)]
final class PathResolverTest extends TestCase
{
    private PathResolver $subject;

    protected function setUp(): void
    {
        $this->subject = new PathResolver();
    }

    #[Test]
    public function getProjectPathReturnsPathCorrectlyIfEnvFileIsFound(): void
    {
        $path = '/tmp/crowdin-base-test-' . uniqid() . '/path-resolver-test/subfolder';
        mkdir($path, recursive: true);

        $envPath = dirname(dirname($path)) . '/.env';
        touch($envPath);

        $actual = $this->subject->getProjectPath($path);
        $expected = dirname($envPath);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function getProjectPathThrowsExceptionIfEnvFileIsMissing(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(1762948843);

        $this->subject->getProjectPath('/tmp');
    }
}
