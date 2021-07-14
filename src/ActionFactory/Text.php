<?php
/**
 * Outputs a string.
 */
namespace AuraSidera\Sextant\ActionFactory;

/**
 * Outputs a string.
 */
class Text implements ActionFactoryInterface {
    /**
     * Returns an action outputting a string.
     *
     * @param string $subject String to show
     * @return callable Action showing a string
     */
    public function __invoke(string $subject = ""): callable {
        return function () use ($subject) {
            echo $subject;
        };
    }
}
