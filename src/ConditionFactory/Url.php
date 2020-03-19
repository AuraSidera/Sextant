<?php
/**
 * Matches an URL.
 */
namespace AuraSidera\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Matches an URL.
 */
class Url implements ConditionFactory {
    /**
     * Returns a condition matching an URL.
     *
     * @param string $condition_url URL to match
     * @return callable Condition matching an URL
     */
    public function __invoke(string $condition_url = ''): callable {
        return function(string $url = '') use ($condition_url): bool {
            return $condition_url === $url;
        };
    }
}
