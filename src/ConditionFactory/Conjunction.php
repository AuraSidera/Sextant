<?php
/**
 * Conjunction of conditions.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;

/**
 * Conjunction of conditions.
 */
class Conjunction implements ConditionFactoryInterface {
    /**
     * Returns a condiction which is satisfied when every subject is satisfied.
     *
     * @param callable $subjects list of subjects
     * @return callable Conjunction of conditions
     */
    public function __invoke(callable ...$subjects): callable {
        return function(State $state) use ($subjects): bool {
            foreach ($subjects as $subject) {
                if (!$subject($state)) {
                    return false;
                }
            }
            return true;
        };
    }
}
