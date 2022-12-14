<?php
declare(strict_types=1);

/**
 * TestPluginController used by Dispatcher test to test plugin shortcut URLs.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * TestPluginController
 */
namespace TestPlugin\Controller;

class TestPluginController extends TestPluginAppController
{
    public function index()
    {
        $this->autoRender = false;
    }

    public function add()
    {
        $this->autoRender = false;
    }
}
