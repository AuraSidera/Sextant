<?php
/**
 * Matches an URL.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;

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
        return function(State $state) use ($condition_url): bool {
            return $condition_url === $state->getUrl();
        };
    }
}
