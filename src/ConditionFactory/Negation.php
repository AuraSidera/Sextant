<?php
/**
 * Negation of a condition.
 */
namespace Aura\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Negation of a condition.
 */
class Negation implements ConditionFactory {
    /**
     * Returns a condition which is the negation of another condition.
     *
     * @param callable $subject Original condition
     * @return callable Negation of given condition
     */
    public function __invoke(callable $subject = null): callable {
        return function(
            string $url = '',
            string $method = '',
            array $parameters = [],
            array $headers = [],
            array &$matches = []
        ) use ($subject): bool {
            return (!is_null($subject))
                 ? !$subject($url, $method, $parameters, $headers, $matches)
                 : false;
        };
    }
}
