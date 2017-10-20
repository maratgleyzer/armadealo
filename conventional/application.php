<?php

class ZfApplication
{
    /**
     * The environment state of your current application
     *
     * @var string
     */
    protected $_environment;

    /**
     * Sets the environment to load from configuration file
     *
     * @param string $environment - The environment to set
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->_environment = $environment;
    }

    /**
     * Returns the environment which is currently set
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->_environment;
    }

    /**
     * Convenience method to bootstrap the application
     *
     * @return mixed
     */
    public function bootstrap()
    {
        if (!$this->_environment) {
            throw new Exception('Please set the environment using ::setEnvironment');
        }

        $frontController = $this->initialize();

        $this->setupRoutes($frontController);
        $response = $this->dispatch($frontController);

        $this->render($response);
    }

    /**
     * Initialization stage, loads configration files, sets up includes paths
     * and instantiazed the frontController
     *
     * @return Zend_Controller_Front
     */
    public function initialize()
    {
        // Set the include path
        set_include_path(
              dirname(__FILE__) . '/library'
            . PATH_SEPARATOR
            . get_include_path()
        );

        /* Zend_View */
        require_once 'Zend/View.php';

        /* Zend_Registry */
        require_once 'Zend/Registry.php';

        /* Zend_Config_ini */
        require_once 'Zend/Config/Ini.php';

        /* Zend_Controller_Front */
        require_once 'Zend/Controller/Front.php';

        /* Zend_Controller_Router_Rewrite */
        require_once 'Zend/Controller/Router/Rewrite.php';

        /* Zend_Controller_Action_HelperBroker */
        require_once 'Zend/Controller/Action/HelperBroker.php';

        /* Zend_Controller_Action_Helper_ViewRenderer */
        require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';

        /*
         * Load the given stage from our configuration file,
         * and store it into the registry for later usage.
         */
        $config = new Zend_Config_Ini(dirname(__FILE__) . '/app/etc/config.ini', $this->getEnvironment());
        Zend_Registry::set('config', $config);

        /*
         * Create a *custom* view object with modified paths,
         * and store it into the registry for later usage.
         */
        $view = new Zend_View();
        $view->setScriptPath(dirname(__FILE__) . '/app/views/scripts');
        $view->setHelperPath(dirname(__FILE__) . '/app/views/helpers');
        Zend_Registry::set('view', $view);

        // Add the custom view object to the ViewRenderer
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        /*
         * Create an instance of the frontcontroller, and point it to our
         * controller directory
         */
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->throwExceptions((bool) $config->mvc->exceptions);
        $frontController->setControllerDirectory(dirname(__FILE__) . '/app/controllers');

        return $frontController;
    }

    /**
     * Sets up the custom routes
     *
     * @param  object Zend_Controller_Front $frontController - The frontcontroller
     * @return object Zend_Controller_Router_Rewrite
     */
    public function setupRoutes(Zend_Controller_Front $frontController)
    {
        // Retrieve the router from the frontcontroller
        $router = $frontController->getRouter();

        /*
         * You can add routes here like so:
         * $router->addRoute(
         *    'home',
         *    new Zend_Controller_Router_Route('home', array(
         *        'controller' => 'index',
         *        'action'     => 'index'
         *    ))
         * );
         */

        return $router;
    }

    /**
     * Dispatches the request
     *
     * @param  object Zend_Controller_Front $frontController - The frontcontroller
     * @return object Zend_Controller_Response_Abstract
     */
    public function dispatch(Zend_Controller_Front $frontController)
    {
        // Return the response
        $frontController->returnResponse(true);
        return $frontController->dispatch();
    }

    /**
     * Renders the response
     *
     * @param  object Zend_Controller_Response_Abstract $response - The response object
     * @return void
     */
    public function render(Zend_Controller_Response_Abstract $response)
    {
        $response->sendHeaders();
        $response->outputBody();
    }
}
