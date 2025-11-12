<?php

declare(strict_types=1);

use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationReader;
use FriendsOfTYPO3\CrowdinBase\Configuration\ConfigurationWriter;
use FriendsOfTYPO3\CrowdinBase\Settings\Settings;
use CrowdinApiClient\Crowdin;

/** @var Settings $settings */
$settings = require 'settings.php';

return [
    ConfigurationReader::class => DI\autowire()
        ->constructorParameter('configurationFile', $settings->configurationFile),

    ConfigurationWriter::class => DI\autowire()
        ->constructorParameter('configurationFile', $settings->configurationFile)
        ->constructorParameter('skippedProjects', $settings->skippedProjects),

    Crowdin::class => DI\autowire()
        ->constructorParameter('config', ['access_token' => $settings->crowdinAccessToken]),
];
