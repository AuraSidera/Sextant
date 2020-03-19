<?php
/**
 * Does nothing.
 */
namespace Aura\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * Does nothing.
 */
class Nothing implements ActionFactory {
    /**
     * Returns an action which does nothing.
     *
     * @return callable Action doing nothing
     */
    public function __invoke(): callable {
        return function () {};
    }
}
