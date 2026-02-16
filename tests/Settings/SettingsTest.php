<?php

declare(strict_types=1);


namespace FriendsOfTYPO3\CrowdinBase\Tests\Settings;

use FriendsOfTYPO3\CrowdinBase\Settings\PathResolver;
use FriendsOfTYPO3\CrowdinBase\Settings\Settings;
use FriendsOfTYPO3\CrowdinBase\Settings\UndefinedEnvironmentVariableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(Settings::class)]
final class SettingsTest extends TestCase
{
    private Stub $pathResolverStub;

    protected function setUp(): void
    {
        $this->pathResolverStub = self::createStub(PathResolver::class);
        $this->pathResolverStub
            ->method('getProjectPath')
            ->willReturnMap([
                [
                    dirname(__DIR__, 2) . '/src/Settings',
                    '/tmp',
                ]
            ]);
    }

    #[Test]
    public function fromEnvironment(): void
    {
        $env = [
            'CONFIGURATION_FILE' => 'var/configuration.json',
            'CROWDIN_ACCESS_TOKEN' => 'some-very-secret-token',
        ];
        $skippedProjects = [
            'some-skipped-project',
        ];

        $actual = Settings::fromEnvironment($env, $skippedProjects, $this->pathResolverStub);

        self::assertStringEndsWith($env['CONFIGURATION_FILE'], $actual->configurationFile);
        self::assertSame($env['CROWDIN_ACCESS_TOKEN'], $actual->crowdinAccessToken);
        self::assertSame($skippedProjects, $actual->skippedProjects);
    }

    #[Test]
    public function fromEnvironmentThrowsExceptionIfConfigurationFileIsNotAvailableViaEnv(): void
    {
        $this->expectException(UndefinedEnvironmentVariableException::class);

        Settings::fromEnvironment(
            [
                'CROWDIN_ACCESS_TOKEN' => 'some-very-secret-token',
            ],
            [
                'some-skipped-project',
            ],
            $this->pathResolverStub
        );
    }

    #[Test]
    public function fromEnvironmentThrowsExceptionIfCrowdinAccessTokenIsNotAvailableViaEnv(): void
    {
        $this->expectException(UndefinedEnvironmentVariableException::class);

        Settings::fromEnvironment(
            [
                'CONFIGURATION_FILE' => 'var/configuration.json',
            ],
            [
                'some-skipped-project',
            ],
            $this->pathResolverStub
        );
    }
}
