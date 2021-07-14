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
        callable $default_action
    ) {
        $this->routes = $routes;
        $this->default_action = $default_action;
    }

    /**
     * Static factory method.
     * 
     * @param callable $condition_factory Default condition factory to use
     * @param callable $action_factory Default action factory to use
     * @param callable $default_action Default action if no route matches
     */
    public static function fromDefaultAction(callable $default_action): self {
        return new Router([], $default_action);
    }

    /**
     * Declares a route.
     *
     * @param $condition Condition for the route
     * @param $action Action to take
     * @return self This router
     */
    public function add($condition, $action): self {
        $this->routes[] = [
            'condition' => $condition,
            'action' => $action
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
