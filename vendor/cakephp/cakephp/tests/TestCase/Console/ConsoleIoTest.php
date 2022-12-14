<?php
declare(strict_types=1);

/**
 * CakePHP :  Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Test\TestCase\Console;

use Cake\Console\ConsoleIo;
use Cake\Console\Exception\StopException;
use Cake\Filesystem\Filesystem;
use Cake\Log\Log;
use Cake\TestSuite\TestCase;

/**
 * ConsoleIo test.
 */
class ConsoleIoTest extends TestCase
{
    /**
     * @var \Cake\Console\ConsoleIo
     */
    protected $io;

    /**
     * @var \Cake\Console\ConsoleOutput|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $out;

    /**
     * @var \Cake\Console\ConsoleOutput|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $err;

    /**
     * @var \Cake\Console\ConsoleInput|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $in;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        static::setAppNamespace();

        $this->out = $this->getMockBuilder('Cake\Console\ConsoleOutput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->err = $this->getMockBuilder('Cake\Console\ConsoleOutput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->in = $this->getMockBuilder('Cake\Console\ConsoleInput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->io = new ConsoleIo($this->out, $this->err, $this->in);
    }

    /**
     * teardown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        if (is_dir(TMP . 'shell_test')) {
            $fs = new Filesystem();
            $fs->deleteDir(TMP . 'shell_test');
        }
    }

    /**
     * Provider for testing choice types.
     *
     * @return array
     */
    public function choiceProvider()
    {
        return [
            [['y', 'n']],
            ['y,n'],
            ['y/n'],
            ['y'],
        ];
    }

    /**
     * test ask choices method
     *
     * @dataProvider choiceProvider
     * @return void
     */
    public function testAskChoices($choices)
    {
        $this->in->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue('y'));

        $result = $this->io->askChoice('Just a test?', $choices);
        $this->assertSame('y', $result);
    }

    /**
     * test ask choices method
     *
     * @dataProvider choiceProvider
     * @return void
     */
    public function testAskChoicesInsensitive($choices)
    {
        $this->in->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue('Y'));

        $result = $this->io->askChoice('Just a test?', $choices);
        $this->assertSame('Y', $result);
    }

    /**
     * Test ask method
     *
     * @return void
     */
    public function testAsk()
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with("<question>Just a test?</question>\n> ");

        $this->in->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue('y'));

        $result = $this->io->ask('Just a test?');
        $this->assertSame('y', $result);
    }

    /**
     * Test ask method
     *
     * @return void
     */
    public function testAskDefaultValue()
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with("<question>Just a test?</question>\n[n] > ");

        $this->in->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue(''));

        $result = $this->io->ask('Just a test?', 'n');
        $this->assertSame('n', $result);
    }

    /**
     * testOut method
     *
     * @return void
     */
    public function testOut()
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with('Just a test', 1);

        $this->out->expects($this->at(1))
            ->method('write')
            ->with(['Just', 'a', 'test'], 1);

        $this->out->expects($this->at(2))
            ->method('write')
            ->with(['Just', 'a', 'test'], 2);

        $this->out->expects($this->at(3))
            ->method('write')
            ->with('', 1);

        $this->io->out('Just a test');
        $this->io->out(['Just', 'a', 'test']);
        $this->io->out(['Just', 'a', 'test'], 2);
        $this->io->out();
    }

    /**
     * test that verbose and quiet output levels work
     *
     * @return void
     */
    public function testVerboseOut()
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with('Verbose', 1);
        $this->out->expects($this->at(1))
            ->method('write')
            ->with('Normal', 1);
        $this->out->expects($this->at(2))
            ->method('write')
            ->with('Quiet', 1);

        $this->io->level(ConsoleIo::VERBOSE);

        $this->io->out('Verbose', 1, ConsoleIo::VERBOSE);
        $this->io->out('Normal', 1, ConsoleIo::NORMAL);
        $this->io->out('Quiet', 1, ConsoleIo::QUIET);
    }

    /**
     * test that verbose and quiet output levels work
     *
     * @return void
     */
    public function testVerboseOutput()
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with('Verbose', 1);
        $this->out->expects($this->at(1))
            ->method('write')
            ->with('Normal', 1);
        $this->out->expects($this->at(2))
            ->method('write')
            ->with('Quiet', 1);

        $this->io->level(ConsoleIo::VERBOSE);

        $this->io->verbose('Verbose');
        $this->io->out('Normal');
        $this->io->quiet('Quiet');
    }

    /**
     * test that verbose and quiet output levels work
     *
     * @return void
     */
    public function testQuietOutput()
    {
        $this->out->expects($this->exactly(2))
            ->method('write')
            ->with('Quiet', 1);

        $this->io->level(ConsoleIo::QUIET);

        $this->io->out('Verbose', 1, ConsoleIo::VERBOSE);
        $this->io->out('Normal', 1, ConsoleIo::NORMAL);
        $this->io->out('Quiet', 1, ConsoleIo::QUIET);
        $this->io->verbose('Verbose');
        $this->io->quiet('Quiet');
    }

    /**
     * testErr method
     *
     * @return void
     */
    public function testErr()
    {
        $this->err->expects($this->at(0))
            ->method('write')
            ->with('Just a test', 1);

        $this->err->expects($this->at(1))
            ->method('write')
            ->with(['Just', 'a', 'test'], 1);

        $this->err->expects($this->at(2))
            ->method('write')
            ->with(['Just', 'a', 'test'], 2);

        $this->err->expects($this->at(3))
            ->method('write')
            ->with('', 1);

        $this->io->err('Just a test');
        $this->io->err(['Just', 'a', 'test']);
        $this->io->err(['Just', 'a', 'test'], 2);
        $this->io->err();
    }

    /**
     * Tests abort() wrapper.
     *
     * @return void
     */
    public function testAbort()
    {
        $this->expectException(StopException::class);
        $this->expectExceptionMessage('Some error');
        $this->expectExceptionCode(1);

        $this->err->expects($this->at(0))
            ->method('write')
            ->with('<error>Some error</error>', 1);

        $this->expectException(StopException::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('Some error');

        $this->io->abort('Some error');
    }

    /**
     * Tests abort() wrapper.
     *
     * @return void
     */
    public function testAbortCustomCode()
    {
        $this->expectException(StopException::class);
        $this->expectExceptionMessage('Some error');
        $this->expectExceptionCode(99);

        $this->err->expects($this->at(0))
            ->method('write')
            ->with('<error>Some error</error>', 1);

        $this->expectException(StopException::class);
        $this->expectExceptionCode(99);
        $this->expectExceptionMessage('Some error');

        $this->io->abort('Some error', 99);
    }

    /**
     * testNl
     *
     * @return void
     */
    public function testNl()
    {
        $newLine = "\n";
        if (DS === '\\') {
            $newLine = "\r\n";
        }
        $this->assertSame($this->io->nl(), $newLine);
        $this->assertSame($this->io->nl(2), $newLine . $newLine);
        $this->assertSame($this->io->nl(1), $newLine);
    }

    /**
     * testHr
     *
     * @return void
     */
    public function testHr()
    {
        $bar = str_repeat('-', 79);

        $this->out->expects($this->at(0))->method('write')->with('', 0);
        $this->out->expects($this->at(1))->method('write')->with($bar, 1);
        $this->out->expects($this->at(2))->method('write')->with('', 0);

        $this->out->expects($this->at(3))->method('write')->with('', true);
        $this->out->expects($this->at(4))->method('write')->with($bar, 1);
        $this->out->expects($this->at(5))->method('write')->with('', true);

        $this->io->hr();
        $this->io->hr(2);
    }

    /**
     * Test overwriting.
     *
     * @return void
     */
    public function testOverwrite()
    {
        $number = strlen('Some text I want to overwrite');

        $this->out->expects($this->at(0))
            ->method('write')
            ->with('Some <info>text</info> I want to overwrite', 0)
            ->will($this->returnValue($number));

        $this->out->expects($this->at(1))
            ->method('write')
            ->with(str_repeat("\x08", $number), 0);

        $this->out->expects($this->at(2))
            ->method('write')
            ->with('Less text', 0)
            ->will($this->returnValue(9));

        $this->out->expects($this->at(3))
            ->method('write')
            ->with(str_repeat(' ', $number - 9), 0);

        $this->io->out('Some <info>text</info> I want to overwrite', 0);
        $this->io->overwrite('Less text');
    }

    /**
     * Test overwriting content with shorter content
     *
     * @return void
     */
    public function testOverwriteWithShorterContent()
    {
        $length = strlen('12345');

        $this->out->expects($this->at(0))
            ->method('write')
            ->with('12345')
            ->will($this->returnValue($length));

        // Backspaces
        $this->out->expects($this->at(1))
            ->method('write')
            ->with(str_repeat("\x08", $length), 0)
            ->will($this->returnValue($length));

        $this->out->expects($this->at(2))
            ->method('write')
            ->with('123', 0)
            ->will($this->returnValue(3));

        // 2 spaces output to pad up to 5 bytes
        $this->out->expects($this->at(3))
            ->method('write')
            ->with(str_repeat(' ', $length - 3), 0)
            ->will($this->returnValue($length - 3));

        // Backspaces
        $this->out->expects($this->at(4))
            ->method('write')
            ->with(str_repeat("\x08", $length), 0)
            ->will($this->returnValue($length));

        $this->out->expects($this->at(5))
            ->method('write')
            ->with('12', 0)
            ->will($this->returnValue(2));

        $this->out->expects($this->at(6))
            ->method('write')
            ->with(str_repeat(' ', $length - 2), 0);

        $this->io->out('12345');
        $this->io->overwrite('123', 0);
        $this->io->overwrite('12', 0);
    }

    /**
     * Test overwriting content with longer content
     *
     * @return void
     */
    public function testOverwriteWithLongerContent()
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with('1')
            ->will($this->returnValue(1));

        // Backspaces
        $this->out->expects($this->at(1))
            ->method('write')
            ->with(str_repeat("\x08", 1), 0)
            ->will($this->returnValue(1));

        $this->out->expects($this->at(2))
            ->method('write')
            ->with('123', 0)
            ->will($this->returnValue(3));

        // Backspaces
        $this->out->expects($this->at(3))
            ->method('write')
            ->with(str_repeat("\x08", 3), 0)
            ->will($this->returnValue(3));

        $this->out->expects($this->at(4))
            ->method('write')
            ->with('12345', 0)
            ->will($this->returnValue(5));

        $this->io->out('1');
        $this->io->overwrite('123', 0);
        $this->io->overwrite('12345', 0);
    }

    /**
     * Tests that setLoggers works properly
     *
     * @return void
     */
    public function testSetLoggers()
    {
        Log::drop('stdout');
        Log::drop('stderr');
        $this->io->setLoggers(true);
        $this->assertNotEmpty(Log::engine('stdout'));
        $this->assertNotEmpty(Log::engine('stderr'));

        $this->io->setLoggers(false);
        $this->assertNull(Log::engine('stdout'));
        $this->assertNull(Log::engine('stderr'));
    }

    /**
     * Tests that setLoggers works properly with quiet
     *
     * @return void
     */
    public function testSetLoggersQuiet()
    {
        Log::drop('stdout');
        Log::drop('stderr');
        $this->io->setLoggers(ConsoleIo::QUIET);
        $this->assertEmpty(Log::engine('stdout'));
        $this->assertNotEmpty(Log::engine('stderr'));
    }

    /**
     * Tests that setLoggers works properly with verbose
     *
     * @return void
     */
    public function testSetLoggersVerbose()
    {
        Log::drop('stdout');
        Log::drop('stderr');
        $this->io->setLoggers(ConsoleIo::VERBOSE);

        $this->assertNotEmpty(Log::engine('stderr'));
        $engine = Log::engine('stdout');
        $this->assertEquals(['notice', 'info', 'debug'], $engine->getConfig('levels'));
    }

    /**
     * Ensure that setStyle() just proxies to stdout.
     *
     * @return void
     */
    public function testSetStyle()
    {
        $this->out->expects($this->once())
            ->method('setStyle')
            ->with('name', ['props']);
        $this->io->setStyle('name', ['props']);
    }

    /**
     * Ensure that getStyle() just proxies to stdout.
     *
     * @return void
     */
    public function testGetStyle()
    {
        $this->out->expects($this->once())
            ->method('getStyle')
            ->with('name');
        $this->io->getStyle('name');
    }

    /**
     * Ensure that styles() just proxies to stdout.
     *
     * @return void
     */
    public function testStyles()
    {
        $this->out->expects($this->once())
            ->method('styles');
        $this->io->styles();
    }

    /**
     * Test the helper method.
     *
     * @return void
     */
    public function testHelper()
    {
        $this->out->expects($this->once())
            ->method('write')
            ->with('It works!well ish');
        $helper = $this->io->helper('simple');
        $this->assertInstanceOf('Cake\Console\Helper', $helper);
        $helper->output(['well', 'ish']);
    }

    /**
     * Provider for output helpers
     *
     * @return array
     */
    public function outHelperProvider()
    {
        return [['info'], ['success'], ['comment']];
    }

    /**
     * Provider for err helpers
     *
     * @return array
     */
    public function errHelperProvider()
    {
        return [['warning'], ['error']];
    }

    /**
     * test out helper methods
     *
     * @dataProvider outHelperProvider
     * @return void
     */
    public function testOutHelpers($method)
    {
        $this->out->expects($this->at(0))
            ->method('write')
            ->with("<{$method}>Just a test</{$method}>", 1);

        $this->out->expects($this->at(1))
            ->method('write')
            ->with(["<{$method}>Just</{$method}>", "<{$method}>a test</{$method}>"], 1);

        $this->io->{$method}('Just a test');
        $this->io->{$method}(['Just', 'a test']);
    }

    /**
     * test err helper methods
     *
     * @dataProvider errHelperProvider
     * @return void
     */
    public function testErrHelpers($method)
    {
        $this->err->expects($this->at(0))
            ->method('write')
            ->with("<{$method}>Just a test</{$method}>", 1);

        $this->err->expects($this->at(1))
            ->method('write')
            ->with(["<{$method}>Just</{$method}>", "<{$method}>a test</{$method}>"], 1);

        $this->io->{$method}('Just a test');
        $this->io->{$method}(['Just', 'a test']);
    }

    /**
     * Test that createFile
     *
     * @return void
     */
    public function testCreateFileSuccess()
    {
        $this->err->expects($this->never())
            ->method('write');
        $path = TMP . 'shell_test';
        mkdir($path);

        $file = $path . DS . 'file1.php';
        $contents = 'some content';
        $result = $this->io->createFile($file, $contents);

        $this->assertTrue($result);
        $this->assertFileExists($file);
        $this->assertStringEqualsFile($file, $contents);
    }

    public function testCreateFileEmptySuccess()
    {
        $this->err->expects($this->never())
            ->method('write');
        $path = TMP . 'shell_test';
        mkdir($path);

        $file = $path . DS . 'file_empty.php';
        $contents = '';
        $result = $this->io->createFile($file, $contents);

        $this->assertTrue($result);
        $this->assertFileExists($file);
        $this->assertStringEqualsFile($file, $contents);
    }

    public function testCreateFileDirectoryCreation()
    {
        $this->err->expects($this->never())
            ->method('write');

        $directory = TMP . 'shell_test';
        $this->assertFileDoesNotExist($directory, 'Directory should not exist before createFile');

        $path = $directory . DS . 'create.txt';
        $contents = 'some content';
        $result = $this->io->createFile($path, $contents);

        $this->assertTrue($result, 'File should create');
        $this->assertFileExists($path);
        $this->assertStringEqualsFile($path, $contents);
    }

    /**
     * Test that createFile with permissions error.
     *
     * @return void
     */
    public function testCreateFilePermissionsError()
    {
        $this->skipIf(DS === '\\', 'Cant perform operations using permissions on windows.');

        $path = TMP . 'shell_test';
        $file = $path . DS . 'no_perms';

        if (!is_dir($path)) {
            mkdir($path);
        }
        chmod($path, 0444);

        $this->io->createFile($file, 'testing');
        $this->assertFileDoesNotExist($file);

        chmod($path, 0744);
        rmdir($path);
    }

    /**
     * Test that `q` raises an error.
     *
     * @return void
     */
    public function testCreateFileOverwriteQuit()
    {
        $path = TMP . 'shell_test';
        mkdir($path);

        $file = $path . DS . 'file1.php';
        touch($file);

        $this->expectException(StopException::class);

        $this->in->expects($this->once())
            ->method('read')
            ->will($this->returnValue('q'));

        $this->io->createFile($file, 'some content');
    }

    /**
     * Test that `n` raises an error.
     *
     * @return void
     */
    public function testCreateFileOverwriteNo()
    {
        $path = TMP . 'shell_test';
        mkdir($path);

        $file = $path . DS . 'file1.php';
        file_put_contents($file, 'original');
        touch($file);

        $this->in->expects($this->once())
            ->method('read')
            ->will($this->returnValue('n'));

        $contents = 'new content';
        $result = $this->io->createFile($file, $contents);

        $this->assertFalse($result);
        $this->assertFileExists($file);
        $this->assertStringEqualsFile($file, 'original');
    }

    /**
     * Test the forceOverwrite parameter
     *
     * @return void
     */
    public function testCreateFileOverwriteParam()
    {
        $path = TMP . 'shell_test';
        mkdir($path);

        $file = $path . DS . 'file1.php';
        file_put_contents($file, 'original');
        touch($file);

        $contents = 'new content';
        $result = $this->io->createFile($file, $contents, true);

        $this->assertTrue($result);
        $this->assertFileExists($file);
        $this->assertStringEqualsFile($file, $contents);
    }

    /**
     * Test the `a` response
     *
     * @return void
     */
    public function testCreateFileOverwriteAll()
    {
        $path = TMP . 'shell_test';
        mkdir($path);

        $file = $path . DS . 'file1.php';
        file_put_contents($file, 'original');
        touch($file);

        $this->in->expects($this->once())
            ->method('read')
            ->will($this->returnValue('a'));

        $this->io->createFile($file, 'new content');
        $this->assertStringEqualsFile($file, 'new content');

        $this->io->createFile($file, 'newer content');
        $this->assertStringEqualsFile($file, 'newer content');

        $this->io->createFile($file, 'newest content', false);
        $this->assertStringEqualsFile(
            $file,
            'newest content',
            'overwrite state replaces parameter'
        );
    }
}
