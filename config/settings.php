<?php

declare(strict_types=1);

use Dotenv\Exception\InvalidPathException;
use FriendsOfTYPO3\CrowdinBase\Settings\EnvironmentVariables;
use FriendsOfTYPO3\CrowdinBase\Settings\Settings;
use FriendsOfTYPO3\CrowdinBase\Settings\PathResolver;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(getcwd());
    $dotenv->load();
} catch (InvalidPathException) {
    // Environment variables can also be defined in console, so there is no .env file necessary
}

foreach (EnvironmentVariables::cases() as $variable) {
    if (getenv($variable->name) !== false) {
        $_ENV[$variable->name] = getenv($variable->name);
    }
}

if (file_exists(__DIR__ . '/skippedProjects.php')) {
    $skippedProjects = require __DIR__ . '/skippedProjects.php';
} else {
    $skippedProjects = [];
}

return Settings::fromEnvironment($_ENV, $skippedProjects, new PathResolver());
