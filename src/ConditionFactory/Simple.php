<?php
/**
 * Matches an URL pattern and a method.
 */
namespace Aura\Sextant\ConditionFactory;

require_once __DIR__ . '/UrlPattern.php';
require_once __DIR__ . '/Method.php';

/**
 * Matches an URL pattern and a method.
 */
class Simple implements ConditionFactory {
    /** UrlPattern URL pattern condition factory. */
    private $url_pattern;

    /** Method Method condition factory. */
    private $method;


    /** Default constructor. */
    public function __construct() {
        $this->url_pattern = new UrlPattern();
        $this->method = new Method();
    }


    /**
     * Returns a condition satisfied when URL matches a pattern for a specific method.
     *
     * Also populates matches array.
     *
     * @param string $method Method to match
     * @param string $url_pattern Pattern to match
     * @return Condition matching URL pattern and method
     */
    public function __invoke(string $method = '', string $url_pattern = ''): callable {
        $url_pattern_factory = $this->url_pattern;
        $url_condition = $url_pattern_factory($url_pattern);

        $method_factory = $this->method;
        $method_condition = $method_factory($method);

        return function (
            string $url = '',
            string $method = '',
            array $parameters = [],
            array $headers = [],
            array &$matches = []
        ) use ($url_condition, $method_condition): bool {
            return $url_condition($url, $method, $parameters, $headers, $matches)
                && $method_condition($url, $method);
        };
    }
}
