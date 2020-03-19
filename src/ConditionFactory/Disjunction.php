<?php
/**
 * Disjunction of conditions.
 */
namespace Aura\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Disjunction of conditions.
 */
class Disjunction implements ConditionFactory {
    /**
     * Returns a condition which is satisfied when at least one subject is satisfied.
     *
     * @param callable $subjects List of subjects
     * @return Disjunction condition
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
                if ($subject($url, $method, $parameters, $headers, $matches)) {
                    return true;
                }
            }
            return false;
        };
    }
}
