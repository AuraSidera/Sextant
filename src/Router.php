<?php
/**
 * Non-intrusive routing library for PHP.
 */
namespace AuraSidera\Sextant;

/**
 * Router.
 *
 * Allows to declasre routes and dispatch incoming requests. Every route is a
 * pair (condition, action): given action is taken whenever condition is
 * satisfied. Routes conditions are tested in the same order they are declared.
 */
class Router {
    /** array List of declared routes. */
    private $routes;

    /** callable Action to take when no other route matches. */
    private $default_action;

    /** callable Default condition factory to use when a proper condition is not provied. */
    private $condition_factory;

    /** callable Default action factory to use when a proper action is not provided. */
    private $action_factory;


    /** Default constructor. */
    public function __construct() {
        $this->routes = [];
        $this->default_action = null;
        $this->condition_factory = null;
        $this->action_factory = null;
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
        $always = new ConditionFactory\Always();
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
        $nothing = new ActionFactory\Nothing();
        return $nothing();
    }


    /**
     * Sets action to take when no other route matches.
     *
     * @param mixed $default_action Action
     * @return self This router
     */
    public function setDefaultAction($default_action = null): self {
        $this->default_action = $this->getAction($default_action);

        return $this;
    }


    /**
     * Sets default condition factory.
     *
     * @param callable $condition_factory Condition factory
     * @return self This router
     */
    public function setConditionFactory(callable $condition_factory = null): self {
        $this->condition_factory = $condition_factory;

        return $this;
    }


    /**
     * Sets default action factory.
     *
     * @param callable $action_factory Action factory
     * @return self This router
     */
    public function setActionFactory(callable $action_factory = null): self {
        $this->action_factory = $action_factory;

        return $this;
    }


    /**
     * Declares a route.
     *
     * @param $condition Condition for the route
     * @param $action Action to take
     * @return self This router
     */
    public function addRoute($condition, $action): self {
        $this->routes[] = [
            'condition' => $this->getCondition($condition),
            'action' => $this->getAction($action)
        ];

        return $this;
    }


    /**
     * Tests routes and takes appropriate action.
     *
     * @param State $state Request state
     * @return self This router
     */
    public function match(State $state): self {
        foreach ($this->routes as $route) {
            if ($route['condition']($state)) {
                $route['action']($state);
                return $this;
            }
        }
        ($this->default_action)($state);
        return $this;
    }
}
