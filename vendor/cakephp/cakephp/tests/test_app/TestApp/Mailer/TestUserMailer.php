<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.3
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace TestApp\Mailer;

/**
 * Test Suite Test App Mailer class.
 */
class TestUserMailer extends TestMailer
{
    public function invite($email)
    {
        $this->_email
            ->setSubject('CakePHP')
            ->setFrom('jadb@cakephp.org')
            ->setTo($email)
            ->setCc('markstory@cakephp.org')
            ->addCc('admad@cakephp.org', 'Adnan')
            ->setBcc('dereuromark@cakephp.org', 'Mark')
            ->addBcc('antograssiot@cakephp.org')
            ->setAttachments([
                dirname(__FILE__) . DS . 'TestMailer.php',
                dirname(__FILE__) . DS . 'TestUserMailer.php',
            ])
            ->send('Hello ' . $email);
    }
}
