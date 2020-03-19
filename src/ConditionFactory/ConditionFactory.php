<?php
/**
 * Abstract Factory for a condition.
 */
namespace AuraSidera\Sextant\ConditionFactory;

/**
 * Abstract Factory for a condition.
 *
 * A condition is a callable which accepts the following parameters:
 * - URL: request URL
 * - method: request method
 * - parameters: GET/POST parameters
 * - headers: HTTP headers
 *
 * and returns a boolean value telling whether the condition is satisfied. It
 * also accepts an array which will be filled with part of the URL matching
 * an expression (if the condition's nature supports it), as in a preg_match.
 */
interface ConditionFactory {
    /**
     * Returns a condition.
     *
     * @return callable Condition
     */
    public function __invoke(): callable;
}
