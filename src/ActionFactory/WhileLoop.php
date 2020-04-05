<?php
/**
 * While loop of actions.
 */
namespace AuraSidera\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * While loop of actions.
 */
class WhileLoop implements ActionFactory {
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
        return function (
            array &$matches = [],
            array &$parameters = [],
            array &$headers = [],
            string &$url = '',
            string &$method = '',
            &$status = null
        ) use ($condition, $action) {
            while (
                !is_null($action)
             && (is_null($condition) || $condition($matches, $parameters, $headers, $url, $method, $status))
            ) {
                $action($matches, $parameters, $headers, $url, $method, $status);
            }
        };
    }
}
