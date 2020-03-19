<?php
/**
 * Sets a file not found header.
 */
namespace AuraSidera\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * Sets a file not found header.
 */
class NotFound implements ActionFactory {
    /**
     * Returns an action which sets a file not found header.
     *
     * @return callable Action setting a 404 header
     */
    public function __invoke(): callable {
        return function () {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
        };
    }
}