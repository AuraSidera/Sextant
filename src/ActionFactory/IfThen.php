<?php
/**
 * Conditional branching.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \AuraSidera\Sextant\State;

/**
 * Conditional branching.
 */
class IfThen implements ActionFactory {
    /**
     * Returns a conditional action.
     *
     * @param callable $condition Condition to be evaluated
     * @param callable $then Action to take if condition is ture
     * @return callable Conditional action
     */
    public function __invoke(
        callable $condition = null,
        callable $then = null
    ): callable {
        return function (State $state) use ($condition, $then) {
            if (!is_null($then) && (is_null($condition) || $condition($state))) {
                $then($state);
            }
        };
    }
}
