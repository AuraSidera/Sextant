<?php
/**
 * Shows a JSON document.
 */
namespace AuraSidera\Sextant\ActionFactory;

/**
 * Shows a JSON document.
 */
class Json implements ActionFactory {
    /**
     * Returns an action outputting a JSON document.
     *
     * @param mixed $subject JSON object
     * @return callable Action outputting a JSON document
     */
    public function __invoke($subject = []): callable {
        return function () use ($subject, $set_header) {
            header('Content-Type: application/json;');
            echo json_encode($subject);
        };
    }
}
