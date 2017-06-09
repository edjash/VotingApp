<?php

/**
 * Base class for controllers
 */
class Controller
{

    /** @var string The name of the controller, set by the Router. */
    public $controller_name;

    /** @var array An array of variables that is extracted in the View */
    public $viewVars = array();

    /**
     * Require user to be logged in order to access the route
     *
     * @var boolean
     */
    public $require_auth = true;

    /**
     * This method is called automatically by the Router after the controller is
     * invoked.
     *
     * @return void
     */
    public function renderView()
    {
        $view_file = BASE_DIR.'views/'.strtolower($this->controller_name).'.html';
        if (is_file($view_file)) {
            extract($this->viewVars);
            require $view_file;
        }
    }

    /**
     * utility function for child classes to determine if the request was a POST.
     *
     * @return boolean
     */
    public function isPost()
    {
        return (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST');
    }

    /**
     * Utility function for chlid classes to get an instance of any model.
     *
     * @param  string $name The name of the model (minus the 'Model' affix)
     * @return object|false Returns a model instance or false if the model doesn't exist.
     */
    public function getModel($name)
    {
        $model_file = BASE_DIR . 'models/' . $name . '.php';
        if (!is_file($model_file)) {
            return false;
        }
        require_once $model_file;

        $class = $name . 'Model';
        $model = new $class;
        return $model;
    }
}
