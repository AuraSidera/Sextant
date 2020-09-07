<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use AuraSidera\Sextant\Server;

final class ServerTest extends TestCase {
    public function testGetUrl() {
        $server = $this->init();
        $this->assertEquals('http://www.site.com', $server->getUrl());
    }

    public function testGetMethod() {
        $server = $this->init();
        $this->assertEquals('get', $server->getMethod());
    }

    public function testGetParameters() {
        $server = $this->init();
        $this->assertEquals([
            'number' => 42,
            'string' => 'fourtytwo',
            'array' => ['a', 'b', 'c']
        ], $server->getParameters());
    }

    public function testGetHeaders() {
        $server = $this->init();
        $this->assertEquals([
            'X-customheader' => 42
        ], $server->getHeaders());
    }

    private function init(): Server {
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
        return new Server();
    }
}
