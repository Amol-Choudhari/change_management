<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Console;

use Cake\Console\Exception\MissingTaskException;
use Cake\Core\App;
use Cake\Core\ObjectRegistry;

/**
 * Registry for Tasks. Provides features
 * for lazily loading tasks.
 *
 * @extends \Cake\Core\ObjectRegistry<\Cake\Console\Shell>
 */
class TaskRegistry extends ObjectRegistry
{
    /**
     * Shell to use to set params to tasks.
     *
     * @var \Cake\Console\Shell
     */
    protected $_Shell;

    /**
     * Constructor
     *
     * @param \Cake\Console\Shell $shell Shell instance
     */
    public function __construct(Shell $shell)
    {
        $this->_Shell = $shell;
    }

    /**
     * Resolve a task classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class Partial classname to resolve.
     * @return string|null Either the correct class name or null.
     * @psalm-return class-string|null
     */
    protected function _resolveClassName(string $class): ?string
    {
        return App::className($class, 'Shell/Task', 'Task');
    }

    /**
     * Throws an exception when a task is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     * and Cake\Core\ObjectRegistry::unload()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the task is missing in.
     * @return void
     * @throws \Cake\Console\Exception\MissingTaskException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new MissingTaskException([
            'class' => $class,
            'plugin' => $plugin,
        ]);
    }

    /**
     * Create the task instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname to create.
     * @param string $alias The alias of the task.
     * @param array $config An array of settings to use for the task.
     * @return \Cake\Console\Shell The constructed task class.
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function _create($class, string $alias, array $config): Shell
    {
        // phpcs:ignore SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.InvalidFormat
        /** @var \Cake\Console\Shell */
        return new $class($this->_Shell->getIo());
    }
}
