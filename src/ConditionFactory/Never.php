<?php
/**
 * A condition which is never satisfied.
 */
namespace Aura\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * A condition which is never satisfied.
 */
class Never implements ConditionFactory {
    /**
     * Returns a condition which is never satisfied.
     * 
     * @return A condition which is never satisfied
     */
    public function __invoke(): callable {
        return function(): bool {
            return false;
        };
    }
}
