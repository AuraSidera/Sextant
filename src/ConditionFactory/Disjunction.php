<?php
/**
 * Disjunction of conditions.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;

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
        return function(State $state) use ($subjects): bool {
            foreach ($subjects as $subject) {
                if ($subject($state)) {
                    return true;
                }
            }
            return false;
        };
    }
}
