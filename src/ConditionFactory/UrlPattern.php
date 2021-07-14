<?php
/**
 * Matches an URL pattern.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;
use \Exception;

/**
 * Matches an URL pattern.
 *
 * URL patterns can contain extact text to match or placeholders enclosed
 * in brackets like {id}: those will be returned in the matches array, using
 * the placeholder as key and the matching portion of URL as value.
 *
 * Placeholders can be decorated with a type: {id:number}, which restricts
 * the possible matched to string corresponding to appropriate type.
 * Supported types are:
 * - number: natural numbers
 * - string: anything but slashes
 * - date: dates in YYYY-MM-DD format
 * - time: time in hh:mm:ss format
 * - datetime: date and time in YYYY-MM-DD hh:mm:ss format
 */
class UrlPattern implements ConditionFactoryInterface {
    /**
     * Returns PCRE matching given type.
     *
     * @param string $type_name Name of the type
     * @return string PCRE expression matching given type
     */
    public static function typePattern(string $type_name = null) {
        $types = [
            'number' => '\d+',
            'string' => '[^\/]+',
            'date' => '\d{4}-\d{1,2}-\d{1,2}',
            'time' => '\d{1,2}:\d{2}:\d{2}',
            'datetime' => '\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{2}:\d{2}'
        ];

        return (array_key_exists($type_name, $types)) ? $types[$type_name] : $types['string'];
    }

    /**
     * Returns a condition satisfied when URL matches a pattern.
     *
     * Also populates matches array.
     *
     * @param string $url_pattern Pattern to match
     * @return Condition matching URL pattern and method
     */
    public function __invoke(string $url_pattern = ''): callable {
        $url_pattern = '/^' . str_replace('/', '\/', $url_pattern) . '$/';
        $processed_pattern = $url_pattern;

        // Finds placeholders
        $result = preg_match_all('/{((\w+)(:(\w+))?)}/', $url_pattern, $matches);
        if ($result === false) {
            throw new Exception('Error while processing pattern "'. $url_pattern . '".');
        }

        // Replaces placeholders
        $placeholders = array_key_exists(2, $matches) ? $matches[2] : [];
        foreach (array_keys($placeholders) as $index) {
            $processed_pattern = str_replace(
                $matches[0][$index],
                '(' . self::typePattern($matches[4][$index]) . ')',
                $processed_pattern
            );
        }

        // Returns closure
        return function(State $state) use ($processed_pattern, $placeholders): bool {
            $local_matches = [];
            $result = preg_match($processed_pattern, $state->getUrl(), $local_matches);
            if ($result === false) {
                throw new Exception('Error while trying to match "'. $state->getUrl() . '" againts pattern "' . $processed_pattern . '".');
            }

            for ($i = 1; $i < \count($local_matches); ++$i) {
                $state->addMatch($placeholders[$i - 1], $local_matches[$i]);
            }

            return $result === 1;
        };
    }
}
