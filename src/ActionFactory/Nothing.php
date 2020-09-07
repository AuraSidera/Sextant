<?php
/**
 * Does nothing.
 */
namespace AuraSidera\Sextant\ActionFactory;

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
