<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\State;

final class StateTest extends TestCase {
    public function testGetUrl() {
        $state = $this->initState();
        $this->assertEquals('http://www.site.com', $state->getUrl());
    }

    public function testGetMethod() {
        $state = $this->initState();
        $this->assertEquals('GET', $state->getMethod());
    }

    public function testGetParameters() {
        $state = $this->initState();
        $this->assertEquals([
            'number' => 42,
            'string' => 'fourtytwo',
            'array' => ['a', 'b', 'c']
        ], $state->getParametersAsDictionary());
    }

    public function testGetHeaders() {
        $state = $this->initState();
        $this->assertEquals([
            'X-customheader' => 42
        ], $state->getHeadersAsDictionary());
    }

    public function testGetMatchesAsDictionary() {
        $state = $this->initState();
        $this->assertEquals([], $state->getMatchesAsDictionary());
    }

    public function testAddMatch() {
        $state = $this->initState();
        $state->addMatch('name', 'value');
        $this->assertEquals(
            ['name' => 'value'],
            $state->getMatchesAsDictionary()
        );
    }

    public function testSetGet() {
        $state = $this->initState();
        $state->name = 'value';
        $this->assertEquals('value', $state->name);
    }

    public function testSetGetArray() {
        $state = $this->initState();
        $state['name'] = 'value';
        $this->assertEquals('value', $state['name']);
    }

    public function testIsset() {
        $state = $this->initState();
        $state->name = 'value';
        $this->assertTrue(isset($state->name));
    }

    public function testIssetArray() {
        $state = $this->initState();
        $state['name'] = 'value';
        $this->assertTrue(isset($state['name']));
    }

    public function testUnset() {
        $state = $this->initState();
        $state->name = 'value';
        unset($state->name);
        $this->assertFalse(isset($state->name));
    }

    public function testUnsetArray() {
        $state = $this->initState();
        $state['name'] = 'value';
        unset($state['name']);
        $this->assertFalse(isset($state['name']));
    }

    public function testGetStateFromDefault() {
        $_SERVER = [
            'REQUEST_URI' => 'http://www.site.com',
            'REQUEST_METHOD' => 'get',
            'HTTP_X-CustomHeader' => 42
        ];
        $_REQUEST = [
            'number' => 42,
            'string' => 'fourtytwo',
            'array' => ['a', 'b', 'c']
        ];
        $state = State::fromDefault();
        $this->assertEquals($this->initState(), $state);
    }


    private function initState() {
        return new State(
            'http://www.site.com',
            'GET',
            [
                'number' => 42,
                'string' => 'fourtytwo',
                'array' => ['a', 'b', 'c']
            ],
            [
                'X-customheader' => 42
            ],
            [],
            []
        );
    }
}
