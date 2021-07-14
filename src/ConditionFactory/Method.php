<?php
/**
 * Matches a method.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;

/**
 * Matches a method.
 */
class Method implements ConditionFactoryInterface {
    /**
     * Returns a condition matching a method.
     *
     * @param string $condition_method Method to match (default: GET)
     * @return callable Condition matching a method
     */
    public function __invoke(string $method = 'GET'): callable {
        return function(State $state) use ($method): bool {
            return $method === $state->getMethod();
        };
    }
}
