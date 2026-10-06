<?php
/**
 * A condition which is never satisfied.
 */
namespace AuraSidera\Sextant\ConditionFactory;

/**
 * A condition which is never satisfied.
 */
class Unsatisfiable implements ConditionFactoryInterface {
    /**
     * Returns a condition which is never satisfied.
     *
     * @return callable A condition which is never satisfied
     */
    public function __invoke(): callable {
        return function(): bool {
            return false;
        };
    }
}
