<?php
/**
 * Sequential composition of actions.
 */
namespace AuraSidera\Sextant\ActionFactory;

require_once __DIR__ . '/ActionFactory.php';

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
        return function (
            array &$matches = [],
            array &$parameters = [],
            array &$headers = [],
            string &$url = '',
            string &$method = '',
            &$status = null
        ) use ($subjects) {
            foreach ($subjects as $subject) {
                $subject($matches, $parameters, $headers, $url, $method, $status);
            }
        };
    }
}
