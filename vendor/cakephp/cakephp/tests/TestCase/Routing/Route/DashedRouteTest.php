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
namespace Cake\Test\TestCase\Routing\Route;

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

/**
 * Test case for DashedRoute
 */
class DashedRouteTest extends TestCase
{
    /**
     * test that routes match their pattern.
     *
     * @return void
     */
    public function testMatchBasic()
    {
        $route = new DashedRoute('/:controller/:action/:id', ['plugin' => null]);
        $result = $route->match(['controller' => 'Posts', 'action' => 'myView', 'plugin' => null]);
        $this->assertNull($result);

        $result = $route->match([
            'plugin' => null,
            'controller' => 'Posts',
            'action' => 'myView',
            0,
        ]);
        $this->assertNull($result);

        $result = $route->match([
            'plugin' => null,
            'controller' => 'MyPosts',
            'action' => 'myView',
            'id' => 1,
        ]);
        $this->assertSame('/my-posts/my-view/1', $result);

        $route = new DashedRoute('/', ['controller' => 'Pages', 'action' => 'myDisplay', 'home']);
        $result = $route->match(['controller' => 'Pages', 'action' => 'myDisplay', 'home']);
        $this->assertSame('/', $result);

        $result = $route->match(['controller' => 'Pages', 'action' => 'display', 'about']);
        $this->assertNull($result);

        $route = new DashedRoute('/blog/:action', ['controller' => 'Posts']);
        $result = $route->match(['controller' => 'Posts', 'action' => 'myView']);
        $this->assertSame('/blog/my-view', $result);

        $result = $route->match(['controller' => 'Posts', 'action' => 'myView', '?' => ['id' => 2]]);
        $this->assertSame('/blog/my-view?id=2', $result);

        $result = $route->match(['controller' => 'Posts', 'action' => 'myView', 1]);
        $this->assertNull($result);

        $route = new DashedRoute('/foo/:controller/:action', ['action' => 'index']);
        $result = $route->match(['controller' => 'Posts', 'action' => 'myView']);
        $this->assertSame('/foo/posts/my-view', $result);

        $route = new DashedRoute('/:plugin/:id/*', ['controller' => 'Posts', 'action' => 'myView']);
        $result = $route->match([
            'plugin' => 'TestPlugin',
            'controller' => 'Posts',
            'action' => 'myView',
            'id' => '1',
        ]);
        $this->assertSame('/test-plugin/1/', $result);

        $result = $route->match([
            'plugin' => 'TestPlugin',
            'controller' => 'Posts',
            'action' => 'myView',
            'id' => '1',
            '0',
        ]);
        $this->assertSame('/test-plugin/1/0', $result);

        $result = $route->match([
            'plugin' => 'TestPlugin',
            'controller' => 'Nodes',
            'action' => 'myView',
            'id' => 1,
        ]);
        $this->assertNull($result);

        $result = $route->match([
            'plugin' => 'TestPlugin',
            'controller' => 'Posts',
            'action' => 'edit',
            'id' => 1,
        ]);
        $this->assertNull($result);

        $route = new DashedRoute('/admin/subscriptions/:action/*', [
            'controller' => 'Subscribe', 'prefix' => 'admin',
        ]);
        $result = $route->match([
            'controller' => 'Subscribe',
            'prefix' => 'admin',
            'action' => 'editAdminE',
            1,
        ]);
        $expected = '/admin/subscriptions/edit-admin-e/1';
        $this->assertSame($expected, $result);

        $route = new DashedRoute('/:controller/:action-:id');
        $result = $route->match([
            'controller' => 'MyPosts',
            'action' => 'myView',
            'id' => 1,
        ]);
        $this->assertSame('/my-posts/my-view-1', $result);

        $route = new DashedRoute('/:controller/:action/:slug-:id', [], ['id' => Router::ID]);
        $result = $route->match([
            'controller' => 'MyPosts',
            'action' => 'myView',
            'id' => '1',
            'slug' => 'the-slug',
        ]);
        $this->assertSame('/my-posts/my-view/the-slug-1', $result);
    }

    /**
     * test the parse method of DashedRoute.
     *
     * @return void
     */
    public function testParse()
    {
        $route = new DashedRoute('/:controller/:action/:id', [], ['id' => Router::ID]);
        $route->compile();
        $result = $route->parse('/my-posts/my-view/1', 'GET');
        $this->assertSame('MyPosts', $result['controller']);
        $this->assertSame('myView', $result['action']);
        $this->assertSame('1', $result['id']);

        $route = new DashedRoute('/:controller/:action-:id');
        $route->compile();
        $result = $route->parse('/my-posts/my-view-1', 'GET');
        $this->assertSame('MyPosts', $result['controller']);
        $this->assertSame('myView', $result['action']);
        $this->assertSame('1', $result['id']);

        $route = new DashedRoute('/:controller/:action/:slug-:id', [], ['id' => Router::ID]);
        $route->compile();
        $result = $route->parse('/my-posts/my-view/the-slug-1', 'GET');
        $this->assertSame('MyPosts', $result['controller']);
        $this->assertSame('myView', $result['action']);
        $this->assertSame('1', $result['id']);
        $this->assertSame('the-slug', $result['slug']);

        $route = new DashedRoute(
            '/admin/:controller',
            ['prefix' => 'admin', 'action' => 'index']
        );
        $route->compile();
        $result = $route->parse('/admin/', 'GET');
        $this->assertNull($result);

        $result = $route->parse('/admin/my-posts', 'GET');
        $this->assertSame('MyPosts', $result['controller']);
        $this->assertSame('index', $result['action']);

        $route = new DashedRoute(
            '/media/search/*',
            ['controller' => 'Media', 'action' => 'searchIt']
        );
        $result = $route->parse('/media/search', 'GET');
        $this->assertSame('Media', $result['controller']);
        $this->assertSame('searchIt', $result['action']);
        $this->assertEquals([], $result['pass']);

        $result = $route->parse('/media/search/tv_shows', 'GET');
        $this->assertSame('Media', $result['controller']);
        $this->assertSame('searchIt', $result['action']);
        $this->assertEquals(['tv_shows'], $result['pass']);
    }

    /**
     * @return void
     */
    public function testMatchThenParse()
    {
        $route = new DashedRoute('/plugin/:controller/:action', [
            'plugin' => 'Vendor/PluginName',
        ]);
        $url = $route->match([
            'plugin' => 'Vendor/PluginName',
            'controller' => 'ControllerName',
            'action' => 'actionName',
        ]);
        $expectedUrl = '/plugin/controller-name/action-name';
        $this->assertSame($expectedUrl, $url);
        $result = $route->parse($expectedUrl, 'GET');
        $this->assertSame('ControllerName', $result['controller']);
        $this->assertSame('actionName', $result['action']);
        $this->assertSame('Vendor/PluginName', $result['plugin']);
    }
}
