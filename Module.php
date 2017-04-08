<?php

namespace Core;

use Zend\Mvc\ModuleRouteListener;
use Zend\Stdlib\ArrayUtils;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response;
use Zend\Json\Json;
use Core\Helper\Url;
use Core\Model\InstallModel;
use Zend\ModuleManager\ModuleEvent;
use Assets\Helper\Header;
use Core\Helper\Format;
use Core\Helper\View;
use User\Model\UserModel;

class Module {

    protected $sm;
    protected $config;
    protected $em;
    protected $controller;
    protected $module;
    protected $default_route;

    public function getDefaultConfig($config) {
        $config['DefaultModule'] = isset($config['DefaultModule']) ? $config['DefaultModule'] : 'Home';
        $config['DefaultController'] = isset($config['DefaultController']) ? $config['DefaultController'] : 'Default';
        $config['LogChanges'] = isset($config['LogChanges']) ? $config['LogChanges'] : true;
        $config['EntityPath'] = isset($config['EntityPath']) ? $config['EntityPath'] : getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        return $config;
    }

    public function onBootstrap(\Zend\Mvc\MvcEvent $e) {
        $this->sm = $e->getApplication()->getServiceManager();
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $config = $this->sm->get('config');
        $storage = $e->getApplication()->getServiceManager()->get('Core\Storage\SessionStorage');
        $storage->setSessionStorage();
        $this->default_route = $config ['router']['routes']['default']['options']['defaults'];
        $this->config = $this->getDefaultConfig(
                (isset($config['Core']) ? $config['Core'] : array())
        );
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->setResponseType($e);
        if (isset($config['doctrine']['connection']['orm_default']['params'])) {
            $dbConfig = $config['doctrine']['connection']['orm_default']['params'];
            $entity = new DiscoveryEntity($this->em, $dbConfig, $this->config);
            $entity->checkEntities();
        }
        $this->configDefaultViewOptions($eventManager);
        $this->setViewTerminal($e, $config['view']['terminal_sufix']);

        $this->ckeckLogin($e);


        //$this->installEntities();                
    }

    private function ckeckLogin(\Zend\Mvc\MvcEvent $e) {
        $userModel = new UserModel();
        $userModel->initialize($e->getApplication()->getServiceManager());
        $uri = $e->getRequest()->getUri()->getPath();        
        
        $verify = preg_grep('/^' . \addcslashes('/user/login.json', '/') . '/i', array($uri));
        if (!$userModel->loggedIn() && \Core\Helper\Url::isRedirectUrl($uri) && !$verify) {
            $params = $e->getRequest()->getUri()->getQueryAsArray() ?: array();
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', '/user/login/' . ($uri != '/user/logout' ? '?login-referrer=' . $uri . ($params ? rawurlencode('&' . http_build_query($params)) : '') : ''));
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }

    private function setViewTerminal(\Zend\Mvc\MvcEvent $e, array $terminal_sufix = array('.html')) {
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $app = $e->getTarget();
        $uri = $e->getRequest()->getUri()->getPath();
        $extension = '.' . strtolower(pathinfo($uri, PATHINFO_EXTENSION));
        if (in_array($extension, $terminal_sufix)) {
            $sharedEvents = $e->getApplication()->getEventManager()->getSharedManager();
            $sharedEvents->attach($this->module, 'dispatch', function($e) {
                $result = $e->getResult();
                if ($result instanceof \Zend\View\Model\ViewModel) {
                    $result->setTerminal(true);
                }
            });
        } elseif ($extension != '.json') {
            $renderer = $e->getApplication()->getServiceManager()->get('\Zend\View\Renderer\RendererInterface');
            Header::init($renderer, $this->default_route, $uri);
            Header::addJsLibs('lazyLoad', 'controleonline-core-js/dist/js/LazyLoad.js');
            Header::addJsLibs('GMaps', 'controleonline-core-js/dist/js/GMaps.js');
            $viewModel->requireJsFiles = Header::getRequireJsFiles();
            $viewModel->requireJsLibs = Header::requireJsLibs();
            $viewModel->systemVersion = Header::getSystemVersion();

            $app->getEventManager()->attach('finish', array($this, 'lazyLoad'), 100);
        }
        $app->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, array($this, 'setDefaultVariables'), 100);
        $viewModel->setVariables(Format::returnData($viewModel->getVariables()));
    }

    public function setDefaultVariables(\Zend\Mvc\MvcEvent $e) {
        $controller = new \stdClass();
        $controller->_view = $e->getApplication()->getMvcEvent()->getViewModel();
        $controller->_module_name = strtolower(substr($e->getRouteMatch()->getParam('controller'), 1, strpos($e->getRouteMatch()->getParam('controller'), '\\', 1) - 1));
        $controller->_event = $e;
        View::setDefaultVariables($controller, $e->getApplication()->getServiceManager());
    }

    public function lazyLoad(\Zend\Mvc\MvcEvent $e) {
        $response = $e->getResponse();
        $this->sm = $e->getApplication()->getServiceManager();
        $config = $this->sm->get('config');
        $html = Helper\LazyLoad::imgLazyLoad($response->getBody(), $config['LazyLoadImages']);
        $response->setContent($html);
    }

    private function addDefaultTemplates($event, $baseDirs) {
        $sm = $event->getParam('application')->getServiceManager();
        $viewResolverMap = $sm->get('ViewTemplateMapResolver');
        $viewResolverPathStack = $sm->get('ViewTemplatePathStack');
        foreach ($baseDirs AS $baseDir) {
            if (is_file($baseDir . '/layout/layout.phtml')) {
                $viewResolverMap->add('layout/layout', $baseDir . '/layout/layout.phtml');
                $viewResolverMap->add('layout', $baseDir . '/layout/layout.phtml');
            }
            if (is_file($baseDir . '/error/404.phtml')) {
                $viewResolverMap->add('error/404', $baseDir . '/error/404.phtml');
                $viewResolverMap->add('404', $baseDir . '/error/404.phtml');
            }
            if (is_file($baseDir . '/error/index.phtml')) {
                $viewResolverMap->add('error/index', $baseDir . '/error/index.phtml');
                $viewResolverMap->add('error', $baseDir . '/error/index.phtml');
            }
            if ($baseDir) {
                $viewResolverPathStack->addPath($baseDir);
            }
            $viewResolverPathStack->addPath(__DIR__ . DIRECTORY_SEPARATOR . 'view');
        }
    }

    private function configDefaultViewOptions($eventManager) {
        $eventManager->attach(MvcEvent::EVENT_RENDER, function(MvcEvent $event) {
            $baseDirs[] = realpath($this->getModulePath($this->module) . DIRECTORY_SEPARATOR . 'view');
            $baseDirs[] = realpath($this->getModulePath($this->config['DefaultModule']) . DIRECTORY_SEPARATOR . 'view');
            $this->addDefaultTemplates($event, $baseDirs);
        }, 10);
    }

    public function init(\Zend\ModuleManager\ModuleManager $mm) {
        $sharedManager = $mm->getEventManager()->getSharedManager();
        $sharedManager->attach(get_class($mm), ModuleEvent::EVENT_LOAD_MODULES_POST, new Listener\ModuleLoaderListener(), 9000);
        $config = $this->getDefaultConfig($this->config);
        $uri = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
        if (isset($uri[0]) && isset($uri[1])) {
            $class = '\\' . ucfirst(Url::removeSufix($uri[0])) . '\\Controller\\' . ucfirst(Url::removeSufix($uri[1])) . 'Controller';
            $defaultClass = '\\' . ucfirst(Url::removeSufix($uri[0])) . '\\Controller\\' . $config['DefaultController'] . 'Controller';
            $this->module = ucfirst(Url::removeSufix($uri[0]));
            $this->controller = class_exists($class) ? $class : $defaultClass;
        } elseif (isset($uri[0])) {
            $controller = $config['DefaultController'];
            $class = '\\' . ucfirst(Url::removeSufix($uri[0])) . '\\Controller\\' . $controller . 'Controller';
            $this->module = ucfirst(Url::removeSufix($uri[0]));
            $this->controller = $class;
        } else {
            $module = $config['DefaultModule'];
            $controller = $config['DefaultController'];
            $class = '\\' . $module . '\\Controller\\' . $controller . 'Controller';
            $this->module = $module;
            $this->controller = $class;
        }
    }

    protected function getModulePath($module) {
        if (class_exists($module . '\Module')) {
            $reflector = new \ReflectionClass($module . '\Module');
            $fn = $reflector->getFileName();
            return dirname($fn) . DIRECTORY_SEPARATOR;
        }
        return dirname(__FILE__);
    }

    protected function installEntities() {
        $module = $this->module;
        $directory = realpath($this->getModulePath($module) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'Entity');

        if (is_dir($directory) || is_link($directory)) {
            $entities = glob($directory . DIRECTORY_SEPARATOR . '*.php');
            array_walk($entities, function (&$value) use (&$module) {
                $value = '' . $module . '\\Core\\Entity\\' . pathinfo($value)['filename'];
            });
            $install = new InstallModel($this->sm);
            $install->installEntity($entities);
        }
    }

    public function getControllerConfig() {
        return array(
            'invokables' => array(
                $this->controller => $this->controller
            )
        );
    }

    public function finishJsonStrategy(\Zend\Mvc\MvcEvent $e) {
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json; charset=utf-8');
        $response->setContent(Json::encode(Format::returnData($e->getResult()->getVariables()), true));
        $e->setResponse($response);
    }

    public function setResponseType(\Zend\Mvc\MvcEvent $e) {
        $this->verifyJsonStrategy($e);
    }

    public function verifyJsonStrategy(\Zend\Mvc\MvcEvent $e) {
        $request = $e->getRequest();
        $headers = $request->getHeaders();
        $uri = $request->getUri()->getPath();
        $compare = '.json';
        $is_json = substr_compare($uri, $compare, strlen($uri) - strlen($compare), strlen($compare)) === 0;
        if ($headers->has('accept') || $is_json) {
            $accept = $headers->get('accept');
            $match = $accept->match('application/json');
            if ($match && $match->getTypeString() != '*/*' || $is_json) {
                $e->getApplication()->getEventManager()->attach('render', array($this, 'registerJsonStrategy'), 100);
                $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_FINISH, array($this, 'finishJsonStrategy'));
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function registerJsonStrategy(\Zend\Mvc\MvcEvent $e) {
        $app = $e->getTarget();
        $locator = $app->getServiceManager();
        $view = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('ViewJsonStrategy');
        $view->getEventManager()->attach($jsonStrategy, 100);
    }

    public function getConfig() {
        $this->config = $this->getDefaultConfig(
                (isset($this->config['Core']) ? $this->config['Core'] : array())
        );
        $config = ArrayUtils::merge(array('Core' => $this->config), (include __DIR__ . '/config/module.config.php'));
        $config['doctrine']['driver']['Entity']['paths'][] = $this->config['EntityPath'];

        return $config;
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
