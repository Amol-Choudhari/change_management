<?php
declare(strict_types=1);

/**
 * FileLogTest file
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
namespace Cake\Test\TestCase\Log\Engine;

use Cake\Log\Engine\FileLog;
use Cake\TestSuite\TestCase;

/**
 * FileLogTest class
 */
class FileLogTest extends TestCase
{
    /**
     * testLogFileWriting method
     *
     * @return void
     */
    public function testLogFileWriting()
    {
        $this->_deleteLogs(LOGS);

        $log = new FileLog(['path' => LOGS]);
        $log->log('warning', 'Test warning');
        $this->assertFileExists(LOGS . 'error.log');

        $result = file_get_contents(LOGS . 'error.log');
        $this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Test warning/', $result);

        $log->log('debug', 'Test warning');
        $this->assertFileExists(LOGS . 'debug.log');

        $result = file_get_contents(LOGS . 'debug.log');
        $this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Debug: Test warning/', $result);

        $log->log('random', 'Test warning');
        $this->assertFileExists(LOGS . 'random.log');

        $result = file_get_contents(LOGS . 'random.log');
        $this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Random: Test warning/', $result);
    }

    /**
     * test using the path setting to log logs in other places.
     *
     * @return void
     */
    public function testPathSetting()
    {
        $path = TMP . 'tests' . DS;
        $this->_deleteLogs($path);

        $log = new FileLog(compact('path'));
        $log->log('warning', 'Test warning');
        $this->assertFileExists($path . 'error.log');
    }

    /**
     * test log rotation
     *
     * @return void
     */
    public function testRotation()
    {
        $path = TMP . 'tests' . DS;
        $this->_deleteLogs($path);

        file_put_contents($path . 'error.log', "this text is under 35 bytes\n");
        $log = new FileLog([
            'path' => $path,
            'size' => 35,
            'rotate' => 2,
        ]);
        $log->log('warning', 'Test warning one');
        $this->assertFileExists($path . 'error.log');

        $result = file_get_contents($path . 'error.log');
        $this->assertMatchesRegularExpression('/Warning: Test warning one/', $result);
        $this->assertCount(0, glob($path . 'error.log.*'));

        clearstatcache();
        $log->log('warning', 'Test warning second');

        $files = glob($path . 'error.log.*');
        $this->assertCount(1, $files);

        $result = file_get_contents($files[0]);
        $this->assertMatchesRegularExpression('/this text is under 35 bytes/', $result);
        $this->assertMatchesRegularExpression('/Warning: Test warning one/', $result);

        sleep(1);
        clearstatcache();
        $log->log('warning', 'Test warning third');

        $result = file_get_contents($path . 'error.log');
        $this->assertMatchesRegularExpression('/Warning: Test warning third/', $result);

        $files = glob($path . 'error.log.*');
        $this->assertCount(2, $files);

        $result = file_get_contents($files[0]);
        $this->assertMatchesRegularExpression('/this text is under 35 bytes/', $result);

        $result = file_get_contents($files[1]);
        $this->assertMatchesRegularExpression('/Warning: Test warning second/', $result);

        file_put_contents($path . 'error.log.0000000000', "The oldest log file with over 35 bytes.\n");

        sleep(1);
        clearstatcache();
        $log->log('warning', 'Test warning fourth');

        // rotate count reached so file count should not increase
        $files = glob($path . 'error.log.*');
        $this->assertCount(2, $files);

        $result = file_get_contents($path . 'error.log');
        $this->assertMatchesRegularExpression('/Warning: Test warning fourth/', $result);

        $result = file_get_contents(array_pop($files));
        $this->assertMatchesRegularExpression('/Warning: Test warning third/', $result);

        $result = file_get_contents(array_pop($files));
        $this->assertMatchesRegularExpression('/Warning: Test warning second/', $result);

        file_put_contents($path . 'debug.log', "this text is just greater than 35 bytes\n");
        $log = new FileLog([
            'path' => $path,
            'size' => 35,
            'rotate' => 0,
        ]);
        file_put_contents($path . 'debug.log.0000000000', "The oldest log file with over 35 bytes.\n");
        $log->log('debug', 'Test debug');
        $this->assertFileExists($path . 'debug.log');

        $result = file_get_contents($path . 'debug.log');
        $this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Debug: Test debug/', $result);
        $this->assertFalse(strstr($result, 'greater than 5 bytes'));
        $this->assertCount(0, glob($path . 'debug.log.*'));
    }

    public function testMaskSetting()
    {
        if (DS === '\\') {
            $this->markTestSkipped('File permission testing does not work on Windows.');
        }

        $path = TMP . 'tests' . DS;
        $this->_deleteLogs($path);

        $log = new FileLog(['path' => $path, 'mask' => 0666]);
        $log->log('warning', 'Test warning one');
        $result = substr(sprintf('%o', fileperms($path . 'error.log')), -4);
        $expected = '0666';
        $this->assertSame($expected, $result);
        unlink($path . 'error.log');

        $log = new FileLog(['path' => $path, 'mask' => 0644]);
        $log->log('warning', 'Test warning two');
        $result = substr(sprintf('%o', fileperms($path . 'error.log')), -4);
        $expected = '0644';
        $this->assertSame($expected, $result);
        unlink($path . 'error.log');

        $log = new FileLog(['path' => $path, 'mask' => 0640]);
        $log->log('warning', 'Test warning three');
        $result = substr(sprintf('%o', fileperms($path . 'error.log')), -4);
        $expected = '0640';
        $this->assertSame($expected, $result);
        unlink($path . 'error.log');
    }

    /**
     * helper function to clears all log files in specified directory
     *
     * @param string $dir
     * @return void
     */
    protected function _deleteLogs($dir)
    {
        $files = array_merge(glob($dir . '*.log'), glob($dir . '*.log.*'));
        foreach ($files as $file) {
            unlink($file);
        }
    }

    /**
     * test dateFormat option
     *
     * @return void
     */
    public function testDateFormat()
    {
        $this->_deleteLogs(LOGS);

        // original 'Y-m-d H:i:s' format test was testLogFileWriting() method

        // 'c': ISO 8601 date (added in PHP 5)
        $log = new FileLog(['path' => LOGS, 'dateFormat' => 'c']);
        $log->log('warning', 'Test warning');

        $result = file_get_contents(LOGS . 'error.log');
        $this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+T[0-9]+:[0-9]+:[0-9]+\+\d{2}:\d{2} Warning: Test warning/', $result);
    }
}
