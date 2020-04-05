<?php
/**
 * Conditional branching.
 */
namespace AuraSidera\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

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
        return function (
            array &$matches = [],
            array &$parameters = [],
            array &$headers = [],
            string &$url = '',
            string &$method = '',
            &$status = null
        ) use ($condition, $then) {
            if (!is_null($then) && (is_null($condition) || $condition($matches, $parameters, $headers, $url, $method, $status))) {
                $then($matches, $parameters, $headers, $url, $method, $status);
            }
        };
    }
}
