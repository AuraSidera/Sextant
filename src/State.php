<?php
/**
 * Surrent state.
 */
namespace AuraSidera\Sextant;
use \ArrayAccess;

/**
 * Current state.
 *
 * Describes the current state of the system. State include the (immutable) URL,
 * method, parameters and headers, as well as a (mutable) collection of named
 * entities.
 *
 * Collection of named entities can be transparently accessed through member
 * access syntax ($state->some_named_entity) or array access syntax
 * ($state['some_named_entity']).
 */
class State implements ArrayAccess {
    /** string Requested URL. */
    private $url;

    /** string Requested method. */
    private $method;

    /** array Associative array of parameters. */
    private $parameters;

    /** array Associative array of headers. */
    private $headers;

    /** array Associative array of matches. */
    private $matches;

    /** array Associative array of named entities. */
    private $data;

    /**
     * Default constructor.
     *
     * @param string $url URL
     * @param string $method Request method
     * @param array $parameters Parameters
     * @param array $headers Headers
     * @param array $matches Dictionary of matches
     * @param array $data Dictionary of named entities
     */
    public function __construct(
        string $url,
        string $method,
        array $parameters,
        array $headers,
        array $matches,
        array $data
    ) {
        $this->url = $url;
        $this->method = strtoupper($method);
        $this->parameters = $parameters;
        $this->headers = $headers;
        $this->matches = $matches;
        $this->data = $data;
    }

    /**
     * Builds state from server.
     *
     * Reads URL, method, parameters and headers from server information. All
     * of them can be overwritten by passing a non null parameter.
     *
     * @param Server Server
     * @return State State
     */
    public static function fromServer(Server $server): State {
        return new State(
            $server->getUrl(),
            $server->getMethod(),
            $server->getParameters(),
            $server->getHeaders(),
            [],
            []
        );
    }

    /**
     * Static factory method.
     * 
     * Reads information from a default server.
     * 
     * @return State State
     */
    public static function fromDefault(): State {
        return self::fromServer(Server::fromDefault());
    }

    /**
     * Returns URL.
     *
     * @return string URL
     */
    public function getUrL(): string {
        return $this->url;
    }

    /**
     * Returns method.
     *
     * @return string HTTP Method
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * Returns associative array of parameters.
     *
     * @return array Parameters
     */
    public function getParametersAsDictionary(): array {
        return $this->parameters;
    }

    /**
     * Returns headers as associative array.
     *
     * @return array Headers
     */
    public function getHeadersAsDictionary(): array {
        return $this->headers;
    }

    /**
     * Returns matches as associative array.
     *
     * @return array Matching parts of URL
     */
    public function getMatchesAsDictionary(): array {
        return $this->matches;
    }

    /**
     * Returns associative array of named entities.
     *
     * @return array Named entities as dictionary
     */
    public function getNamedEntitiesAsDictionary(): array {
        return $this->data;
    }

    /**
     * Adds a matching URL parameter.
     *
     * @param string $name Name of parameter
     * @param mixed $value Value
     * @return self This state
     */
    public function addMatch(string $name, $value): self {
        $this->matches[$name] = $value;
        return $this;
    }

    /**
     * Tells whether a named entity is defined.
     *
     * @param  mixed $offset Name
     * @return bool True if and only if entity is defined
     */
    public function offsetExists($offset): bool {
        return isset($this->data[$offset]);
    }

    /**
     * Tells whether a named entity is defined.
     *
     * @param  string $name Name
     * @return bool True if and only if entity is defined
     */
    public function __isset(string $name): bool {
        return isset($this->data[$name]);
    }

    /**
     * Returns value of a named entity.
     *
     * @note Any undefined entity has a null value
     * @param mixed $offset Name
     */
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Returns value of a named entity.
     *
     * @note Any undefined entity has a null value
     * @param string $name Name
     */
    public function __get(string $name) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Sets value of a named entity.
     *
     * @param mixed $offset Name
     * @param mixed $value Value
     */
    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    /**
     * Sets value of a named entity.
     *
     * @param string $name Name
     * @param mixed $value Value
     */
    public function __set(string $name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Deletes a named entity
     *
     * @param mixed $offset Name
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    /**
     * Deletes a named entity
     *
     * @param string $name Name
     */
    public function __unset(string $name) {
        unset($this->data[$name]);
    }
}
