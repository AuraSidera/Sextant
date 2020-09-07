<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\Router;
use AuraSidera\Sextant\State;

final class RouterTest extends TestCase {
    public function testSetDefaultAction() {
        $router = new Router();
        $router->setDefaultAction(function ($state) { $state->spy = true; });
        $state = State::getStateFromServer();
        $router->match($state);
        $this->assertTrue($state->spy);
    }

    public function testSetConditionFactory() {
        $router = new Router();
        $router->setConditionFactory(function () {
            return function ($state) {
                $state->spy = true;
                return true;
            };
        });
        $router->addRoute(null, null);
        $state = State::getStateFromServer();
        $router->match($state);
        $this->assertTrue($state->spy);
    }

    public function testSetActionFactory() {
        $router = new Router();
        $router->setActionFactory(function () {
            return function ($state) {
                $state->spy = true;
            };
        });
        $router->addRoute(null, null);
        $state = State::getStateFromServer();
        $router->match($state);
        $this->assertTrue($state->spy);
    }

    public function testAddRoute() {
        $this->testSetConditionFactory();
    }

    public function testMatch() {
        $this->testSetConditionFactory();
    }
}
