<?php
/**
 * Executes a script.
 */
namespace Aura\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * Executes a script.
 */
class Script implements ActionFactory {
    /**
     * Returns an action executing a script.
     *
     * @param string $file_path Path to the script
     * @return callable Action executing a script
     * @throw \Exception If script file is not readable
     */
    public function __invoke(string $file_path = ''): callable {
        if (!file_exists($file_path) || !is_readable($file_path)) {
            throw new \Exception('Cannot access "' . $file_path . '".\n');
        }

        return function (
            array $matches = [],
            array $parameters = [],
            array $headers = [],
            string $url = '',
            string $method = ''
        ) use ($file_path) {
            include $file_path;
        };
    }
}
