<?php
/**
 * Non-intrusive routing library for PHP.
 */
namespace AuraSidera\Sextant;
use \AuraSidera\Sextant\ConditionFactory\Always;
use \AuraSidera\Sextant\ActionFactory\Nothing;

/**
 * Router.
 *
 * Allows to declare routes and dispatch incoming requests. Every route is a
 * pair (condition, action): given action is taken whenever condition is
 * satisfied. Routes conditions are tested in the same order they are declared.
 */
class SmartRouter extends Router {
    /** callable Default condition factory to use when a proper condition is not provied. */
    private $condition_factory;

    /** callable Default action factory to use when a proper action is not provided. */
    private $action_factory;

    /**
     * Constructor.
     * 
     * @param array $routes List of routes
     * @param callable $condition_factory Default condition factory to use
     * @param callable $action_factory Default action factory to use
     * @param callable $default_action Default action if no route matches
     */
    public function __construct(
        array $routes,
        callable $condition_factory,
        callable $action_factory,
        callable $default_action
    ) {
        parent::__construct($routes, $default_action);
        $this->condition_factory = $condition_factory;
        $this->action_factory = $action_factory;
    }

    /**
     * Static factory method.
     * 
     * @param callable $condition_factory Default condition factory to use
     * @param callable $action_factory Default action factory to use
     * @param callable $default_action Default action if no route matches
     * @return self A new smart router
     */
    public static function fromFactories(
        callable $condition_factory,
        callable $action_factory,
        callable $default_action
    ) {
        return new SmartRouter(
            [],
            $condition_factory,
            $action_factory,
            $default_action
        );
    }

    /**
     * Declares a route.
     * 
     * Uses default condition and action factories if given condition and
     * action are not callable.
     *
     * @param mixed $condition Condition for the route
     * @param mixed $action Action to take
     * @return self This router
     */
    public function add($condition, $action): self {
        parent::add($this->getCondition($condition), $this->getAction($action));
        return $this;
    }

    /**
     * Returns a proper condition.
     *
     * Returns given condition if already appropriate, otherwise applies
     * default condition builder if any, otherwise returns an always matching
     * condition.
     *
     * @param mixed $condition Condition to check
     * @return callable Proper condition
     */
    private function getCondition($condition): callable {
        // Returns given condition if callable
        if (is_callable($condition)) {
            return $condition;
        }

        // Applies default condition builder, if any
        if (!is_null($this->condition_factory)) {
            return call_user_func_array(
                $this->condition_factory,
                is_array($condition) ? $condition : [$condition]
            );
        }

        // No proper condition available: always matches
        $always = new Always();
        return $always();
    }

    /**
     * Returns a proper action.
     *
     * Returns given action if already appropriate, otherwise applies
     * default action builder if any, otherwise returns an empty action.
     *
     * @param mixed $action Action to check
     * @return callable Proper action
     */
    private function getAction($action): callable {
        // Returns given action if callable
        if (is_callable($action)) {
            return $action;
        }

        // Applies default action builder, if any
        if (!is_null($this->action_factory)) {
            return call_user_func_array(
                $this->action_factory,
                is_array($action) ? $action : [$action]
            );
        }

        // No proper action available: does nothing
        $nothing = new Nothing();
        return $nothing();
    }
}
