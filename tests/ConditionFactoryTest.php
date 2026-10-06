<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\ConditionFactory\Factory;
use AuraSidera\Sextant\ConditionFactory\Unsatisfiable;
use AuraSidera\Sextant\ConditionFactory\UrlPattern;
use AuraSidera\Sextant\State;

final class ConditionFactoryTest extends TestCase {
    public function testUnsatisfiable() {
        $unsatisfiable = new Unsatisfiable();
        $condition = $unsatisfiable();
        $this->assertFalse($condition($this->initState('/')));
    }

    public function testFactoryCreatesEveryConditionFactory() {
        $factory = new Factory();
        foreach ([
            'createAlways', 'createUnsatisfiable', 'createNegation',
            'createConjunction', 'createDisjunction', 'createMethod',
            'createUrl', 'createUrlPattern', 'createPcre', 'createSimple'
        ] as $method) {
            $this->assertIsCallable($factory->$method());
        }
    }

    public function testCreateNeverIsAliasOfCreateUnsatisfiable() {
        $factory = new Factory();
        $this->assertInstanceOf(Unsatisfiable::class, $factory->createNever());
    }

    public function testTypePattern() {
        $this->assertEquals('\d+', UrlPattern::typePattern('number'));
        $this->assertEquals('[^\/]+', UrlPattern::typePattern());
        $this->assertEquals('[^\/]+', UrlPattern::typePattern(null));
        $this->assertEquals('[^\/]+', UrlPattern::typePattern('unknown'));
    }

    public function testUrlPattern() {
        $url_pattern = new UrlPattern();
        $condition = $url_pattern('users/{id:number}/{name}');
        $state = $this->initState('users/42/John%20Doe');
        $this->assertTrue($condition($state));
        $this->assertEquals(
            ['id' => '42', 'name' => 'John Doe'],
            $state->getMatchesAsDictionary()
        );
        $this->assertFalse($condition($this->initState('users/abc/John')));
    }

    private function initState(string $url): State {
        return new State($url, 'GET', [], [], [], []);
    }
}
