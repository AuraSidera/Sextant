<?php
/**
 * Conditional branching.
 */
namespace AuraSidera\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

/**
 * Conditional branching.
 */
class IfThenElse implements ActionFactory {
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
        return function (
            array &$matches = [],
            array &$parameters = [],
            array &$headers = [],
            string &$url = '',
            string &$method = '',
            &$status = null
        ) use ($condition, $then, $else) {
            if (is_null($condition) || $condition($matches, $parameters, $headers, $url, $method, $status)) {
                if (!is_null($then)) {
                    $then($matches, $parameters, $headers, $url, $method, $status);
                }
            }
            elseif (!is_null($then)) {
                $else($matches, $parameters, $headers, $url, $method, $status);
            }
        };
    }
}
