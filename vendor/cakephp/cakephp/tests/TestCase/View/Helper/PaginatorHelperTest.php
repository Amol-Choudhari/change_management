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
 * @since         1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Cake\View\Helper\PaginatorHelper;
use Cake\View\View;

/**
 * PaginatorHelperTest class
 */
class PaginatorHelperTest extends TestCase
{
    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \Cake\View\View
     */
    protected $View;

    /**
     * @var \Cake\View\Helper\PaginatorHelper
     */
    protected $Paginator;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('Config.language', 'eng');
        $request = new ServerRequest([
            'url' => '/',
            'params' => [
                'plugin' => null,
                'controller' => 'Articles',
                'action' => 'index',
            ],
        ]);
        $request = $request->withAttribute('paging', [
            'Article' => [
                'page' => 1,
                'current' => 9,
                'count' => 62,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 7,
                'sort' => null,
                'direction' => null,
                'limit' => null,
            ],
        ]);
        $this->View = new View($request);
        $this->Paginator = new PaginatorHelper($this->View);

        Router::reload();
        Router::connect('/', ['controller' => 'Articles', 'action' => 'index']);
        Router::connect('/:controller/:action/*');
        Router::connect('/:plugin/:controller/:action/*');
        Router::setRequest($request);

        $this->locale = I18n::getLocale();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->View, $this->Paginator);

        I18n::setLocale($this->locale);
    }

    /**
     * Test the templates method.
     *
     * @return void
     */
    public function testTemplates()
    {
        $result = $this->Paginator->setTemplates([
            'test' => 'val',
        ]);
        $this->assertSame(
            $this->Paginator,
            $result,
            'Setting should return the same object'
        );

        $result = $this->Paginator->getTemplates();
        $this->assertArrayHasKey('test', $result);
        $this->assertSame('val', $result['test']);

        $this->assertSame('val', $this->Paginator->getTemplates('test'));
    }

    /**
     * testHasPrevious method
     *
     * @return void
     */
    public function testHasPrevious()
    {
        $this->assertFalse($this->Paginator->hasPrev());
        $this->setPagingParams(['Article' => [
            'prevPage' => true,
        ]]);
        $this->assertTrue($this->Paginator->hasPrev());
    }

    /**
     * testHasNext method
     *
     * @return void
     */
    public function testHasNext()
    {
        $this->assertTrue($this->Paginator->hasNext());
        $this->setPagingParams(['Article' => [
            'nextPage' => false,
        ]]);
        $this->assertFalse($this->Paginator->hasNext());
    }

    /**
     * testSortLinks method
     *
     * @return void
     */
    public function testSortLinks()
    {
        $request = new ServerRequest([
            'url' => '/accounts/',
            'params' => [
                'plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->Paginator->options(['url' => ['param']]);
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'current' => 9,
                'count' => 62,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 7,
                'sort' => 'date',
                'direction' => 'asc',
                'page' => 1,
            ],
        ]));

        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', null, ['model' => 'Nope']);
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', null, ['model' => 'Article']);
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('date');
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=date&amp;direction=desc', 'class' => 'asc'],
            'Date',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', 'TestTitle');
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=asc'],
            'TestTitle',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', ['asc' => 'ascending', 'desc' => 'descending']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=asc'],
            'ascending',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'title',
            ],
        ]);
        $result = $this->Paginator->sort('title', ['asc' => 'ascending', 'desc' => 'descending']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=desc', 'class' => 'asc'],
            'descending',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=asc', 'class' => 'desc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=desc', 'class' => 'asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sort('title', 'Title', ['direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=asc', 'class' => 'desc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sort('title', 'Title', ['direction' => 'ASC']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=asc', 'class' => 'desc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sort('title', 'Title', ['direction' => 'asc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=desc', 'class' => 'asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sort('title', 'Title', ['direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=desc', 'class' => 'asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test sort() with escape option
     */
    public function testSortEscape()
    {
        $result = $this->Paginator->sort('title', 'TestTitle >');
        $expected = [
            'a' => ['href' => '/?sort=title&amp;direction=asc'],
            'TestTitle &gt;',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', 'TestTitle >', ['escape' => true]);
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', 'TestTitle >', ['escape' => false]);
        $expected = [
            'a' => ['href' => '/?sort=title&amp;direction=asc'],
            'TestTitle >',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test that sort() works with virtual field order options.
     *
     * @return void
     */
    public function testSortLinkWithVirtualField()
    {
        $request = new ServerRequest([
            'url' => '/accounts/',
            'params' => [
                'plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'full_name',
                'direction' => 'asc',
            ],
        ]);

        $result = $this->Paginator->sort('Article.full_name');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=Article.full_name&amp;direction=desc', 'class' => 'asc'],
            'Article Full Name',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('full_name');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=full_name&amp;direction=desc', 'class' => 'asc'],
            'Full Name',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'full_name',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sort('Article.full_name');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=Article.full_name&amp;direction=asc', 'class' => 'desc'],
            'Article Full Name',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('full_name');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=full_name&amp;direction=asc', 'class' => 'desc'],
            'Full Name',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testSortLinksUsingDirectionOption method
     *
     * @return void
     */
    public function testSortLinksUsingDirectionOption()
    {
        $request = new ServerRequest([
            'url' => '/accounts/',
            'params' => [
                'plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->Paginator->options(['url' => ['param']]);

        $result = $this->Paginator->sort('title', 'TestTitle', ['direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=desc'],
            'TestTitle',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('title', ['asc' => 'ascending', 'desc' => 'descending'], ['direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param?sort=title&amp;direction=desc'],
            'descending',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testSortLinksUsingDotNotation method
     *
     * @return void
     */
    public function testSortLinksUsingDotNotation()
    {
        $request = new ServerRequest([
            'url' => '/accounts/',
            'params' => [
                'plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sort('Article.title');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=Article.title&amp;direction=asc', 'class' => 'desc'],
            'Article Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sort('Article.title', 'Title');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=Article.title&amp;direction=asc', 'class' => 'desc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sort('Article.title', 'Title');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=Article.title&amp;direction=desc', 'class' => 'asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Account.title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/accounts/index?sort=title&amp;direction=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test multiple pagination sort links
     *
     * @return void
     */
    public function testSortLinksMultiplePagination()
    {
        $request = new ServerRequest([
            'url' => '/accounts/',
            'params' => [
                'plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->Paginator->options(['model' => 'Articles']);
        $this->setPagingParams([
            'Articles' => [
                'current' => 9,
                'count' => 62,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 7,
                'sort' => 'date',
                'direction' => 'asc',
                'page' => 1,
                'scope' => 'article',
            ],
            'Tags' => [
                'current' => 1,
                'count' => 100,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
                'sort' => 'tag',
                'direction' => 'asc',
                'page' => 1,
                'scope' => 'tags',
            ],
        ]);

        $result = $this->Paginator->sort('title', 'Title', ['model' => 'Articles']);
        $expected = [
            'a' => ['href' => '/accounts/index?article%5Bsort%5D=title&amp;article%5Bdirection%5D=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->sort('tag', 'Tag', ['model' => 'Tags']);
        $expected = [
            'a' => ['class' => 'asc', 'href' => '/accounts/index?tags%5Bsort%5D=tag&amp;tags%5Bdirection%5D=desc'],
            'Tag',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test creating paging links for missing models.
     *
     * @return void
     */
    public function testPagingLinksMissingModel()
    {
        $result = $this->Paginator->sort('title', 'Title', ['model' => 'Missing']);
        $expected = [
            'a' => ['href' => '/?sort=title&amp;direction=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('Next', ['model' => 'Missing']);
        $expected = [
            'li' => ['class' => 'next disabled'],
            'a' => ['href' => '', 'onclick' => 'return false;'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('Prev', ['model' => 'Missing']);
        $expected = [
            'li' => ['class' => 'prev disabled'],
            'a' => ['href' => '', 'onclick' => 'return false;'],
            'Prev',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testSortKey method
     *
     * @return void
     */
    public function testSortKey()
    {
        $result = $this->Paginator->sortKey('Article', ['sort' => 'Article.title']);
        $this->assertSame('Article.title', $result);

        $result = $this->Paginator->sortKey('Article', ['sort' => 'Article']);
        $this->assertSame('Article', $result);
    }

    /**
     * Test that sortKey falls back to the default sorting options set
     * in the $params which are the default pagination options.
     *
     * @return void
     */
    public function testSortKeyFallbackToParams()
    {
        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.body',
            ],
        ]);
        $result = $this->Paginator->sortKey();
        $this->assertSame('Article.body', $result);

        $result = $this->Paginator->sortKey('Article');
        $this->assertSame('Article.body', $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.body',
                'order' => 'DESC',
            ],
        ]);
        $result = $this->Paginator->sortKey();
        $this->assertSame('Article.body', $result);

        $result = $this->Paginator->sortKey('Article');
        $this->assertSame('Article.body', $result);
    }

    /**
     * testSortDir method
     *
     * @return void
     */
    public function testSortDir()
    {
        $result = $this->Paginator->sortDir();
        $expected = 'asc';
        $this->assertSame($expected, $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sortDir();
        $this->assertSame('desc', $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sortDir();
        $this->assertSame('asc', $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'title',
                'direction' => 'desc',
            ],
        ]);
        $result = $this->Paginator->sortDir();
        $this->assertSame('desc', $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'title',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sortDir();
        $this->assertSame('asc', $result);

        $this->setPagingParams([
            'Article' => [
                'direction' => null,
            ],
        ]);
        $result = $this->Paginator->sortDir('Article', ['direction' => 'asc']);
        $this->assertSame('asc', $result);

        $result = $this->Paginator->sortDir('Article', ['direction' => 'desc']);
        $this->assertSame('desc', $result);

        $result = $this->Paginator->sortDir('Article', ['direction' => 'asc']);
        $this->assertSame('asc', $result);
    }

    /**
     * Test that sortDir falls back to the default sorting options set
     * in the $params which are the default pagination options.
     *
     * @return void
     */
    public function testSortDirFallbackToParams()
    {
        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.body',
                'direction' => 'asc',
            ],
        ]);

        $result = $this->Paginator->sortDir();
        $this->assertSame('asc', $result);

        $result = $this->Paginator->sortDir('Article');
        $this->assertSame('asc', $result);

        $this->setPagingParams([
            'Article' => [
                'sort' => 'Article.body',
                'direction' => 'DESC',
            ],
        ]);

        $result = $this->Paginator->sortDir();
        $this->assertSame('desc', $result);

        $result = $this->Paginator->sortDir('Article');
        $this->assertSame('desc', $result);
    }

    /**
     * testSortAdminLinks method
     *
     * @return void
     */
    public function testSortAdminLinks()
    {
        Router::reload();
        Router::connect('/admin/:controller/:action/*', ['prefix' => 'admin']);

        $request = new ServerRequest([
            'url' => '/admin/users',
            'params' => [
                'plugin' => null, 'controller' => 'users', 'action' => 'index', 'prefix' => 'admin',
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->setPagingParams([
            'Article' => [
                'page' => 1,
            ],
        ]);
        $result = $this->Paginator->next('Next');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/admin/users/index?page=2', 'rel' => 'next'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->Paginator->options(['url' => ['param']]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/admin/users/index/param?sort=title&amp;direction=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->Paginator->options(['url' => ['param']]);
        $result = $this->Paginator->sort('Article.title', 'Title');
        $expected = [
            'a' => ['href' => '/admin/users/index/param?sort=Article.title&amp;direction=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test that generated URLs work without sort defined within the request
     *
     * @return void
     */
    public function testDefaultSortAndNoSort()
    {
        $request = new ServerRequest([
            'url' => '/articles/index',
            'params' => [
                'plugin' => null, 'controller' => 'articles', 'action' => 'index',
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 1, 'current' => 3, 'count' => 13,
                'prevPage' => false, 'nextPage' => true, 'pageCount' => 8,
                'sortDefault' => 'Article.title', 'directionDefault' => 'ASC',
                'sort' => null,
            ],
        ]));
        $result = $this->Paginator->next('Next');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['rel' => 'next', 'href' => '/articles/index?page=2'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testUrlGeneration method
     *
     * @return void
     */
    public function testUrlGeneration()
    {
        $result = $this->Paginator->sort('controller');
        $expected = [
            'a' => ['href' => '/?sort=controller&amp;direction=asc'],
            'Controller',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->generateUrl();
        $this->assertSame('/', $result);

        $this->setPagingParams([
            'Article' => [
                'page' => 2,
            ],
        ]);
        $result = $this->Paginator->generateUrl();
        $this->assertSame('/?page=2', $result);

        $options = ['sort' => 'Article', 'direction' => 'desc'];
        $result = $this->Paginator->generateUrl($options);
        $this->assertSame('/?sort=Article&amp;direction=desc&amp;page=2', $result);

        $this->setPagingParams([
            'Article' => [
                'page' => 3,
            ],
        ]);
        $options = ['sort' => 'Article.name', 'direction' => 'desc'];
        $result = $this->Paginator->generateUrl($options);
        $this->assertSame('/?sort=Article.name&amp;direction=desc&amp;page=3', $result);

        $this->setPagingParams([
            'Article' => [
                'page' => 3,
            ],
        ]);
        $options = ['sort' => 'Article.name', 'direction' => 'desc'];
        $result = $this->Paginator->generateUrl($options, null, [], ['escape' => false]);
        $this->assertSame('/?sort=Article.name&direction=desc&page=3', $result);

        $this->setPagingParams([
            'Article' => [
                'page' => 3,
            ],
        ]);
        $options = ['sort' => 'Article.name', 'direction' => 'desc'];
        $result = $this->Paginator->generateUrl($options, null, [], ['fullBase' => true]);
        $this->assertSame('http://localhost/?sort=Article.name&amp;direction=desc&amp;page=3', $result);
    }

    /**
     * Verify that sort links always result in a url that is page 1 (page not
     * present in the url)
     *
     * @param string $field
     * @param array $options
     * @param string $expected
     * @dataProvider urlGenerationResetsToPage1Provider
     */
    public function testUrlGenerationResetsToPage1($field, $options, $expected)
    {
        $this->setPagingParams([
            'Article' => [
                'page' => 2,
                'sort' => 'name',
                'direction' => 'asc',
            ],
        ]);
        $result = $this->Paginator->sort($field, null, ['url' => ['?' => $options]]);
        $this->assertSame($expected, $result);
    }

    /**
     * Returns data sets of:
     *  * the name of the field being sorted on
     *  * url parameters to pass to paginator sort
     *  * expected result as a string
     *
     * @return array
     */
    public function urlGenerationResetsToPage1Provider()
    {
        return [
            'Sorting the field currently sorted asc, asc' => [
                'name',
                ['sort' => 'name', 'direction' => 'asc'],
                '<a class="asc" href="/?sort=name&amp;direction=asc">Name</a>',
            ],
            'Sorting the field currently sorted asc, desc' => [
                'name',
                ['sort' => 'name', 'direction' => 'desc'],
                '<a class="asc" href="/?sort=name&amp;direction=desc">Name</a>',
            ],
            'Sorting other asc' => [
                'other',
                ['sort' => 'other', 'direction' => 'asc'],
                '<a href="/?sort=other&amp;direction=asc">Other</a>',
            ],
            'Sorting other desc' => [
                'other',
                ['sort' => 'other', 'direction' => 'desc'],
                '<a href="/?sort=other&amp;direction=desc">Other</a>',
            ],
        ];
    }

    /**
     * test URL generation with prefix routes
     *
     * @return void
     */
    public function testGenerateUrlWithPrefixes()
    {
        Router::reload();
        Router::connect('/members/:controller/:action/*', ['prefix' => 'members']);
        Router::connect('/:controller/:action/*');

        $request = new ServerRequest([
            'url' => '/Posts/index',
            'params' => [
                'plugin' => null, 'controller' => 'Posts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->setPagingParams([
            'Article' => [
                'page' => 2,
                'prevPage' => true,
            ],
        ]);
        $url = ['prefix' => 'members'];

        $result = $this->Paginator->generateUrl([], null, $url);
        $expected = '/members/Posts/index?page=2';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->sort('name', null, ['url' => $url]);
        $expected = [
            'a' => ['href' => '/members/Posts/index?sort=name&amp;direction=asc'],
            'Name',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('next', ['url' => $url]);
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/members/Posts/index?page=3', 'rel' => 'next'],
            'next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('prev', ['url' => $url]);
        $expected = [
            'li' => ['class' => 'prev'],
            'a' => ['href' => '/members/Posts/index', 'rel' => 'prev'],
            'prev',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $options = ['sort' => 'name', 'direction' => 'desc'];
        $url = [
            'prefix' => 'members',
            'controller' => 'Posts',
        ];
        $result = $this->Paginator->generateUrl($options, null, $url);
        $expected = '/members/Posts/index?sort=name&amp;direction=desc&amp;page=2';
        $this->assertSame($expected, $result);

        $options = ['sort' => 'Article.name', 'direction' => 'desc'];
        $url = ['controller' => 'Posts'];
        $result = $this->Paginator->generateUrl($options, null, $url);
        $expected = '/Posts/index?sort=Article.name&amp;direction=desc&amp;page=2';
        $this->assertSame($expected, $result);
    }

    /**
     * test URL generation can leave prefix routes
     *
     * @return void
     */
    public function testGenerateUrlWithPrefixesLeavePrefix()
    {
        Router::reload();
        Router::connect('/members/:controller/:action/*', ['prefix' => 'members']);
        Router::connect('/:controller/:action/*');

        $request = new ServerRequest([
            'params' => [
                'prefix' => 'members',
                'controller' => 'posts',
                'action' => 'index',
                'plugin' => null,
            ],
            'webroot' => '/',
        ]);
        $request = $request->withAttribute('paging', ['Article' => ['page' => 2, 'prevPage' => true]]);
        Router::setRequest($request);
        $this->View->setRequest($request);

        $result = $this->Paginator->generateUrl();
        $expected = '/members/posts/index?page=2';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->generateUrl([], null, ['prefix' => 'members']);
        $expected = '/members/posts/index?page=2';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->generateUrl([], null, ['prefix' => false]);
        $expected = '/posts/index?page=2';
        $this->assertSame($expected, $result);

        $this->Paginator->options(['url' => ['prefix' => false]]);
        $result = $this->Paginator->generateUrl();
        $this->assertSame($expected, $result, 'Setting prefix in options should work too.');
    }

    /**
     * test generateUrl with multiple pagination
     *
     * @return void
     */
    public function testGenerateUrlMultiplePagination()
    {
        $request = new ServerRequest([
            'url' => '/Posts/index',
            'params' => [
                'plugin' => null, 'controller' => 'Posts', 'action' => 'index', 'pass' => [],
            ],
            'base' => '',
            'webroot' => '/',
        ]);
        Router::setRequest($request);

        $this->setPagingParams([
            'Article' => [
                'scope' => 'article',
                'page' => 3,
                'prevPage' => true,
            ],
        ]);
        $this->Paginator->options(['model' => 'Article']);

        $result = $this->Paginator->generateUrl([]);
        $expected = '/Posts/index?article%5Bpage%5D=3';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->sort('name');
        $expected = [
            'a' => ['href' => '/Posts/index?article%5Bsort%5D=name&amp;article%5Bdirection%5D=asc'],
            'Name',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('next');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/Posts/index?article%5Bpage%5D=4', 'rel' => 'next'],
            'next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('prev');
        $expected = [
            'li' => ['class' => 'prev'],
            'a' => ['href' => '/Posts/index?article%5Bpage%5D=2', 'rel' => 'prev'],
            'prev',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->generateUrl(['sort' => 'name']);
        $expected = '/Posts/index?article%5Bsort%5D=name&amp;article%5Bpage%5D=3';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->generateUrl([], null, ['#' => 'foo']);
        $expected = '/Posts/index?article%5Bpage%5D=3#foo';
        $this->assertSame($expected, $result);
    }

    /**
     * test generateUrl with multiple pagination and query string values
     *
     * @return void
     */
    public function testGenerateUrlMultiplePaginationQueryStringData()
    {
        $request = new ServerRequest([
            'url' => '/Posts/index',
            'params' => [
                'plugin' => null, 'controller' => 'Posts', 'action' => 'index',
            ],
        ]);
        Router::setRequest($request);

        $this->setPagingParams([
            'Article' => [
                'scope' => 'article',
                'page' => 3,
                'prevPage' => true,
            ],
        ]);
        $this->View->setRequest($this->View->getRequest()
            ->withQueryParams([
                'article' => [
                    'puppy' => 'no',
                ],
            ]));
        // Need to run __construct to update _config['url']
        $paginator = new PaginatorHelper($this->View);
        $paginator->options(['model' => 'Article']);

        $result = $paginator->generateUrl(['sort' => 'name']);
        $expected = '/Posts/index?article%5Bsort%5D=name&amp;article%5Bpage%5D=3&amp;article%5Bpuppy%5D=no';
        $this->assertSame($expected, $result);

        $result = $paginator->generateUrl([]);
        $expected = '/Posts/index?article%5Bpage%5D=3&amp;article%5Bpuppy%5D=no';
        $this->assertSame($expected, $result);
    }

    /**
     * testOptions method
     *
     * @return void
     */
    public function testOptions()
    {
        $this->Paginator->options = [];
        $this->View->setRequest($this->View->getRequest()->withAttribute('params', []));

        $options = ['paging' => ['Article' => [
            'direction' => 'desc',
            'sort' => 'title',
        ]]];
        $this->Paginator->options($options);

        $expected = ['Article' => [
            'direction' => 'desc',
            'sort' => 'title',
        ]];
        $this->assertEquals($expected, $this->View->getRequest()->getAttribute('paging'));

        $this->Paginator->options = [];

        $options = ['Article' => [
            'direction' => 'desc',
            'sort' => 'title',
        ]];
        $this->Paginator->options($options);
        $this->assertEquals($expected, $this->View->getRequest()->getAttribute('paging'));

        $options = ['paging' => ['Article' => [
            'direction' => 'desc',
            'sort' => 'Article.title',
        ]]];
        $this->Paginator->options($options);

        $expected = ['Article' => [
            'direction' => 'desc',
            'sort' => 'Article.title',
        ]];
        $this->assertEquals($expected, $this->View->getRequest()->getAttribute('paging'));
    }

    /**
     * testPassedArgsMergingWithUrlOptions method
     *
     * @return void
     */
    public function testPassedArgsMergingWithUrlOptions()
    {
        $request = new ServerRequest([
            'url' => '/articles/',
            'params' => [
                'plugin' => null, 'controller' => 'articles', 'action' => 'index', 'pass' => [],
            ],
        ]);
        Router::setRequest($request);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 1, 'current' => 3, 'count' => 13,
                'prevPage' => false, 'nextPage' => true, 'pageCount' => 8,
                'sort' => null, 'direction' => null,
            ],
        ]));

        $this->View->setRequest($this->View->getRequest()
            ->withParam('pass', [2])
            ->withQueryParams(['page' => 1, 'foo' => 'bar', 'x' => 'y', 'num' => 0]));
        $this->Paginator = new PaginatorHelper($this->View);

        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;sort=title&amp;direction=asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers();
        $expected = [
            ['li' => ['class' => 'active']], '<a href=""', '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=7']], '7', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('Next');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/articles/index/2?foo=bar&amp;x=y&amp;num=0&amp;page=2', 'rel' => 'next'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test that generated URLs don't include sort and direction parameters
     *
     * @return void
     */
    public function testDefaultSortRemovedFromUrl()
    {
        $request = new ServerRequest([
            'url' => '/articles/',
            'params' => [
                'plugin' => null, 'controller' => 'articles', 'action' => 'index',
            ],
        ]);
        Router::setRequest($request);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 1, 'current' => 3, 'count' => 13,
                'prevPage' => false, 'nextPage' => true, 'pageCount' => 8,
                'sort' => 'Article.title', 'direction' => 'ASC',
                'sortDefault' => 'Article.title', 'directionDefault' => 'ASC',
            ],
        ]));
        $result = $this->Paginator->next('Next');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['rel' => 'next', 'href' => '/articles/index?page=2'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Tests that generated default order URL doesn't include sort and direction parameters.
     *
     * @return void
     */
    public function testDefaultSortRemovedFromUrlWithAliases()
    {
        $request = new ServerRequest([
            'params' => ['controller' => 'articles', 'action' => 'index', 'plugin' => null],
            'url' => '/articles?sort=title&direction=asc',
        ]);
        Router::setRequest($request);

        $this->Paginator->options(['model' => 'Articles']);
        $request = $this->View->getRequest()->withAttribute('paging', [
            'Articles' => [
                'page' => 1, 'current' => 3, 'count' => 13,
                'prevPage' => false, 'nextPage' => true, 'pageCount' => 8,
                'sort' => 'Articles.title', 'direction' => 'asc',
                'sortDefault' => 'Articles.title', 'directionDefault' => 'desc',
            ],
        ]);
        $this->View->setRequest($request);

        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['class' => 'asc', 'href' => '/articles/index'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test the prev() method.
     *
     * @return void
     */
    public function testPrev()
    {
        $this->setPagingParams([
            'Client' => [
                'page' => 1,
                'current' => 3,
                'count' => 13,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
            ],
        ], false);
        $result = $this->Paginator->prev('<< Previous');
        $expected = [
            'li' => ['class' => 'prev disabled'],
            'a' => ['href' => '', 'onclick' => 'return false;'],
            '&lt;&lt; Previous',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('<< Previous', ['disabledTitle' => 'Prev']);
        $expected = [
            'li' => ['class' => 'prev disabled'],
            'a' => ['href' => '', 'onclick' => 'return false;'],
            'Prev',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('<< Previous', ['disabledTitle' => false]);
        $this->assertSame('', $result, 'disabled + no text = no link');

        $this->setPagingParams(['Client' => [
            'page' => 2,
            'prevPage' => true,
        ]]);
        $result = $this->Paginator->prev('<< Previous');
        $expected = [
            'li' => ['class' => 'prev'],
            'a' => ['href' => '/', 'rel' => 'prev'],
            '&lt;&lt; Previous',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('Prev', [
            'templates' => [
                'prevActive' => '<a rel="prev" href="{{url}}">{{text}}</a>',
            ],
        ]);
        $expected = [
            'a' => ['href' => '/', 'rel' => 'prev'],
            'Prev',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test that prev() and the shared implementation underneath picks up from options
     *
     * @return void
     */
    public function testPrevWithOptions()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 2, 'current' => 1, 'count' => 13, 'prevPage' => true,
                'nextPage' => false, 'pageCount' => 2,
                'limit' => 10,
            ],
        ]));
        $this->Paginator->options(['url' => [12, 'page' => 3]]);
        $result = $this->Paginator->prev('Prev', ['url' => ['?' => ['foo' => 'bar']]]);
        $expected = [
            'li' => ['class' => 'prev'],
            'a' => ['href' => '/Articles/index/12?foo=bar&amp;limit=10', 'rel' => 'prev'],
            'Prev',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test the next() method.
     *
     * @return void
     */
    public function testNext()
    {
        $result = $this->Paginator->next('Next >>');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/?page=2', 'rel' => 'next'],
            'Next &gt;&gt;',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('Next', [
            'templates' => [
                'nextActive' => '<a rel="next" href="{{url}}">{{text}}</a>',
            ],
        ]);
        $expected = [
            'a' => ['href' => '/?page=2', 'rel' => 'next'],
            'Next',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('Next >>', ['escape' => false]);
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/?page=2', 'rel' => 'next'],
            'preg:/Next >>/',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test next() with disabled links
     *
     * @return void
     */
    public function testNextDisabled()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 5,
                'current' => 3,
                'count' => 13,
                'prevPage' => true,
                'nextPage' => false,
                'pageCount' => 5,
            ],
        ]));
        $result = $this->Paginator->next('Next >>');
        $expected = [
            'li' => ['class' => 'next disabled'],
            'a' => ['href' => '', 'onclick' => 'return false;'],
            'Next &gt;&gt;',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('Next >>', ['disabledTitle' => 'Next']);
        $expected = [
            'li' => ['class' => 'next disabled'],
            'a' => ['href' => '', 'onclick' => 'return false;'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->next('Next >>', ['disabledTitle' => false]);
        $this->assertSame('', $result, 'disabled + no text = no link');
    }

    /**
     * Test next() with a model argument.
     *
     * @return void
     */
    public function testNextAndPrevNonDefaultModel()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 1,
                'current' => 3,
                'count' => 13,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
            ],
            'Server' => [
                'page' => 5,
                'current' => 1,
                'count' => 5,
                'prevPage' => true,
                'nextPage' => false,
                'pageCount' => 5,
            ],
        ]));
        $result = $this->Paginator->next('Next', [
            'model' => 'Client',
        ]);
        $expected = [
            'li' => ['class' => 'next'],
            'a' => ['href' => '/?page=2', 'rel' => 'next'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->prev('Prev', [
            'model' => 'Client',
        ]);
        $expected = '<li class="prev disabled"><a href="" onclick="return false;">Prev</a></li>';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->next('Next', [
            'model' => 'Server',
        ]);
        $expected = '<li class="next disabled"><a href="" onclick="return false;">Next</a></li>';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->prev('Prev', [
            'model' => 'Server',
        ]);
        $expected = [
            'li' => ['class' => 'prev'],
            'a' => ['href' => '/?page=4', 'rel' => 'prev'],
            'Prev',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testNumbers method
     *
     * @return void
     */
    public function testNumbers()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 8,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers();
        $expected = [
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['first' => 'first', 'last' => 'last']);
        $expected = [
            ['li' => ['class' => 'first']], ['a' => ['href' => '/']], 'first', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => ['class' => 'last']], ['a' => ['href' => '/?page=15']], 'last', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['first' => '2', 'last' => '8']);
        $expected = [
            ['li' => ['class' => 'first']], ['a' => ['href' => '/']], '2', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => ['class' => 'last']], ['a' => ['href' => '/?page=15']], '8', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['first' => '8', 'last' => '8']);
        $expected = [
            ['li' => ['class' => 'first']], ['a' => ['href' => '/']], '8', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => ['class' => 'last']], ['a' => ['href' => '/?page=15']], '8', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 1,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers();
        $expected = [
            ['li' => ['class' => 'active']], '<a href=""', '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 14,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers();
        $expected = [
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=13']], '13', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '14', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=15']], '15', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 2,
                'current' => 3,
                'count' => 27,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 9,
            ],
        ]));

        $result = $this->Paginator->numbers(['first' => 1]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['last' => 1]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 15,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));

        $result = $this->Paginator->numbers(['first' => 1]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=13']], '13', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=14']], '14', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '15', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 10,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));

        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12']], '12', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=13']], '13', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=14']], '14', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=15']], '15', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 6,
                'current' => 15,
                'count' => 623,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 42,
            ],
        ]));

        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=8']], '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10']], '10', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=42']], '42', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 37,
                'current' => 15,
                'count' => 623,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 42,
            ],
        ]));

        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=33']], '33', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=34']], '34', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=35']], '35', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=36']], '36', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '37', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=38']], '38', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=39']], '39', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=40']], '40', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=41']], '41', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=42']], '42', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test for URLs with paging params as route placeholders instead of query string params.
     *
     * @return void
     */
    public function testRoutePlaceholder()
    {
        Router::reload();
        Router::connect('/:controller/:action/:page');
        $request = $this->View
            ->getRequest()
            ->withAttribute('params', [
                'plugin' => null,
                'controller' => 'clients',
                'action' => 'index',
            ])
            ->withAttribute('paging', [
                'Client' => [
                    'page' => 8,
                    'current' => 3,
                    'count' => 30,
                    'prevPage' => false,
                    'nextPage' => 2,
                    'pageCount' => 15,
                ],
            ]);
        $this->View->setRequest($request);
        Router::setRequest($request);

        $this->Paginator->options(['routePlaceholders' => ['page']]);

        $result = $this->Paginator->numbers();
        $expected = [
            ['li' => []], ['a' => ['href' => '/clients/index/4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/7']], '7', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/clients/index/12']], '12', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        Router::reload();
        Router::connect('/:controller/:action/:sort/:direction');
        Router::setRequest($request);

        $this->Paginator->options(['routePlaceholders' => ['sort', 'direction']]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/clients/index/title/asc'],
            'Title',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testNumbersPages method
     *
     * @return void
     */
    public function testNumbersMulti()
    {
        $expected = [
            1 => '*1 2 3 4 5 6 7 ',
            2 => '1 *2 3 4 5 6 7 ',
            3 => '1 2 *3 4 5 6 7 ',
            4 => '1 2 3 *4 5 6 7 ',
            5 => '1 2 3 4 *5 6 7 ',
            6 => '1 2 3 4 5 *6 7 ',
            7 => '1 2 3 4 5 6 *7 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 7);
        $this->assertEquals($expected, $result);

        $result = $this->getNumbersForMultiplePages(array_keys($expected), 7, ['first' => 'F', 'last' => 'L']);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ',
            2 => '1 *2 3 4 5 6 7 8 9 ',
            3 => '1 2 *3 4 5 6 7 8 9 ',
            4 => '1 2 3 *4 5 6 7 8 9 ',
            5 => '1 2 3 4 *5 6 7 8 9 ',
            6 => '2 3 4 5 *6 7 8 9 10 ',
            7 => '3 4 5 6 *7 8 9 10 11 ',
            10 => '6 7 8 9 *10 11 12 13 14 ',
            15 => '11 12 13 14 *15 16 17 18 19 ',
            16 => '12 13 14 15 *16 17 18 19 20 ',
            17 => '12 13 14 15 16 *17 18 19 20 ',
            18 => '12 13 14 15 16 17 *18 19 20 ',
            19 => '12 13 14 15 16 17 18 *19 20 ',
            20 => '12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ',
            2 => '1 *2 3 4 5 6 7 8 9 ',
            3 => '1 2 *3 4 5 6 7 8 9 ',
            4 => '1 2 3 *4 5 6 7 8 9 ',
            5 => '1 2 3 4 *5 6 7 8 9 ',
            6 => '1 2 3 4 5 *6 7 8 9 10 ',
            7 => '1 2 3 4 5 6 *7 8 9 10 11 ',
            8 => '<F ... 4 5 6 7 *8 9 10 11 12 ',
            9 => '<F ... 5 6 7 8 *9 10 11 12 13 ',
            10 => '<F ... 6 7 8 9 *10 11 12 13 14 ',
            15 => '<F ... 11 12 13 14 *15 16 17 18 19 ',
            16 => '<F ... 12 13 14 15 *16 17 18 19 20 ',
            17 => '<F ... 12 13 14 15 16 *17 18 19 20 ',
            18 => '<F ... 12 13 14 15 16 17 *18 19 20 ',
            19 => '<F ... 12 13 14 15 16 17 18 *19 20 ',
            20 => '<F ... 12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['first' => 'F']);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ',
            2 => '1 *2 3 4 5 6 7 8 9 ',
            3 => '1 2 *3 4 5 6 7 8 9 ',
            4 => '1 2 3 *4 5 6 7 8 9 ',
            5 => '1 2 3 4 *5 6 7 8 9 ',
            6 => '1 2 3 4 5 *6 7 8 9 10 ',
            7 => '1 2 3 4 5 6 *7 8 9 10 11 ',
            8 => '1 2 3 4 5 6 7 *8 9 10 11 12 ',
            9 => '1 2 ... 5 6 7 8 *9 10 11 12 13 ',
            10 => '1 2 ... 6 7 8 9 *10 11 12 13 14 ',
            15 => '1 2 ... 11 12 13 14 *15 16 17 18 19 ',
            16 => '1 2 ... 12 13 14 15 *16 17 18 19 20 ',
            17 => '1 2 ... 12 13 14 15 16 *17 18 19 20 ',
            18 => '1 2 ... 12 13 14 15 16 17 *18 19 20 ',
            19 => '1 2 ... 12 13 14 15 16 17 18 *19 20 ',
            20 => '1 2 ... 12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['first' => 2]);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ... L> ',
            2 => '1 *2 3 4 5 6 7 8 9 ... L> ',
            3 => '1 2 *3 4 5 6 7 8 9 ... L> ',
            4 => '1 2 3 *4 5 6 7 8 9 ... L> ',
            5 => '1 2 3 4 *5 6 7 8 9 ... L> ',
            6 => '2 3 4 5 *6 7 8 9 10 ... L> ',
            7 => '3 4 5 6 *7 8 9 10 11 ... L> ',
            8 => '4 5 6 7 *8 9 10 11 12 ... L> ',
            9 => '5 6 7 8 *9 10 11 12 13 ... L> ',
            10 => '6 7 8 9 *10 11 12 13 14 ... L> ',
            11 => '7 8 9 10 *11 12 13 14 15 ... L> ',
            12 => '8 9 10 11 *12 13 14 15 16 ... L> ',
            13 => '9 10 11 12 *13 14 15 16 17 ... L> ',
            14 => '10 11 12 13 *14 15 16 17 18 19 20 ',
            15 => '11 12 13 14 *15 16 17 18 19 20 ',
            16 => '12 13 14 15 *16 17 18 19 20 ',
            17 => '12 13 14 15 16 *17 18 19 20 ',
            18 => '12 13 14 15 16 17 *18 19 20 ',
            19 => '12 13 14 15 16 17 18 *19 20 ',
            20 => '12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['last' => 'L']);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ... L> ',
            2 => '1 *2 3 4 5 6 7 8 9 ... L> ',
            3 => '1 2 *3 4 5 6 7 8 9 ... L> ',
            4 => '1 2 3 *4 5 6 7 8 9 ... L> ',
            5 => '1 2 3 4 *5 6 7 8 9 ... L> ',
            6 => '1 2 3 4 5 *6 7 8 9 10 ... L> ',
            7 => '1 2 3 4 5 6 *7 8 9 10 11 ... L> ',
            8 => '<F ... 4 5 6 7 *8 9 10 11 12 ... L> ',
            9 => '<F ... 5 6 7 8 *9 10 11 12 13 ... L> ',
            10 => '<F ... 6 7 8 9 *10 11 12 13 14 ... L> ',
            11 => '<F ... 7 8 9 10 *11 12 13 14 15 ... L> ',
            12 => '<F ... 8 9 10 11 *12 13 14 15 16 ... L> ',
            13 => '<F ... 9 10 11 12 *13 14 15 16 17 ... L> ',
            14 => '<F ... 10 11 12 13 *14 15 16 17 18 19 20 ',
            15 => '<F ... 11 12 13 14 *15 16 17 18 19 20 ',
            16 => '<F ... 12 13 14 15 *16 17 18 19 20 ',
            17 => '<F ... 12 13 14 15 16 *17 18 19 20 ',
            18 => '<F ... 12 13 14 15 16 17 *18 19 20 ',
            19 => '<F ... 12 13 14 15 16 17 18 *19 20 ',
            20 => '<F ... 12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['first' => 'F', 'last' => 'L']);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ... 19 20 ',
            2 => '1 *2 3 4 5 6 7 8 9 ... 19 20 ',
            3 => '1 2 *3 4 5 6 7 8 9 ... 19 20 ',
            4 => '1 2 3 *4 5 6 7 8 9 ... 19 20 ',
            5 => '1 2 3 4 *5 6 7 8 9 ... 19 20 ',
            6 => '1 2 3 4 5 *6 7 8 9 10 ... 19 20 ',
            7 => '1 2 3 4 5 6 *7 8 9 10 11 ... 19 20 ',
            8 => '1 2 3 4 5 6 7 *8 9 10 11 12 ... 19 20 ',
            9 => '1 2 ... 5 6 7 8 *9 10 11 12 13 ... 19 20 ',
            10 => '1 2 ... 6 7 8 9 *10 11 12 13 14 ... 19 20 ',
            11 => '1 2 ... 7 8 9 10 *11 12 13 14 15 ... 19 20 ',
            12 => '1 2 ... 8 9 10 11 *12 13 14 15 16 ... 19 20 ',
            13 => '1 2 ... 9 10 11 12 *13 14 15 16 17 18 19 20 ',
            14 => '1 2 ... 10 11 12 13 *14 15 16 17 18 19 20 ',
            15 => '1 2 ... 11 12 13 14 *15 16 17 18 19 20 ',
            16 => '1 2 ... 12 13 14 15 *16 17 18 19 20 ',
            17 => '1 2 ... 12 13 14 15 16 *17 18 19 20 ',
            18 => '1 2 ... 12 13 14 15 16 17 *18 19 20 ',
            19 => '1 2 ... 12 13 14 15 16 17 18 *19 20 ',
            20 => '1 2 ... 12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['first' => 2, 'last' => 2]);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 6 7 8 9 ... 19 20 ',
            2 => '1 *2 3 4 5 6 7 8 9 ... 19 20 ',
            3 => '1 2 *3 4 5 6 7 8 9 ... 19 20 ',
            4 => '1 2 3 *4 5 6 7 8 9 ... 19 20 ',
            5 => '1 2 3 4 *5 6 7 8 9 ... 19 20 ',
            6 => '2 3 4 5 *6 7 8 9 10 ... 19 20 ',
            7 => '3 4 5 6 *7 8 9 10 11 ... 19 20 ',
            8 => '4 5 6 7 *8 9 10 11 12 ... 19 20 ',
            9 => '5 6 7 8 *9 10 11 12 13 ... 19 20 ',
            10 => '6 7 8 9 *10 11 12 13 14 ... 19 20 ',
            11 => '7 8 9 10 *11 12 13 14 15 ... 19 20 ',
            12 => '8 9 10 11 *12 13 14 15 16 ... 19 20 ',
            13 => '9 10 11 12 *13 14 15 16 17 18 19 20 ',
            14 => '10 11 12 13 *14 15 16 17 18 19 20 ',
            15 => '11 12 13 14 *15 16 17 18 19 20 ',
            16 => '12 13 14 15 *16 17 18 19 20 ',
            17 => '12 13 14 15 16 *17 18 19 20 ',
            18 => '12 13 14 15 16 17 *18 19 20 ',
            19 => '12 13 14 15 16 17 18 *19 20 ',
            20 => '12 13 14 15 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['last' => 2]);
        $this->assertEquals($expected, $result);
    }

    /**
     * Retrieves result of PaginatorHelper::numbers for multiple pages
     *
     * @param int[] $pagesToCheck Pages to get result for
     * @param int $pageCount Number of total pages
     * @param array $options Options for PaginatorHelper::numbers
     * @return string[]
     */
    protected function getNumbersForMultiplePages($pagesToCheck, $pageCount, $options = [])
    {
        $options['templates'] = [
            'first' => '<{{text}} ',
            'last' => '{{text}}> ',
            'number' => '{{text}} ',
            'current' => '*{{text}} ',
            'ellipsis' => '... ',
        ];

        $this->setPagingParams(['Client' => [
            'page' => 1,
            'pageCount' => $pageCount,
        ]], false);

        $result = [];
        foreach ($pagesToCheck as $page) {
            $this->setPagingParams(['Client' => [
                'page' => $page,
            ]]);

            $result[$page] = $this->Paginator->numbers($options);
        }

        return $result;
    }

    /**
     * Test that numbers() lets you overwrite templates.
     *
     * The templates file has no li elements.
     *
     * @return void
     */
    public function testNumbersTemplates()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 8,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers(['templates' => 'htmlhelper_tags']);
        $expected = [
            ['a' => ['href' => '/?page=4']], '4', '/a',
            ['a' => ['href' => '/?page=5']], '5', '/a',
            ['a' => ['href' => '/?page=6']], '6', '/a',
            ['a' => ['href' => '/?page=7']], '7', '/a',
            'span' => ['class' => 'active'], '8', '/span',
            ['a' => ['href' => '/?page=9']], '9', '/a',
            ['a' => ['href' => '/?page=10']], '10', '/a',
            ['a' => ['href' => '/?page=11']], '11', '/a',
            ['a' => ['href' => '/?page=12']], '12', '/a',
        ];
        $this->assertHtml($expected, $result);

        $this->assertStringContainsString(
            '<li',
            $this->Paginator->templater()->get('current'),
            'Templates were not restored.'
        );
    }

    /**
     * Test modulus option for numbers()
     *
     * @return void
     */
    public function testNumbersModulus()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 1,
                'current' => 10,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 3,
            ],
        ]));

        $result = $this->Paginator->numbers(['modulus' => 10]);
        $expected = [
            ['li' => ['class' => 'active']], '<a href=""', '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['modulus' => 3]);
        $expected = [
            ['li' => ['class' => 'active']], '<a href=""', '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 4895,
                'current' => 10,
                'count' => 48962,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 4897,
            ],
        ]));

        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 2, 'last' => 2]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4894']], '4,894', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '4,895', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Client' => [
            'page' => 3,
        ]]);

        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 2, 'last' => 2]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 5, 'last' => 5]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4893']], '4,893', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4894']], '4,894', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4895']], '4,895', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Client' => [
            'page' => 4893,
        ]]);
        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 4, 'last' => 5]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4891']], '4,891', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4892']], '4,892', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '4,893', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4894']], '4,894', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4895']], '4,895', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Client' => [
            'page' => 58,
        ]]);
        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 4, 'last' => 5]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=56']], '56', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=57']], '57', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '58', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=59']], '59', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=60']], '60', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4893']], '4,893', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4894']], '4,894', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4895']], '4,895', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Client' => [
            'page' => 5,
        ]]);
        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 4, 'last' => 5]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7']], '7', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4893']], '4,893', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4894']], '4,894', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4895']], '4,895', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Client' => [
            'page' => 3,
        ]]);
        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 2, 'last' => 2]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4']], '4', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Client' => [
            'page' => 3,
        ]]);
        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 0, 'last' => 2]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '3', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test modulus option for numbers()
     *
     * @return void
     */
    public function testNumbersModulusMulti()
    {
        $expected = [
            1 => '*1 2 3 4 ',
            2 => '1 *2 3 4 ',
            3 => '1 2 *3 4 ',
            4 => '2 3 *4 5 ',
            5 => '3 4 *5 6 ',
            6 => '4 5 *6 7 ',
            7 => '4 5 6 *7 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 7, ['modulus' => 3]);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 ... L> ',
            2 => '1 *2 3 4 ... L> ',
            3 => '1 2 *3 4 ... L> ',
            4 => '1 2 3 *4 5 6 7 ',
            5 => '1 2 3 4 *5 6 7 ',
            6 => '<F ... 4 5 *6 7 ',
            7 => '<F ... 4 5 6 *7 ',
        ];

        $result = $this->getNumbersForMultiplePages(array_keys($expected), 7, ['modulus' => 3, 'first' => 'F', 'last' => 'L']);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 ... 19 20 ',
            2 => '1 *2 3 ... 19 20 ',
            3 => '1 2 *3 4 ... 19 20 ',
            4 => '1 2 3 *4 5 ... 19 20 ',
            5 => '1 2 3 4 *5 6 ... 19 20 ',
            6 => '1 2 ... 5 *6 7 ... 19 20 ',
            15 => '1 2 ... 14 *15 16 ... 19 20 ',
            16 => '1 2 ... 15 *16 17 18 19 20 ',
            17 => '1 2 ... 16 *17 18 19 20 ',
            18 => '1 2 ... 17 *18 19 20 ',
            19 => '1 2 ... 18 *19 20 ',
            20 => '1 2 ... 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['first' => 2, 'modulus' => 2, 'last' => 2]);
        $this->assertEquals($expected, $result);

        $expected = [
            1 => '*1 2 3 4 5 ... 16 17 18 19 20 ',
            2 => '1 *2 3 4 5 ... 16 17 18 19 20 ',
            3 => '1 2 *3 4 5 ... 16 17 18 19 20 ',
            4 => '1 2 3 *4 5 6 ... 16 17 18 19 20 ',
            5 => '1 2 3 4 *5 6 7 ... 16 17 18 19 20 ',
            6 => '1 2 3 4 5 *6 7 8 ... 16 17 18 19 20 ',
            7 => '1 2 3 4 5 6 *7 8 9 ... 16 17 18 19 20 ',
            8 => '1 2 3 4 5 6 7 *8 9 10 ... 16 17 18 19 20 ',
            9 => '1 2 3 4 5 6 7 8 *9 10 11 ... 16 17 18 19 20 ',
            10 => '1 2 3 4 5 ... 8 9 *10 11 12 ... 16 17 18 19 20 ',
            11 => '1 2 3 4 5 ... 9 10 *11 12 13 ... 16 17 18 19 20 ',
            12 => '1 2 3 4 5 ... 10 11 *12 13 14 15 16 17 18 19 20 ',
            13 => '1 2 3 4 5 ... 11 12 *13 14 15 16 17 18 19 20 ',
            14 => '1 2 3 4 5 ... 12 13 *14 15 16 17 18 19 20 ',
            15 => '1 2 3 4 5 ... 13 14 *15 16 17 18 19 20 ',
            16 => '1 2 3 4 5 ... 14 15 *16 17 18 19 20 ',
            17 => '1 2 3 4 5 ... 15 16 *17 18 19 20 ',
            18 => '1 2 3 4 5 ... 16 17 *18 19 20 ',
            19 => '1 2 3 4 5 ... 16 17 18 *19 20 ',
            20 => '1 2 3 4 5 ... 16 17 18 19 *20 ',
        ];
        $result = $this->getNumbersForMultiplePages(array_keys($expected), 20, ['first' => 5, 'modulus' => 4, 'last' => 5]);
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests that disabling modulus displays all page links.
     *
     * @return void
     */
    public function testModulusDisabled()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 4,
                'current' => 2,
                'count' => 30,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 6,
            ],
        ]));

        $result = $this->Paginator->numbers(['modulus' => false]);
        $expected = [
            ['li' => []], '<a href="/"', '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=2']], '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=3']], '3', '/a', '/li',
            ['li' => ['class' => 'active']], ['a' => ['href' => '']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6']], '6', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test that numbers() with url options.
     *
     * @return void
     */
    public function testNumbersWithUrlOptions()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 8,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers(['url' => ['#' => 'foo']]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/?page=4#foo']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=5#foo']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=6#foo']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=7#foo']], '7', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '8', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=9#foo']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=10#foo']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=11#foo']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?page=12#foo']], '12', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 3,
                'current' => 10,
                'count' => 48962,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 4897,
            ],
        ]));
        $result = $this->Paginator->numbers([
            'first' => 2,
            'modulus' => 2,
            'last' => 2,
            'url' => ['?' => ['foo' => 'bar']]]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/?foo=bar']], '1', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?foo=bar&amp;page=2']], '2', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '3', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?foo=bar&amp;page=4']], '4', '/a', '/li',
            ['li' => ['class' => 'ellipsis']], '&hellip;', '/li',
            ['li' => []], ['a' => ['href' => '/?foo=bar&amp;page=4896']], '4,896', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/?foo=bar&amp;page=4897']], '4,897', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test numbers() with routing parameters.
     *
     * @return void
     */
    public function testNumbersRouting()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 2,
                'current' => 2,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 3,
                'pageCount' => 3,
            ],
        ]));

        $request = new ServerRequest([
            'params' => ['controller' => 'Clients', 'action' => 'index', 'plugin' => null],
            'url' => '/Clients/index/?page=2',
        ]);
        Router::setRequest($request);

        $result = $this->Paginator->numbers();
        $expected = [
            ['li' => []], ['a' => ['href' => '/Clients/index']], '1', '/a', '/li',
            ['li' => ['class' => 'active']], '<a href=""', '2', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/Clients/index?page=3']], '3', '/a', '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * Test that numbers() works with the non default model.
     *
     * @return void
     */
    public function testNumbersNonDefaultModel()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 1,
                'current' => 3,
                'count' => 13,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
            ],
            'Server' => [
                'page' => 5,
                'current' => 1,
                'count' => 5,
                'prevPage' => true,
                'nextPage' => false,
                'pageCount' => 5,
            ],
        ]));
        $result = $this->Paginator->numbers(['model' => 'Server']);
        $this->assertStringContainsString('<li class="active"><a href="">5</a></li>', $result);
        $this->assertStringNotContainsString('<li class="active"><a href="">1</a></li>', $result);

        $result = $this->Paginator->numbers(['model' => 'Client']);
        $this->assertStringContainsString('<li class="active"><a href="">1</a></li>', $result);
        $this->assertStringNotContainsString('<li class="active"><a href="">5</a></li>', $result);
    }

    /**
     * test first() and last() with tag options
     *
     * @return void
     */
    public function testFirstAndLastTag()
    {
        $this->setPagingParams(['Article' => [
            'page' => 2,
        ]]);
        $result = $this->Paginator->first('<<');
        $expected = [
            'li' => ['class' => 'first'],
            'a' => ['href' => '/'],
            '&lt;&lt;',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->first('5');
        $expected = [
            'li' => ['class' => 'first'],
            'a' => ['href' => '/'],
            '5',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->last(2);
        $expected = [
            '<li',
            ['a' => ['href' => '/?page=6']], '6', '/a',
            '/li',
            '<li',
            ['a' => ['href' => '/?page=7']], '7', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->last('9');
        $expected = [
            'li' => ['class' => 'last'],
            'a' => ['href' => '/?page=7'],
            '9',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->first('first', ['url' => ['action' => 'paged']]);
        $expected = [
            'li' => ['class' => 'first'],
            'a' => ['href' => '/Articles/paged'],
            'first',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test that on the last page you don't get a link ot the last page.
     *
     * @return void
     */
    public function testLastNoOutput()
    {
        $this->setPagingParams(['Article' => [
            'page' => 15,
            'pageCount' => 15,
        ]]);

        $result = $this->Paginator->last();
        $expected = '';
        $this->assertSame($expected, $result);
    }

    /**
     * test first() with a the model parameter.
     *
     * @return void
     */
    public function testFirstNonDefaultModel()
    {
        $this->setPagingParams([
            'Article' => ['page' => 1],
            'Client' => [
                'page' => 3,
                'current' => 3,
                'count' => 13,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
            ],
        ]);

        $result = $this->Paginator->first('first', ['model' => 'Article']);
        $this->assertSame('', $result);

        $result = $this->Paginator->first('first', ['model' => 'Client', 'url' => ['?' => ['foo' => 'bar']]]);
        $expected = [
            'li' => ['class' => 'first'],
            'a' => ['href' => '/?foo=bar'],
            'first',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test first() on the first page.
     *
     * @return void
     */
    public function testFirstEmpty()
    {
        $this->View->setRequest($this->View->getRequest()->withParam('paging.Article.page', 1));

        $result = $this->Paginator->first();
        $expected = '';
        $this->assertSame($expected, $result);
    }

    /**
     * test first() and options()
     *
     * @return void
     */
    public function testFirstFullBaseUrl()
    {
        $this->setPagingParams(['Article' => [
            'page' => 3,
            'direction' => 'DESC',
            'sort' => 'Article.title',
        ]]);

        $this->Paginator->options(['url' => ['_full' => true, '#' => 'foo']]);

        $result = $this->Paginator->first();
        $expected = [
            'li' => ['class' => 'first'],
            ['a' => [
                'href' => Configure::read('App.fullBaseUrl') . '/?sort=Article.title&amp;direction=DESC#foo',
            ]],
            '&lt;&lt; first',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test first() on the fence-post
     *
     * @return void
     */
    public function testFirstBoundaries()
    {
        $this->setPagingParams(['Article' => ['page' => 3]]);
        $result = $this->Paginator->first();
        $expected = [
            'li' => ['class' => 'first'],
            'a' => ['href' => '/'],
            '&lt;&lt; first',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->first(2);
        $expected = [
            '<li',
            ['a' => ['href' => '/']], '1', '/a',
            '/li',
            '<li',
            ['a' => ['href' => '/?page=2']], '2', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Article' => [
            'page' => 2,
        ]]);
        $result = $this->Paginator->first(3);
        $this->assertSame('', $result, 'When inside the first links range, no links should be made');
    }

    /**
     * test params() method
     *
     * @return void
     */
    public function testParams()
    {
        $result = $this->Paginator->params();
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('pageCount', $result);

        $result = $this->Paginator->params('Nope');
        $this->assertEquals([], $result);
    }

    /**
     * test param() method
     *
     * @return void
     */
    public function testParam()
    {
        $result = $this->Paginator->param('count');
        $this->assertSame(62, $result);

        $result = $this->Paginator->param('imaginary');
        $this->assertNull($result);
    }

    /**
     * test last() method
     *
     * @return void
     */
    public function testLast()
    {
        $result = $this->Paginator->last();
        $expected = [
            'li' => ['class' => 'last'],
            'a' => ['href' => '/?page=7'],
            'last &gt;&gt;',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->last(1);
        $expected = [
            '<li',
            'a' => ['href' => '/?page=7'],
            '7',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $this->setPagingParams(['Article' => ['page' => 6]]);

        $result = $this->Paginator->last(2);
        $expected = [
            '<li',
            ['a' => ['href' => '/?page=6']], '6', '/a',
            '/li',
            '<li',
            ['a' => ['href' => '/?page=7']], '7', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->last(3);
        $this->assertSame('', $result, 'When inside the last links range, no links should be made');

        $result = $this->Paginator->last('lastest', ['url' => ['action' => 'paged']]);
        $expected = [
            'li' => ['class' => 'last'],
            'a' => ['href' => '/Articles/paged?page=7'],
            'lastest',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test the options for last()
     *
     * @return void
     */
    public function testLastOptions()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 4,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
                'sort' => 'Client.name',
                'direction' => 'DESC',
            ],
        ]));

        $result = $this->Paginator->last();
        $expected = [
            'li' => ['class' => 'last'],
            'a' => [
                'href' => '/?page=15&amp;sort=Client.name&amp;direction=DESC',
            ],
            'last &gt;&gt;', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->last(1);
        $expected = [
            '<li',
            ['a' => ['href' => '/?page=15&amp;sort=Client.name&amp;direction=DESC']], '15', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->last(2);
        $expected = [
            '<li',
            ['a' => ['href' => '/?page=14&amp;sort=Client.name&amp;direction=DESC']], '14', '/a',
            '/li',
            '<li',
            ['a' => ['href' => '/?page=15&amp;sort=Client.name&amp;direction=DESC']], '15', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test last() with a the model parameter.
     *
     * @return void
     */
    public function testLastNonDefaultModel()
    {
        $this->setPagingParams([
            'Article' => ['page' => 7],
            'Client' => [
                'page' => 3,
                'current' => 3,
                'count' => 13,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
            ],
        ]);

        $result = $this->Paginator->last('last', ['model' => 'Article']);
        $this->assertSame('', $result);

        $result = $this->Paginator->last('last', ['model' => 'Client']);
        $expected = [
            'li' => ['class' => 'last'],
            'a' => ['href' => '/?page=5'],
            'last',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * testCounter method
     *
     * @return void
     */
    public function testCounter()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 1,
                'current' => 3,
                'count' => 13,
                'perPage' => 3,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
                'limit' => 3,
                'sort' => 'Client.name',
                'order' => 'DESC',
                'start' => 1,
                'end' => 3,
            ],
        ]));
        $input = 'Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total, ';
        $input .= 'starting on record {{start}}, ending on {{end}}';

        $expected = 'Page 1 of 5, showing 3 records out of 13 total, starting on record 1, ';
        $expected .= 'ending on 3';
        $result = $this->Paginator->counter($input);
        $this->assertSame($expected, $result);

        $result = $this->Paginator->counter('pages');
        $expected = '1 of 5';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->counter('range');
        $expected = '1 - 3 of 13';
        $this->assertSame($expected, $result);

        $result = $this->Paginator->counter('Showing {{page}} of {{pages}} {{model}}');
        $this->assertSame('Showing 1 of 5 clients', $result);
    }

    /**
     * Tests that numbers are formatted according to the locale when using counter()
     *
     * @return void
     */
    public function testCounterBigNumbers()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 1523,
                'current' => 3000,
                'count' => 4800001,
                'perPage' => 3000,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 1600,
                'limit' => 5000,
                'sort' => 'Client.name',
                'order' => 'DESC',
                'start' => 4566001,
                'end' => 4569001,
            ],
        ]));

        $input = 'Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total, ';
        $input .= 'starting on record {{start}}, ending on {{end}}';

        $expected = 'Page 1,523 of 1,600, showing 3,000 records out of 4,800,001 total, ';
        $expected .= 'starting on record 4,566,001, ending on 4,569,001';
        $result = $this->Paginator->counter($input);
        $this->assertSame($expected, $result);

        I18n::setLocale('de-DE');
        $expected = 'Page 1.523 of 1.600, showing 3.000 records out of 4.800.001 total, ';
        $expected .= 'starting on record 4.566.001, ending on 4.569.001';
        $result = $this->Paginator->counter($input);
        $this->assertSame($expected, $result);
    }

    /**
     * testHasPage method
     *
     * @return void
     */
    public function testHasPage()
    {
        $result = $this->Paginator->hasPage(15, 'Article');
        $this->assertFalse($result);

        $result = $this->Paginator->hasPage(2, 'UndefinedModel');
        $this->assertFalse($result);

        $result = $this->Paginator->hasPage(2, 'Article');
        $this->assertTrue($result);

        $result = $this->Paginator->hasPage(2);
        $this->assertTrue($result);
    }

    /**
     * testNextLinkUsingDotNotation method
     *
     * @return void
     */
    public function testNextLinkUsingDotNotation()
    {
        $request = new ServerRequest([
            'url' => '/Accounts/index',
            'params' => [
                'plugin' => null, 'controller' => 'Accounts', 'action' => 'index',
            ],
        ]);
        Router::setRequest($request);

        $params = [
            'Article' => [
                'sort' => 'Article.title',
                'direction' => 'asc',
                'page' => 1,
            ],
        ];
        $params = Hash::merge($this->View->getRequest()->getAttribute('paging'), $params);
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', $params));

        $test = ['url' => [
            'page' => '1',
            'sort' => 'Article.title',
            'direction' => 'asc',
        ]];
        $this->Paginator->options($test);

        $result = $this->Paginator->next('Next');
        $expected = [
            'li' => ['class' => 'next'],
            'a' => [
                'href' => '/Accounts/index?page=2&amp;sort=Article.title&amp;direction=asc',
                'rel' => 'next',
            ],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * test the current() method
     *
     * @return void
     */
    public function testCurrent()
    {
        $result = $this->Paginator->current();
        $params = $this->View->getRequest()->getAttribute('paging');
        $this->assertSame($params['Article']['page'], $result);

        $result = $this->Paginator->current('Incorrect');
        $this->assertSame(1, $result);
    }

    /**
     * test the total() method
     *
     * @return void
     */
    public function testTotal()
    {
        $result = $this->Paginator->total();
        $params = $this->View->getRequest()->getAttribute('paging');
        $this->assertSame($params['Article']['pageCount'], $result);

        $result = $this->Paginator->total('Incorrect');
        $this->assertSame(0, $result);
    }

    /**
     * test the defaultModel() method
     *
     * @return void
     */
    public function testNoDefaultModel()
    {
        $this->View->setRequest(new ServerRequest());
        $this->assertNull($this->Paginator->defaultModel());

        $this->Paginator->defaultModel('Article');
        $this->assertSame('Article', $this->Paginator->defaultModel());

        $this->Paginator->options(['model' => 'Client']);
        $this->assertSame('Client', $this->Paginator->defaultModel());
    }

    /**
     * test the numbers() method when there is only one page
     *
     * @return void
     */
    public function testWithOnePage()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 1,
                'current' => 2,
                'count' => 2,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 1,
            ],
        ]));
        $this->assertSame('', $this->Paginator->numbers());
        $this->assertSame('', $this->Paginator->first());
        $this->assertSame('', $this->Paginator->last());
    }

    /**
     * test the numbers() method when there is only one page
     *
     * @return void
     */
    public function testWithZeroPages()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => 0,
                'current' => 0,
                'count' => 0,
                'perPage' => 10,
                'prevPage' => false,
                'nextPage' => false,
                'pageCount' => 0,
                'limit' => 10,
                'start' => 0,
                'end' => 0,
            ],
        ]));

        $result = $this->Paginator->counter('pages');
        $expected = '0 of 1';
        $this->assertSame($expected, $result);
    }

    /**
     * Test data for meta()
     *
     * @return array
     */
    public function dataMetaProvider()
    {
        return [
            // Verifies that no next and prev links are created for single page results.
            [1, false, false, 1, [], ''],
            // Verifies that first and last pages are created for single page results.
            [1, false, false, 1, ['first' => true, 'last' => true], '<link href="http://localhost/?foo=bar" rel="first"/>' .
                '<link href="http://localhost/?foo=bar" rel="last"/>'],
            // Verifies that first page is created for single page results.
            [1, false, false, 1, ['first' => true], '<link href="http://localhost/?foo=bar" rel="first"/>'],
            // Verifies that last page is created for single page results.
            [1, false, false, 1, ['last' => true], '<link href="http://localhost/?foo=bar" rel="last"/>'],
            // Verifies that page 1 only has a next link.
            [1, false, true, 2, [], '<link href="http://localhost/?foo=bar&amp;page=2" rel="next"/>'],
            // Verifies that page 1 only has next, first and last link.
            [1, false, true, 2, ['first' => true, 'last' => true], '<link href="http://localhost/?foo=bar&amp;page=2" rel="next"/>' .
                '<link href="http://localhost/?foo=bar" rel="first"/>' .
                '<link href="http://localhost/?foo=bar&amp;page=2" rel="last"/>'],
            // Verifies that page 1 only has next and first link.
            [1, false, true, 2, ['first' => true], '<link href="http://localhost/?foo=bar&amp;page=2" rel="next"/>' .
                '<link href="http://localhost/?foo=bar" rel="first"/>'],
            // Verifies that page 1 only has next and last link.
            [1, false, true, 2, ['last' => true], '<link href="http://localhost/?foo=bar&amp;page=2" rel="next"/>' .
                '<link href="http://localhost/?foo=bar&amp;page=2" rel="last"/>'],
            // Verifies that the last page only has a prev link.
            [2, true, false, 2, [], '<link href="http://localhost/?foo=bar" rel="prev"/>'],
            // Verifies that the last page only has a prev, first and last link.
            [2, true, false, 2, ['first' => true, 'last' => true], '<link href="http://localhost/?foo=bar" rel="prev"/>' .
                '<link href="http://localhost/?foo=bar" rel="first"/>' .
                '<link href="http://localhost/?foo=bar&amp;page=2" rel="last"/>'],
            // Verifies that a page in the middle has both links.
            [5, true, true, 10, [], '<link href="http://localhost/?foo=bar&amp;page=4" rel="prev"/>' .
                '<link href="http://localhost/?foo=bar&amp;page=6" rel="next"/>'],
            // Verifies that a page in the middle has both links.
            [5, true, true, 10, ['first' => true, 'last' => true], '<link href="http://localhost/?foo=bar&amp;page=4" rel="prev"/>' .
                '<link href="http://localhost/?foo=bar&amp;page=6" rel="next"/>' .
                '<link href="http://localhost/?foo=bar" rel="first"/>' .
                '<link href="http://localhost/?foo=bar&amp;page=10" rel="last"/>'],
        ];
    }

    /**
     * @param int $page
     * @param int $prevPage
     * @param int $nextPage
     * @param int $pageCount
     * @param array $options
     * @param string $expected
     * @dataProvider dataMetaProvider
     */
    public function testMeta($page, $prevPage, $nextPage, $pageCount, $options, $expected)
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Article' => [
                'page' => $page,
                'prevPage' => $prevPage,
                'nextPage' => $nextPage,
                'pageCount' => $pageCount,
            ],
        ]));

        $this->Paginator->options(['url' => ['?' => ['foo' => 'bar']]]);

        $result = $this->Paginator->meta($options);
        $this->assertSame($expected, $result);

        $this->assertSame('', $this->View->fetch('meta'));

        $result = $this->Paginator->meta($options + ['block' => true]);
        $this->assertNull($result);

        $this->assertSame($expected, $this->View->fetch('meta'));
    }

    /**
     * test the limitControl() method
     *
     * @return void
     */
    public function testLimitControl()
    {
        $out = $this->Paginator->limitControl([1 => 1]);
        $expected = [
            ['form' => ['method' => 'get', 'accept-charset' => 'utf-8', 'action' => '/']],
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'limit']],
            'View',
            '/label',
            ['select' => ['name' => 'limit', 'id' => 'limit', 'onChange' => 'this.form.submit()']],
            ['option' => ['value' => '1']],
            '1',
            '/option',
            '/select',
            '/div',
            '/form',
        ];
        $this->assertHtml($expected, $out);

        $out = $this->Paginator->limitControl([1 => 1, 5 => 5], null, ['class' => 'form-control']);
        $expected = [
            ['form' => ['method' => 'get', 'accept-charset' => 'utf-8', 'action' => '/']],
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'limit']],
            'View',
            '/label',
            ['select' => ['name' => 'limit', 'id' => 'limit', 'onChange' => 'this.form.submit()', 'class' => 'form-control']],
            ['option' => ['value' => '1']],
            '1',
            '/option',
            ['option' => ['value' => '5']],
            '5',
            '/option',
            '/select',
            '/div',
            '/form',
        ];
        $this->assertHtml($expected, $out);

        $out = $this->Paginator->limitControl([], null, ['class' => 'form-control']);
        $expected = [
            ['form' => ['method' => 'get', 'accept-charset' => 'utf-8', 'action' => '/']],
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'limit']],
            'View',
            '/label',
            ['select' => ['name' => 'limit', 'id' => 'limit', 'onChange' => 'this.form.submit()', 'class' => 'form-control']],
            ['option' => ['value' => '20']],
            '20',
            '/option',
            ['option' => ['value' => '50']],
            '50',
            '/option',
            ['option' => ['value' => '100']],
            '100',
            '/option',
            '/select',
            '/div',
            '/form',
        ];
        $this->assertHtml($expected, $out);

        $out = $this->Paginator->limitControl();
        $expected = [
            ['form' => ['method' => 'get', 'accept-charset' => 'utf-8', 'action' => '/']],
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'limit']],
            'View',
            '/label',
            ['select' => ['name' => 'limit', 'id' => 'limit', 'onChange' => 'this.form.submit()']],
            ['option' => ['value' => '20']],
            '20',
            '/option',
            ['option' => ['value' => '50']],
            '50',
            '/option',
            ['option' => ['value' => '100']],
            '100',
            '/option',
            '/select',
            '/div',
            '/form',
        ];
        $this->assertHtml($expected, $out);
    }

    /**
     * test the limitControl() method with defaults and query
     *
     * @return void
     */
    public function testLimitControlQuery()
    {
        $out = $this->Paginator->limitControl([], 50);
        $expected = [
            ['form' => ['method' => 'get', 'accept-charset' => 'utf-8', 'action' => '/']],
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'limit']],
            'View',
            '/label',
            ['select' => ['name' => 'limit', 'id' => 'limit', 'onChange' => 'this.form.submit()']],
            ['option' => ['value' => '20']],
            '20',
            '/option',
            ['option' => ['value' => '50', 'selected' => 'selected']],
            '50',
            '/option',
            ['option' => ['value' => '100']],
            '100',
            '/option',
            '/select',
            '/div',
            '/form',
        ];
        $this->assertHtml($expected, $out);

        $this->View->setRequest($this->View->getRequest()->withQueryParams(['limit' => '100']));
        $out = $this->Paginator->limitControl([], 50);
        $expected = [
            ['form' => ['method' => 'get', 'accept-charset' => 'utf-8', 'action' => '/']],
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'limit']],
            'View',
            '/label',
            ['select' => ['name' => 'limit', 'id' => 'limit', 'onChange' => 'this.form.submit()']],
            ['option' => ['value' => '20']],
            '20',
            '/option',
            ['option' => ['value' => '50']],
            '50',
            '/option',
            ['option' => ['value' => '100', 'selected' => 'selected']],
            '100',
            '/option',
            '/select',
            '/div',
            '/form',
        ];
        $this->assertHtml($expected, $out);
    }

    /**
     * Test using paging params set by SimplePaginator which doesn't do count query.
     *
     * @return void
     */
    public function testMethodsWhenThereIsNoPageCount()
    {
        $request = new ServerRequest([
            'url' => '/',
        ]);
        $request = $request->withAttribute('paging', [
            'Article' => [
                'page' => 1,
                'current' => 9,
                'count' => null,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 0,
                'start' => 1,
                'end' => 9,
                'sort' => null,
                'direction' => null,
                'limit' => null,
            ],
        ]);

        $view = new View($request);
        $paginator = new PaginatorHelper($view);

        $result = $paginator->first();
        $this->assertSame('', $result);

        $result = $paginator->last();
        $this->assertSame('', $result);

        // Using below methods when SimplePaginator is used makes no practical sense.
        // The asserts are just to ensure they return a reasonable value.

        $result = $paginator->numbers();
        $this->assertSame('', $result);

        $result = $paginator->hasNext();
        $this->assertTrue($result);

        $result = $paginator->counter();
        // counter() sets `pageCount` to 1 if empty.
        $this->assertSame('1 of 1', $result);

        $result = $paginator->total();
        $this->assertSame(0, $result);
    }

    protected function setPagingParams($params, bool $merge = true)
    {
        if ($merge) {
            $params = Hash::merge($this->View->getRequest()->getAttribute('paging'), $params);
        }
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', $params));
    }
}
