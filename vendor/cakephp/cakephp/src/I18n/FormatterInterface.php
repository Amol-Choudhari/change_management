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
 * @copyright     Copyright (c) 2017 Aura for PHP
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         4.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\I18n;

/**
 * Formatter Interface
 */
interface FormatterInterface
{
    /**
     * Returns a string with all passed variables interpolated into the original
     * message. Variables are interpolated using the sprintf format.
     *
     * @param string $locale The locale in which the message is presented.
     * @param string $message The message to be translated
     * @param array $tokenValues The list of values to interpolate in the message
     * @return string The formatted message
     */
    public function format(string $locale, string $message, array $tokenValues): string;
}
