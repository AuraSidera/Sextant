<?php
/**
 * Shows a JSON document.
 */
namespace Aura\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * Shows a JSON document.
 */
class Json implements ActionFactory {
    /**
     * Returns an action outputting a JSON document.
     *
     * @param mixed $subject JSON object
     * @param bool $set_header Ssets the JSON content type header if true (default: true)
     * @return callable Action outputting a JSON document
     */
    public function __invoke($subject = [], bool $set_header = true): callable {
        return function () use ($subject, $set_header) {
            if ($set_heaeder) {
                header('Content-Type: application/json;');
            }

            echo json_encode($subject);
        };
    }
}
