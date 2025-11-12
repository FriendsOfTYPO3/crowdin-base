<?php

declare(strict_types=1);

namespace FriendsOfTYPO3\CrowdinBase\Settings;

readonly class PathResolver
{
    public function getProjectPath(string $path): string
    {
        if (is_file($path . '/.env')) {
            return $path;
        }

        if ($path === '/') {
            throw new \RuntimeException('.env file not found!', 1762948843);
        }

        return $this->getProjectPath(dirname($path));
    }
}
