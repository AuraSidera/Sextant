<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\SmartRouter as Router;
use AuraSidera\Sextant\State;

final class SmartRouterTest extends TestCase {
    public function testAdd() {
        $router = Router::fromFactories(
            function ($string) { return function ($state) use($string) {
                return $state->string === $string;
            }; },
            function ($string) { return function ($state) use($string) {
                $state->output = $string;
            }; },
            function ($state) { $state->spy = false; }
        );
        $state = State::fromDefault();
        $state->string = 'string';
        $router->add('string', 'output');
        $router->match($state);
        $this->assertEquals('output', $state->output);
    }
}
