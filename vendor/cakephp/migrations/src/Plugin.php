<?php
declare(strict_types=1);

/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Migrations;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;

/**
 * Plugin class for migrations
 */
class Plugin extends BasePlugin
{
    /**
     * Plugin name.
     *
     * @var string
     */
    protected $name = 'Migrations';

    /**
     * Don't try to load routes.
     *
     * @var bool
     */
    protected $routesEnabled = false;

    /**
     * @var array
     */
    protected $migrationCommandsList = [
        Command\MigrationsCommand::class,
        Command\MigrationsCreateCommand::class,
        Command\MigrationsDumpCommand::class,
        Command\MigrationsMarkMigratedCommand::class,
        Command\MigrationsMigrateCommand::class,
        Command\MigrationsCacheBuildCommand::class,
        Command\MigrationsCacheClearCommand::class,
        Command\MigrationsRollbackCommand::class,
        Command\MigrationsSeedCommand::class,
        Command\MigrationsStatusCommand::class,
    ];

    /**
     * Add migrations commands.
     *
     * @param \Cake\Console\CommandCollection $collection The command collection to update
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $collection): CommandCollection
    {
        if (class_exists('Bake\Command\SimpleBakeCommand')) {
            $commands = $collection->discoverPlugin($this->getName());

            return $collection->addMany($commands);
        }
        $commands = [];
        foreach ($this->migrationCommandsList as $class) {
            $name = $class::defaultName();
            // If the short name has been used, use the full name.
            // This allows app commands to have name preference.
            // and app commands to overwrite migration commands.
            if (!$collection->has($name)) {
                $commands[$name] = $class;
            }
            // full name
            $commands['migrations.' . $name] = $class;
        }

        return $collection->addMany($commands);
    }
}
