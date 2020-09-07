<?php
/**
 * Always satisfied condition.
 */
namespace AuraSidera\Sextant\ConditionFactory;

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
