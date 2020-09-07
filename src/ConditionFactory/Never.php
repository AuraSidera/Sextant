<?php
/**
 * A condition which is never satisfied.
 */
namespace AuraSidera\Sextant\ConditionFactory;

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
