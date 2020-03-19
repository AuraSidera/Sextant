<?php
/**
 * Non-intrusive routing library for PHP.
 */
namespace AuraSidera\Sextant;

require_once __DIR__ . '/ConditionFactory/Always.php';
require_once __DIR__ . '/ActionFactory/Nothing.php';

/**
 * Router.
 *
 * Sets routes and dispatches incoming requests. Every route is a pair 
 * (condition, action): given action is taken whenever condition is satisfied.
 * Routes conditions are tested in the same order they are declared.
 */
class Router {
    /** array List of declared routes. */
    private $routes;

    /** callable Action to take when no other route matches. */
    private $deafult_action;

    /** callable Default condition factory to use when a proper condition is not provied. */
    private $default_condition_factory;

    /** callable Default action factory to use when a proper action is not provided. */
    private $default_action_factory;


    /** Default constructor. */
    public function __construct() {
        $this->routes = [];
        $this->default_action = null;
        $this->default_condition_factory = null;
        $this->default_action_factory = null;
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
        if (!is_null($this->default_condition_factory)) {
            return call_user_func_array(
                $this->default_condition_factory,
                is_array($condition) ? $condition : $condition
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
        if (!is_null($this->default_action_factory)) {
            return call_user_func_array(
                $this->default_action_factory,
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
    public function setDefaultConditionFactory(callable $condition_factory = null): self {
        $this->default_condition_factory = $condition_factory;

        return $this;
    }


    /**
     * Sets default action factory.
     *
     * @param callable $action_factory Action factory
     * @return self This router
     */
    public function setDefaultActionFactory(callable $action_factory = null): self {
        $this->default_action_factory = $action_factory;

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
            'action' => $action = $this->getAction($action)
        ];

        return $this;
    }


    /**
     * Tests routes and takes appropriate action.
     *
     * @param string $url URL (default: requested URI)
     * @param string $method Request method (default: requested HTTP method)
     * @param array $parameters Parameters (default: HTTP GET/POST parameters)
     * @param array $headers (default: request headers)
     * @return self This router
     */
    public function match(
        string $url = null,
        string $method = null,
        array $parameters = null,
        array $headers = null
    ): self {
        // Initializes parameters
        if (is_null($url)) {
            $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        }
        if (is_null($method)) {
            $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        }
        if (is_null($parameters)) {
            $parameters = isset($_REQUEST) ? $_REQUEST : [];
        }
        if (is_null($headers)) {
            $headers = (function_exists('getallheaders') && getallheaders() !== false) ? getallheaders() : [];
        }

        // Tests every route
        foreach ($this->routes as $route) {
            $matches = [];
            if ($route['condition']($url, $method, $parameters, $headers, $matches)) {
                $route['action']($matches, $parameters, $headers, $url, $method);
                return $this;
            }
        }

        $default_action = $this->getAction($this->default_action);
        $default_action([], $parameters, $headers, $url, $method);

        return $this;
    }
}
