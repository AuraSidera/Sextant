<?php
/**
 * Sets a file not found header.
 */
namespace AuraSidera\Sextant\ActionFactory;

/**
 * Sets a file not found header.
 */
class NotFound implements ActionFactoryInterface {
    /**
     * Returns an action which sets a file not found header.
     *
     * @return callable Action setting a 404 header
     */
    public function __invoke(): callable {
        return function () {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        };
    }
}
