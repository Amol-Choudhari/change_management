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
namespace Cake\Database\Expression;

/**
 * Contains the field property with a getter and a setter for it
 */
trait FieldTrait
{
    /**
     * The field name or expression to be used in the left hand side of the operator
     *
     * @var string|array|\Cake\Database\ExpressionInterface
     */
    protected $_field;

    /**
     * Sets the field name
     *
     * @param string|array|\Cake\Database\ExpressionInterface $field The field to compare with.
     * @return void
     */
    public function setField($field): void
    {
        $this->_field = $field;
    }

    /**
     * Returns the field name
     *
     * @return string|array|\Cake\Database\ExpressionInterface
     */
    public function getField()
    {
        return $this->_field;
    }
}
