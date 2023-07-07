<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        // __DIR__ . '/docs',
        // __DIR__ . '/lib/Doctrine/DBAL',
        // __DIR__ . '/tests',

        __DIR__ . '/lib/Doctrine/DBAL/Cache',
        __DIR__ . '/lib/Doctrine/DBAL/Configuration.php',
        __DIR__ . '/lib/Doctrine/DBAL/Connection.php',
        __DIR__ . '/lib/Doctrine/DBAL/ConnectionException.php',
        // __DIR__ . '/lib/Doctrine/DBAL/Connections',
        __DIR__ . '/lib/Doctrine/DBAL/DBALException.php',
        // __DIR__ . '/lib/Doctrine/DBAL/Driver',
        __DIR__ . '/lib/Doctrine/DBAL/Driver.php',
        __DIR__ . '/lib/Doctrine/DBAL/DriverManager.php',
        __DIR__ . '/lib/Doctrine/DBAL/Event',
        __DIR__ . '/lib/Doctrine/DBAL/Events.php',
        __DIR__ . '/lib/Doctrine/DBAL/Id',
        __DIR__ . '/lib/Doctrine/DBAL/LockMode.php',
        __DIR__ . '/lib/Doctrine/DBAL/Logging',
        __DIR__ . '/lib/Doctrine/DBAL/Platforms',
        // __DIR__ . '/lib/Doctrine/DBAL/Portability',
        __DIR__ . '/lib/Doctrine/DBAL/Query',
        __DIR__ . '/lib/Doctrine/DBAL/README.markdown',
        __DIR__ . '/lib/Doctrine/DBAL/SQLParserUtils.php',
        __DIR__ . '/lib/Doctrine/DBAL/SQLParserUtilsException.php',
        __DIR__ . '/lib/Doctrine/DBAL/Schema',
        __DIR__ . '/lib/Doctrine/DBAL/Sharding',
        __DIR__ . '/lib/Doctrine/DBAL/Statement.php',
        __DIR__ . '/lib/Doctrine/DBAL/Tools',
        __DIR__ . '/lib/Doctrine/DBAL/Types',
        __DIR__ . '/lib/Doctrine/DBAL/Version.php',
        
    ]);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82
    ]);
};
