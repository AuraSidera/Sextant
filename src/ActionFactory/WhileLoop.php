<?php
/**
 * While loop of actions.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \AuraSidera\Sextant\State;

/**
 * While loop of actions.
 */
class WhileLoop implements ActionFactoryInterface {
    /**
     * Returns an action which is iterated as long as a condition is true.
     *
     * @param callable $condition Permanence codnition
     * @param callable $action Action to iterate
     * @return callable While loop of action
     */
    public function __invoke(
        callable $condition = null,
        callable $action = null
    ): callable {
        return function (State $state) use ($condition, $action) {
            while (
                !is_null($action)
             && (is_null($condition) || $condition($state))
            ) {
                $action($state);
            }
        };
    }
}
