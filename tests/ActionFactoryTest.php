<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\ActionFactory\Factory;
use AuraSidera\Sextant\ActionFactory\IfThen;
use AuraSidera\Sextant\ActionFactory\IfThenElse;
use AuraSidera\Sextant\State;

final class ActionFactoryTest extends TestCase {
    public function testFactoryCreatesEveryActionFactory() {
        $factory = new Factory();
        foreach ([
            'createNothing', 'createIfThen', 'createIfThenElse',
            'createSequential', 'createWhileLoop', 'createNotFound',
            'createDump', 'createText', 'createJson', 'createFile',
            'createScript'
        ] as $method) {
            $this->assertIsCallable($factory->$method());
        }
        $this->assertIsCallable($factory->createController('Some\\Namespace'));
    }

    public function testCreateIfThen() {
        $factory = new Factory();
        $this->assertInstanceOf(IfThen::class, $factory->createIfThen());
    }

    public function testIfThenElseTakesElseBranch() {
        $if_then_else = new IfThenElse();
        $action = $if_then_else(
            function () { return false; },
            function ($state) { $state->branch = 'then'; },
            function ($state) { $state->branch = 'else'; }
        );
        $state = $this->initState();
        $action($state);
        $this->assertEquals('else', $state->branch);
    }

    public function testIfThenElseWithoutElse() {
        $if_then_else = new IfThenElse();
        $action = $if_then_else(
            function () { return false; },
            function ($state) { $state->branch = 'then'; }
        );
        $state = $this->initState();
        $action($state);
        $this->assertNull($state->branch);
    }

    private function initState(): State {
        return new State('/', 'GET', [], [], [], []);
    }
}
