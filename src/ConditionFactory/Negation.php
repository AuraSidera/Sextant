<?php
/**
 * Negation of a condition.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;

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
        return function(State $state) use ($subject): bool {
            return (!is_null($subject))
                 ? !$subject($state)
                 : false;
        };
    }
}
