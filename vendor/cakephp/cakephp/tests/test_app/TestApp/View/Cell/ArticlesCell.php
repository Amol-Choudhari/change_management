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
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace TestApp\View\Cell;

/**
 * TagCloudCell class
 */
class ArticlesCell extends \Cake\View\Cell
{
    /**
     * valid cell options.
     *
     * @var array
     */
    protected $_validCellOptions = ['limit', 'page'];

    /**
     * Counter used to test the cache cell feature
     *
     * @var int
     */
    public $counter = 0;

    /**
     * Default cell action.
     *
     * @return void
     */
    public function display()
    {
    }

    /**
     * Renders articles in teaser view mode.
     *
     * @return void
     */
    public function teaserList()
    {
        $this->set('articles', [
            ['title' => 'Lorem ipsum', 'body' => 'dolorem sit amet'],
            ['title' => 'Usectetur adipiscing eli', 'body' => 'tortor, in tincidunt sem dictum vel'],
            ['title' => 'Topis semper blandit eu non', 'body' => 'alvinar diam convallis non. Nullam pu'],
            ['title' => 'Suspendisse gravida neque', 'body' => 'pellentesque sed scelerisque libero'],
        ]);
    }

    /**
     * Renders a view using a different template than the action name
     * The template is set using the ViewBuilder bound to the Cell
     *
     * @return void
     */
    public function customTemplateViewBuilder()
    {
        $this->counter++;
        $this->viewBuilder()->setTemplate('alternate_teaser_list');
    }

    /**
     * Renders a template in a custom templatePath
     * The template is set using the ViewBuilder bound to the Cell
     *
     * @return void
     */
    public function customTemplatePath()
    {
        $this->viewBuilder()->setTemplatePath(static::TEMPLATE_FOLDER . '/Articles/Subdir');
    }

    /**
     * Simple echo.
     *
     * @param string $msg1
     * @param string $msg2
     * @return void
     */
    public function doEcho($msg1, $msg2)
    {
        $this->set('msg', $msg1 . $msg2);
    }
}
