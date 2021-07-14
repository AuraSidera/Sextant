<?php
/**
 * Matches an URL pattern and a method.
 */
namespace AuraSidera\Sextant\ConditionFactory;
use \AuraSidera\Sextant\State;

/**
 * Matches an URL pattern and a method.
 */
class Simple implements ConditionFactoryInterface {
    /** UrlPattern URL pattern condition factory. */
    private $url_pattern;

    /** Method Method condition factory. */
    private $method;


    /**
     * Constructor.
     * 
     * @param UrlPattern $url_pattern URL pattern factory
     * @param Method $method Method factory
     */
    public function __construct(
        UrlPattern $url_pattern,
        Method $method
    ) {
        $this->url_pattern = $url_pattern;
        $this->method = $method;
    }

    /**
     * Static factory method
     * 
     * @return self Simple factory
     */
    public static function fromDefault(): self {
        return newSimple(new UrlPattern(), new Method());
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

        return function (State $state) use ($url_condition, $method_condition): bool {
            return $url_condition($state) && $method_condition($state);
        };
    }
}
