<?php
/**
 * Conditional branching.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \AuraSidera\Sextant\State;

/**
 * Conditional branching.
 */
class IfThenElse implements ActionFactoryInterface {
    /**
     * Returns a conditional action.
     *
     * @param callable $condition Condition to be evaluated
     * @param callable $then Action to take if condition is ture
     * @param callable $else Action to take if condition is false
     * @return callable Conditional action
     */
    public function __invoke(
        callable $condition = null,
        callable $then = null,
        callable $else = null
    ): callable {
        return function (State $state) use ($condition, $then, $else) {
            if (is_null($condition) || $condition($state)) {
                if (!is_null($then)) {
                    $then($state);
                }
            }
            elseif (!is_null($then)) {
                $else($state);
            }
        };
    }
}
