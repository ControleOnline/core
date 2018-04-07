<?php

namespace Core\Helper;

use Core\Helper\Format;
use Translate\Model\TranslateModel;
use Company\Model\CompanyModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Model\ViewModel;

class Mail {

    /**
     * @var \Translate\Model\TranslateModel
     */
    protected static $_translateModel;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected static $_serviceLocator;

    public static function initialize(\Zend\ServiceManager\ServiceManager $serviceLocator) {
        if (!self::$_translateModel) {
            self::$_serviceLocator = $serviceLocator;
            self::$_translateModel = new TranslateModel();
            self::$_translateModel->initialize($serviceLocator);
            self::$_translateModel->setEntity('Core\Entity\Translate');
        }
    }

    public static function renderMail(\Zend\ServiceManager\ServiceManager $serviceLocator, $template, array $params = array()) {
        self::initialize($serviceLocator);
        $view = new PhpRenderer();
        $resolver = new TemplateMapResolver();
        $resolver->setMap(array('mailTemplate' => __DIR__ . '/../../../view/mail/' . $template));
        $view->setHelperPluginManager(self::$_serviceLocator->get('Zend\View\Renderer\RendererInterface')->getHelperPluginManager());
        $view->setResolver($resolver);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('mailTemplate')->setVariables($params);
        return $view->render($viewModel);
    }

}
