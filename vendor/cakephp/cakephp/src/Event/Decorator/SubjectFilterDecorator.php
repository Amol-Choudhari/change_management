<?php
declare(strict_types=1);

/**
 * CakePHP : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP Project
 * @since         3.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Event\Decorator;

use Cake\Core\Exception\CakeException;
use Cake\Event\EventInterface;
use RuntimeException;

/**
 * Event Subject Filter Decorator
 *
 * Use this decorator to allow your event listener to only
 * be invoked if event subject matches the `allowedSubject` option.
 *
 * The `allowedSubject` option can be a list of class names, if you want
 * to check multiple classes.
 */
class SubjectFilterDecorator extends AbstractDecorator
{
    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        $args = func_get_args();
        if (!$this->canTrigger($args[0])) {
            return false;
        }

        return $this->_call($args);
    }

    /**
     * Checks if the event is triggered for this listener.
     *
     * @param \Cake\Event\EventInterface $event Event object.
     * @return bool
     */
    public function canTrigger(EventInterface $event): bool
    {
        if (!isset($this->_options['allowedSubject'])) {
            throw new RuntimeException(self::class . ' Missing subject filter options!');
        }
        if (is_string($this->_options['allowedSubject'])) {
            $this->_options['allowedSubject'] = [$this->_options['allowedSubject']];
        }

        try {
            $subject = $event->getSubject();
        } catch (CakeException $e) {
            return false;
        }

        return in_array(get_class($subject), $this->_options['allowedSubject'], true);
    }
}
