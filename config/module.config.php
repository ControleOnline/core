<?php

return array(
    'view_helpers' => array(
        'invokables' => array(
            'render_entity_form' => 'Core\Helper\RenderEntityForm'
        ),
    ),
    'LazyLoadImages' => array(
        'LazyLoadImages' => true,
        'LazyLoadClass' => 'lazy-load',
        'LazyLoadPlaceHolder' => 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
        'LazyLoadOnlyOnNoScript' => array('itemprop'),
        'LazyLoadExcludeTags' => array('script', 'noscript', 'textarea')
    ),
    'view' => array(
        'terminal_sufix' => array(
            '.html'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Core\Storage\SessionStorage' => 'Core\Factory\SessionFactory',
            'Core\Controller\Default' => 'Core\Factory\DefaultControllerFactory',
            'Core\Model\Default' => 'Core\Factory\DefaultModelFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Core\Controller\Default' => 'Core\Controller\DefaultController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Home\Controller\Default',
                        'action' => 'index',
                    ),
                ),
            ),
            'default' => array(
                'type' => 'Core\Core',
                'options' => array(
                    'route' => '/[:module][/:controller[/:action]]',
                    'constraints' => array(
                        'module' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'discoveryModule' => 'Core',
                        'module' => 'Home',
                        'controller' => 'Default',
                        'action' => 'index',
                        'base_url' => 'api',
                    ),
                ),
            )
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'Entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Entity' => 'Entity'
                ),
            ),
        ),
    )
);
