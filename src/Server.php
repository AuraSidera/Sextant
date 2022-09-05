<?php
/**
 * Web server.
 */
namespace AuraSidera\Sextant;

/**
 * Helper class representing the web server.
 *
 * @note Most methods rely on the superglobal $_SERVER variable and will
 *       gracefully fall back to neutral values if not available.
 */
class Server {
    /**
     * Static factory method.
     * 
     * @return Server Server
     */
    public static function fromDefault(): self {
        return new Server();
    }
    
    /**
     * Returns URL.
     *
     * @return string URL
     */
     public function getUrl(): string {
        return isset($_SERVER['REQUEST_URI'])
            ? strtok($_SERVER['REQUEST_URI'], '?')
            : '';
    }

    /**
     * Returns method.
     *
     * @return string HTTP method
     */
    public function getMethod(): string {
        return isset($_SERVER['REQUEST_METHOD'])
            ? $_SERVER['REQUEST_METHOD']
            : '';
    }

    /**
     * Returns parameters.
     *
     * @return array Parameters as dictionary
     */
    public function getParameters(): array {
        $parameters = isset($_REQUEST) ? $_REQUEST : [];
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $body_parameters = [];
            $content_type = strtolower($_SERVER['CONTENT_TYPE']);
            if (strpos($content_type, 'application/json') === 0) {
                $body_parameters = json_decode(file_get_contents("php://input"), true);
		if (is_null($body_parameters)) {
                    $body_parameters = [];
                }
            }
            elseif (strpos($content_type, 'application/x-www-form-urlencoded') === 0) {
                parse_str(file_get_contents("php://input"), $body_parameters);
            }
            $parameters = array_merge($parameters, $body_parameters);
        }
        return $parameters;
    }

    /**
     * Returns headers.
     *
     * @return array Headers as dictionary
     */
    public function getHeaders(): array {
        $headers = [];
        if (function_exists('getallheaders') && getallheaders() !== false) {
            $headers = getallheaders();
        }
        elseif (isset($_SERVER)) {
            $headers = [];
            foreach($_SERVER as $name => $value) {
                if ($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
                    $headers[$name] = $value;
                }
            }
        }
        return $headers;
    }
}
