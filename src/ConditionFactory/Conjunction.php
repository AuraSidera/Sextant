<?php
/**
 * Conjunction of conditions.
 */
namespace AuraSidera\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Conjunction of conditions.
 */
class Conjunction implements ConditionFactory {
    /**
     * Returns a condiction which is satisfied when every subject is satisfied.
     *
     * @param callable $subjects list of subjects
     * @return callable Conjunction of conditions
     */
    public function __invoke(callable ...$subjects): callable {
        return function(
            string $url = '',
            string $method = '',
            array $parameters = [],
            array $headers = [],
            array &$matches = []
        ) use ($subjects): bool {
            foreach ($subjects as $subject) {
                if (!$subject($url, $method, $parameters, $headers, $matches)) {
                    return false;
                }
            }
            return true;
        };
    }
}
