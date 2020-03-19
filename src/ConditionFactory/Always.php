<?php
/**
 * Always satisfied condition.
 */
namespace Aura\Sextant\ConditionFactory;

require_once __DIR__ . '/ConditionFactory.php';

/**
 * Always satisfied condition.
 */
class Always implements ConditionFactory {
    /**
     * Returns a condition which is always satisfied.
     *
     * @return callable Always satisfied condition
     */
    public function __invoke(): callable {
        return function(): bool {
            return true;
        };
    }
}
