<?php

/**
 * Router class
 *
 * A front controller for handling requests.
 */
class Router
{
    /** @var array Stores the url segments */
    public static $path_segments = array();

    /** @var string Stores the controller name */
    public static $controller;

    /** @var string The method called on the controller */
    public static $action = 'index';

    /** @var array Any extra paramaters passed in the URL */
    public static $params = array();

    /**
     * Router constructor.
     *
     * Made private as part of singleton pattern.
     */
    private function __construct()
    {
        self::$path_segments = self::getUrlSegments();
        //Analyse the URL segments to establish controller, action, and any parameters

        $segments = self::$path_segments;
        self::$controller = array_shift($segments);
        self::$action = array_shift($segments);

        //Controller and action default to IndexController and Index
        if (!self::$controller) {
            self::$controller = 'index';
        }
        if (!self::$action) {
            self::$action = 'index';
        }
        //determine URL parameters from remaining segments
        $params = array();
        if (count($segments)) {
            parse_str(implode('=', $segments), $params);
        }
        self::$params = $params;
    }

    /**
     * Creates a router instance and returns it.
     *
     * @return object An insteance of Router class
     */
    public static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            return new Router();
        }
        return $instance;
    }

    /**
     * Parses the url and invokes the nescessary controller
     */
    public function dispatch()
    {
        //Determine the controller file name. Controllers are stored in controllers subdirectory.
        $controller_name = ucfirst(self::$controller);
        $controller_file = BASE_DIR.'controllers/'.$controller_name.'.php';

        //if the controller does not exist send a 404 (send404() terminates script execution.)
        if (!is_file($controller_file)) {
            self::send404();
        }

        //The controller file exists and if it contains the class named after it, we instantiate it.
        require_once $controller_file;
        if (!class_exists($controller_name)) {
            self::send404();
        }
        $controller = new $controller_name();

        //check that the controller does not require authentication
        if ($controller->require_auth && !isset($_SESSION['user'])) {
            self::send404();
        }

        //check that the instantiated controller has the specified method, and call it if so.
        if (method_exists($controller, self::$action)) {
            call_user_func(array(&$controller, self::$action));
            $controller->controller_name = $controller_name;
            $controller->viewVars['logged_in'] = isset($_SESSION['user']);
            $controller->viewVars['user'] = isset($_SESSION['user']) ? $_SESSION['user'] : array();
            $controller->renderView();
        } else {
            self::send404();
        }
    }

    /**
     * sends a 404 error and terminates
     */
    public static function send404()
    {
        header('HTTP/1.0 404 not found');
        echo "404 Page Not Found";
        die();
    }

    /**
     * Parses the URL into segments
     *
     * @return array An array of URL segments
     */
    private function getUrlSegments()
    {
        /**
         * Break the request URI into segments so that we can determine the
         * controller/action/params
         **/

        //generate an array of URL parts, minus anything after ?
        $uri = explode('?', trim($_SERVER['REQUEST_URI']));
        $uri = explode('/', trim(array_shift($uri)));
        $segments = array();

        //remove blank segments from the array - (occurs when there are multiple double slashes in URL)
        foreach ($uri as $k => $segment) {
            if (!strlen(trim($segment))) {
                continue;
            }
            $segments[] = strtolower(trim($segment));
        }
        //remove the base url from the segment list
        if (trim(BASE_URL, '/') == $segments[0]) {
            array_shift($segments);
        }

        return $segments;
    }
}
