<?php
/**
 * Automatic controller loading.
 */
namespace AuraSidera\Sextant\ActionFactory;
use \AuraSidera\Sextant\State;

class Controller implements ActionFactoryInterface {
    /** string Namespace prefix for controller */
    private $namespace;

    /**
     * Constructor.
     * 
     * @param string $namespace Namespace prefix for constructor
     */
    public function __construct(string $namespace) {
        $this->namespace = $namespace;
    }

    /**
     * Returns an action which invokes a controller's method on the current state.
     * 
     * @param string $controller_name Name of controller
     * @param string $method_name Name of method
     * @return callable Action calling a controller
     */
    public function __invoke(
        string $controller_name = null,
        string $method_name = null
    ): callable {
        $full_name = $this->namespace . '\\' . $controller_name;
        return function (State $state) use ($full_name, $method_name) {
            $controller = new $full_name();
            if (!is_null($method_name)) {
                $controller->$method_name($state);
                return;
            }
            $controller($state);
        };
    }
}
