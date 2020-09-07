<?php
/**
 * Condition based on a PCRE on URL.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;
use \Exception;

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
        return function(State $state) use ($url_pattern): bool {
            $matches = [];
            $result = preg_match($url_pattern, $state->getUrl(), $matches);
            if ($result === false) {
                throw new Exception('Error while trying to match "'. $state->getUrl() . '" againts pattern "' . $url_pattern . '".');
            }
            for ($i = 0; $i < \count($matches); ++$i) {
                $state->addMatch($i, $matches[$i]);
            }

            return $result === 1;
        };
    }
}
