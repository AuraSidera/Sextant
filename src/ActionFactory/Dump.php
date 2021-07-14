<?php
/**
 * Dump of current status.
 */
namespace AuraSidera\Sextant\ActionFactory;

use \AuraSidera\Sextant\State;

/**
 * Dump of current status.
 */
class Dump implements ActionFactoryInterface {
    /**
     * Dumps current status.
     */
    public function __invoke(): callable {
        return function (State $state) {
            var_dump($state);
        };
    }
}
