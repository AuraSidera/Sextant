<?php
/**
 * Condition based on a PCRE on URL.
 */
namespace AuraSidera\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Condition based on a PCRE on URL.
 */
class Pcre implements ConditionFactory {
    /**
     * Returns a condition which is satisfied if and only if URL matches a given pattern.
     *
     * The array of matches will be populated with the result of the match, as in preg_match.
     *
     * @param string $url_pattern Pattern to match
     * @return Condition based on a PCRE match
     */
    public function __invoke(string $url_pattern = ''): callable {
        return function(
            string $url = '',
            string $method = '',
            array $parameters = [],
            array $headers = [],
            array &$matches = []
        ) use ($url_pattern): bool {
            $result = preg_match($url_pattern, $url, $matches);
            if ($result === false) {
                throw new \Exception('Error while trying to match "'. $url . '" againts pattern "' . $url_pattern . '".');
            }

            return $result === 1;
        };
    }
}
