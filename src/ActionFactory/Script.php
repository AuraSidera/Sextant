<?php
/**
 * Executes a script.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \Exception;

/**
 * Executes a script.
 */
class Script implements ActionFactoryInterface {
    /**
     * Returns an action executing a script.
     *
     * @param string $file_path Path to the script
     * @return callable Action executing a script
     * @throw \Exception If script file is not readable
     */
    public function __invoke(string $file_path = ''): callable {
        if (!file_exists($file_path) || !is_readable($file_path)) {
            throw new Exception('Cannot access "' . $file_path . '".\n');
        }

        return function () use ($file_path) {
            include $file_path;
        };
    }
}
