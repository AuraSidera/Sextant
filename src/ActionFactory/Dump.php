<?php
/**
 * Dump of current status.
 */
namespace AuraSidera\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * Dump of current status.
 */
class Dump implements ActionFactory {
    /**
     * Dumps current status.
     */
    public function __invoke(): callable {
        return function (
            array $matches = [],
            array $parameters = [],
            array $headers = [],
            string $url = '',
            string $method = '',
            $status = null
        ) {
            var_dump([
                'matches' => $matches,
                'parameters' => $parameters,
                'headers' => $headers,
                'url' => $url,
                'method' => $method,
                'status' => $status
            ]);
        };
    }
}
