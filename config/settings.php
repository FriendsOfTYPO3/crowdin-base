<?php

declare(strict_types=1);

use Dotenv\Exception\InvalidPathException;
use FriendsOfTYPO3\CrowdinBase\Settings\Settings;
use FriendsOfTYPO3\CrowdinBase\Settings\PathResolver;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(getcwd());
    $dotenv->load();
} catch (InvalidPathException) {
    die("\n==> .env file not found! Please copy the .env.example in your project root folder to .env and set the configuration accordingly.\n\n");
}

if (file_exists(__DIR__ . '/skippedProjects.php')) {
    $skippedProjects = require __DIR__ . '/skippedProjects.php';
} else {
    $skippedProjects = [];
}

return Settings::fromEnvironment($_ENV, $skippedProjects, new PathResolver());
