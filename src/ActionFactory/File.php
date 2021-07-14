<?php
/**
 * Shows file contents as action.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \Exception;

/**
 * Shows file contents as action.
 */
class File implements ActionFactoryInterface {
    /**
     * Returns an action outputting the content of given file.
     *
     * @param string $file_path Path to file
     * @param string $header Optional header to set (default: none)
     * @return callable Action outputting content of a file
     * @throws Exception If file is not readable
     */
    public function __invoke(string $file_path = '', string $header = null): callable {
        if (!file_exists($file_path) || !is_readable($file_path)) {
            throw new Exception('Cannot access "' . $file_path . '".\n');
        }

        return function () use ($file_path, $header) {
            if (!empty($header)) {
                header($header);
            }
            echo file_get_contents($file_path);
        };
    }
}
