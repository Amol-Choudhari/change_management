<?php
declare(strict_types=1);

/**
 * CakePHP : Rapid Development Framework (https://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc.
 * @link          https://cakephp.org CakePHP Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace TestApp\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * PaginatorPostsTable class
 */
class PaginatorPostsTable extends Table
{
    /**
     * initialize method
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('posts');
        $this->belongsTo('PaginatorAuthor', [
            'foreignKey' => 'author_id',
        ]);
    }

    /**
     * Finder method for find('popular');
     */
    public function findPopular(Query $query, array $options)
    {
        $field = $this->getAlias() . '.' . $this->getPrimaryKey();
        $query->where([$field . ' >' => '1']);

        return $query;
    }

    /**
     * Finder for published posts.
     */
    public function findPublished(Query $query, array $options)
    {
        $query->where(['published' => 'Y']);

        return $query;
    }

    /**
     * Custom finder, used with fixture data to ensure Paginator is sending options
     *
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findAuthor(Query $query, array $options = [])
    {
        if (isset($options['author_id'])) {
            $query->where(['PaginatorPosts.author_id' => $options['author_id']]);
        }

        return $query;
    }
}
