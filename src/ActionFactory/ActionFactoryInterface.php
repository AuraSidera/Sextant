<?php
/**
 * Abstract factory for actions.
 */
namespace AuraSidera\Sextant\ActionFactory;

/**
 * Abstract factory for actions.
 *
 * An action is a callable which accepts the following parameters:
 * - matches: array of matches against parts of the URL
 * - parameters: GET/POST parameters
 * - headers: HTTP headers
 * - URL: requested URL
 * - method: HTTP method
 * 
 * and returns nothing.
 */
interface ActionFactoryInterface
 {
    /**
     * Returns an action.
     *
     * @return callable Action
     */
    public function __invoke(): callable;
}
