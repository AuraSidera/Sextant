<?php
/**
 * Factory for building condition factories.
 */
namespace AuraSidera\Sextant\ConditionFactory;

/**
 * Factory for building condition factories.
 */
class Factory {
    /**
     * Returns an always action factory.
     * 
     * @return Always Action factory
     */
    public function createAlways(): Always {
        return new Always();
    }

    /**
     * Returns a never action factory.
     * 
     * @return Never Action factory
     */
    public function createNever(): Never {
        return new Never();
    }

    /**
     * Returns a negation action factory.
     * 
     * @return Negation Action factory
     */
    public function createNegation(): Negation {
        return new Negation();
    }

    /**
     * Returns a conjunction action factory.
     * 
     * @return Conjunction Action factory
     */
    public function createConjunction(): Conjunction {
        return new Conjunction();
    }

    /**
     * Returns a disjunction action factory.
     * 
     * @return Disjunction Action factory
     */
    public function createDisjunction(): Disjunction {
        return new Disjunction();
    }

    /**
     * Returns a method action factory.
     * 
     * @return Method Action factory
     */
    public function createMethod(): Method {
        return new Method();
    }

    /**
     * Returns an URL action factory.
     * 
     * @return Url Action factory
     */
    public function createUrl(): Url {
        return new Url();
    }

    /**
     * Returns an URL pattern action factory.
     * 
     * @return UrlPattern Action factory
     */
    public function createUrlPattern(): UrlPattern {
        return new UrlPattern();
    }

    /**
     * Returns a PCRE action factory.
     * 
     * @return Pcre Action factory
     */
    public function createPcre(): Pcre {
        return new Pcre();
    }

    /**
     * Returns a simple action factory.
     * 
     * @return Simple Action factory
     */
    public function createSimple(): Simple {
        return new Simple();
    }
}