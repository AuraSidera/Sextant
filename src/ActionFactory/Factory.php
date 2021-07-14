<?php
/**
 * Factory for building action factories.
 */
namespace AuraSidera\Sextant\ActionFactory;

/**
 * Factory for building action factories.
 */
class Factory {
    /**
     * Returns a nothing action factory.
     * 
     * @return Nothing Action factory
     */
    public function createNothing(): Nothing {
        return new Nothing();
    }

    /**
     * Returns an if-then action factory.
     * 
     * @return IfThen Action factory
     */
    public function createIfThen(): IfThens {
        return new IfThen();
    }

    /**
     * Returns an if-then-else action factory.
     * 
     * @return IfThenElse Action factory
     */
    public function createIfThenElse(): IfThenElse {
        return new IfThenElse();
    }

    /**
     * Returns a sequential action factory.
     * 
     * @return Sequential Action factory
     */
    public function createSequential(): Sequential {
        return new Sequential();
    }

    /**
     * Returns a while-loop action factory.
     * 
     * @return WhileLoop Action factory
     */
    public function createWhileLoop(): WhileLoop {
        return new WhileLoop();
    }

    /**
     * Returns a controller action factory.
     * 
     * @param string $namespace Namespace prefix
     * @return Controller Action factory
     */
    public function createController(string $namespace): Controller {
        return new Controller($namespace);
    }
    /**
     * Returns a file-not-found action factory.
     * 
     * @return NotFound Action factory
     */
    public function createNotFound(): NotFound {
        return new NotFound();
    }

    /**
     * Returns a state dump action factory.
     * 
     * @return Dump Action factory
     */
    public function createDump(): Dump {
        return new Dump();
    }

    /**
     * Returns a text action factory.
     * 
     * @return Text Action factory
     */
    public function createText(): Text {
        return new Text();
    }

    /**
     * Returns a JSON action factory.
     * 
     * @return Json Action factory
     */
    public function createJson(): Json {
        return new Json();
    }

    /**
     * Returns a file action factory.
     * 
     * @return File Action factory
     */
    public function createFile(): File {
        return new File();
    }

    /**
     * Returns a script action factory.
     * 
     * @return Script Action factory
     */
    public function createScript(): Script {
        return new Script();
    }
}