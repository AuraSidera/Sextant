<?php
/**
 * Matches a method.
 */
namespace AuraSidera\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Matches a method.
 */
class Method implements ConditionFactory {
    /**
     * Returns a condition matching a method.
     *
     * @param string $condition_method Method to match (default: GET)
     * @return callable Condition matching a method
     */
    public function __invoke(string $condition_method = 'GET'): callable {
        return function(
            string $url = '',
            string $method = ''
        ) use ($condition_method): bool {
            return $condition_method === $method;
        };
    }
}
