<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Console\Exception;

/**
 * Used when a shell method cannot be found.
 */
class MissingShellMethodException extends ConsoleException
{
    /**
     * @var string
     */
    protected $_messageTemplate = "Unknown command %1\$s %2\$s.\nFor usage try `cake %1\$s --help`";
}
