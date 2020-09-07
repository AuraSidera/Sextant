<?php
/**
 * Sequential composition of actions.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \AuraSidera\Sextant\State;

/**
 * Sequential composition of actions.
 */
class Sequential implements ActionFactory {
    /**
     * Returns an action which is the sequential composition of given actions.
     *
     * @param callable $subjects List of actions
     * @return callable Sequence of actions
     */
    public function __invoke(callable ...$subjects): callable {
        return function (State $state) use ($subjects) {
            foreach ($subjects as $subject) {
                $subject($state);
            }
        };
    }
}
