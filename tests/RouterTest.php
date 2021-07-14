<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\Router;
use AuraSidera\Sextant\State;

final class RouterTest extends TestCase {
    public function testAdd() {
        $router = Router::fromDefaultAction(function ($state) { $state->spy = true; });
        $state = State::fromDefault();
        $router->match($state);
        $this->assertTrue($state->spy);
    }

    public function testMatch() {
        $router = Router::fromDefaultAction(function ($state) { $state->spy = false; });
        $state = State::fromDefault();
        $router->add(
            function ($state) { return true; },
            function ($state) { $state->spy = true; } 
        );
        $router->match($state);
        $this->assertTrue($state->spy);
    }
}
