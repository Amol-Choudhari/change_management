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
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Test\TestCase\Database\Driver;

use Cake\Database\Query;
use Cake\TestSuite\TestCase;
use PDO;

/**
 * Tests Postgres driver
 */
class PostgresTest extends TestCase
{
    /**
     * Test connecting to Postgres with default configuration
     *
     * @return void
     */
    public function testConnectionConfigDefault()
    {
        $driver = $this->getMockBuilder('Cake\Database\Driver\Postgres')
            ->onlyMethods(['_connect', 'getConnection'])
            ->getMock();
        $dsn = 'pgsql:host=localhost;port=5432;dbname=cake';
        $expected = [
            'persistent' => true,
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'cake',
            'schema' => 'public',
            'port' => 5432,
            'encoding' => 'utf8',
            'timezone' => null,
            'flags' => [],
            'init' => [],
        ];

        $expected['flags'] += [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        $connection = $this->getMockBuilder('stdClass')
            ->addMethods(['exec', 'quote'])
            ->getMock();
        $connection->expects($this->any())
            ->method('quote')
            ->will($this->onConsecutiveCalls(
                $this->returnArgument(0),
                $this->returnArgument(0),
                $this->returnArgument(0)
            ));

        $connection->expects($this->at(1))->method('exec')->with('SET NAMES utf8');
        $connection->expects($this->at(3))->method('exec')->with('SET search_path TO public');
        $connection->expects($this->exactly(2))->method('exec');

        $driver->expects($this->once())->method('_connect')
            ->with($dsn, $expected);
        $driver->expects($this->any())->method('getConnection')
            ->will($this->returnValue($connection));

        $driver->connect();
    }

    /**
     * Test connecting to Postgres with custom configuration
     *
     * @return void
     */
    public function testConnectionConfigCustom()
    {
        $config = [
            'persistent' => false,
            'host' => 'foo',
            'database' => 'bar',
            'username' => 'user',
            'password' => 'pass',
            'port' => 3440,
            'flags' => [1 => true, 2 => false],
            'encoding' => 'a-language',
            'timezone' => 'Antarctica',
            'schema' => 'fooblic',
            'init' => ['Execute this', 'this too'],
        ];
        $driver = $this->getMockBuilder('Cake\Database\Driver\Postgres')
            ->onlyMethods(['_connect', 'getConnection', 'setConnection'])
            ->setConstructorArgs([$config])
            ->getMock();
        $dsn = 'pgsql:host=foo;port=3440;dbname=bar';

        $expected = $config;
        $expected['flags'] += [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        $connection = $this->getMockBuilder('stdClass')
            ->addMethods(['exec', 'quote'])
            ->getMock();
        $connection->expects($this->any())
            ->method('quote')
            ->will($this->onConsecutiveCalls(
                $this->returnArgument(0),
                $this->returnArgument(0),
                $this->returnArgument(0)
            ));

        $connection->expects($this->at(1))->method('exec')->with('SET NAMES a-language');
        $connection->expects($this->at(3))->method('exec')->with('SET search_path TO fooblic');
        $connection->expects($this->at(5))->method('exec')->with('Execute this');
        $connection->expects($this->at(6))->method('exec')->with('this too');
        $connection->expects($this->at(7))->method('exec')->with('SET timezone = Antarctica');
        $connection->expects($this->exactly(5))->method('exec');

        $driver->setConnection($connection);
        $driver->expects($this->once())->method('_connect')
            ->with($dsn, $expected);

        $driver->expects($this->any())->method('getConnection')
            ->will($this->returnValue($connection));

        $driver->connect();
    }

    /**
     * Tests that insert queries get a "RETURNING *" string at the end
     *
     * @return void
     */
    public function testInsertReturning()
    {
        $driver = $this->getMockBuilder('Cake\Database\Driver\Postgres')
            ->onlyMethods(['_connect', 'getConnection'])
            ->setConstructorArgs([[]])
            ->getMock();
        $connection = $this
            ->getMockBuilder('Cake\Database\Connection')
            ->onlyMethods(['connect'])
            ->disableOriginalConstructor()
            ->getMock();

        $query = new Query($connection);
        $query->insert(['id', 'title'])
            ->into('articles')
            ->values([1, 'foo']);
        $translator = $driver->queryTranslator('insert');
        $query = $translator($query);
        $this->assertSame('RETURNING *', $query->clause('epilog'));

        $query = new Query($connection);
        $query->insert(['id', 'title'])
            ->into('articles')
            ->values([1, 'foo'])
            ->epilog('FOO');
        $query = $translator($query);
        $this->assertSame('FOO', $query->clause('epilog'));
    }

    /**
     * Test that having queries replace the aggregated alias field.
     *
     * @return void
     */
    public function testHavingReplacesAlias()
    {
        $driver = $this->getMockBuilder('Cake\Database\Driver\Postgres')
            ->onlyMethods(['connect', 'getConnection', 'version'])
            ->setConstructorArgs([[]])
            ->getMock();

        $connection = $this->getMockBuilder('\Cake\Database\Connection')
            ->onlyMethods(['connect', 'getDriver', 'setDriver'])
            ->setConstructorArgs([['log' => false]])
            ->getMock();
        $connection->expects($this->any())
            ->method('getDriver')
            ->will($this->returnValue($driver));

        $query = new Query($connection);
        $query
            ->select([
                'posts.author_id',
                'post_count' => $query->func()->count('posts.id'),
            ])
            ->group(['posts.author_id'])
            ->having([$query->newExpr()->gte('post_count', 2, 'integer')]);

        $expected = 'SELECT posts.author_id, (COUNT(posts.id)) AS "post_count" ' .
            'GROUP BY posts.author_id HAVING COUNT(posts.id) >= :c0';
        $this->assertSame($expected, $query->sql());
    }

    /**
     * Test that having queries replaces nothing if no alias is used.
     *
     * @return void
     */
    public function testHavingWhenNoAliasIsUsed()
    {
        $driver = $this->getMockBuilder('Cake\Database\Driver\Postgres')
            ->onlyMethods(['connect', 'getConnection', 'version'])
            ->setConstructorArgs([[]])
            ->getMock();

        $connection = $this->getMockBuilder('\Cake\Database\Connection')
            ->onlyMethods(['connect', 'getDriver', 'setDriver'])
            ->setConstructorArgs([['log' => false]])
            ->getMock();
        $connection->expects($this->any())
            ->method('getDriver')
            ->will($this->returnValue($driver));

        $query = new Query($connection);
        $query
            ->select([
                'posts.author_id',
                'post_count' => $query->func()->count('posts.id'),
            ])
            ->group(['posts.author_id'])
            ->having([$query->newExpr()->gte('posts.author_id', 2, 'integer')]);

        $expected = 'SELECT posts.author_id, (COUNT(posts.id)) AS "post_count" ' .
            'GROUP BY posts.author_id HAVING posts.author_id >= :c0';
        $this->assertSame($expected, $query->sql());
    }
}
