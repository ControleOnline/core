<?php

namespace Core\Helper;

use Core\Helper\Config;
use Menu\Model\MenuModel;

class View {

    public static function setDefaultVariables($controller, \Zend\ServiceManager\ServiceManager $serviceLocator) {

        $controller->_view->default_user_image_profile = '/assets/img/default/profile.png';
        $controller->_view->default_company_logo = '/assets/img/default/logo.png';
        $controller->_view->module_name = $controller->_module_name;
        $controller->_view->action_name = $controller->_action_name;
        $controller->_view->config = new Config();
        $controller->_view->_menuModel = new MenuModel();
        $controller->_view->_menuModel->initialize($serviceLocator);

        if (class_exists('\User\Model\UserModel')) {
            $userModel = new \User\Model\UserModel();
            $userModel->initialize($serviceLocator);
            $controller->_view->user = $userModel->getLoggedUser();
            $controller->_view->userPeople = $userModel->getLoggedUserPeople();
            $controller->_view->userModel = $userModel;
        }
        $controller->_module_name = in_array(ucfirst($controller->_module_name), array('Company', 'Carrier', 'Client', 'Provider')) ? $controller->_module_name : 'Company';
        $model = '\\' . ucfirst($controller->_module_name) . '\\Model\\' . ucfirst($controller->_module_name) . 'Model';
        if (class_exists($model)) {
            $controller->_companyModel = new $model();
            $controller->_companyModel->initialize($serviceLocator);
            if (get_class($controller) != 'stdClass') {
                $company_id = $controller->params()->fromQuery('company_id') ? : $controller->params()->fromQuery($controller->_module_name);
            } else {
                $company_id = $controller->_event->getRequest()->getQuery()->get('company_id');
            }
            $controller->_companyModel->setCompanyId($company_id);
            $controller->_view->companyModel = $controller->_companyModel;
            $controller->_view->defaultCompany = $controller->_companyModel->getDefaultCompany();
            $controller->_view->userCompany = $controller->_companyModel->getCurrentPeopleCompany();
        }
        return $controller;
    }

    public static function redirectToLogin($renderer, $response, $request, $redirect, $referrer = true, $module = 'user') {
        //$module = strtolower(substr($request->getParam('controller'), 1, strpos($request->getParam('controller'), '\\', 1) - 1))?:'user';        
        //echo $module;
        //die();
        $url = $request->getUri()->getPath();
        $params = $request->getUri()->getQueryAsArray() ? : array();
        $redirect->toUrl($renderer->basePath('/' . $module . '/login/' . ($referrer && $url != '/' . $module . '/logout' ? '?login-referrer=' . $url . ($params ? rawurlencode('?') . rawurlencode('&' . http_build_query($params)) : '') : '')));
        $response->sendHeaders();
        exit;
    }

    public static function getAnalytics() {
        return Config::getConfig('analytics') ? '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');
	ga(\'create\', \'' . Config::getConfig('analytics') . '\', \'auto\');
	ga(\'send\', \'pageview\');' : NULL;
    }

    public static function getMautic() {
        return NULL;
        return Config::getConfig('mautic-url') ? '(function(w,d,t,u,n,a,m){w[\'MauticTrackingObject\']=n;
        w[n]=w[n]||function(){(w[n].q=w[n].q||[]).push(arguments)},a=d.createElement(t),
        m=d.getElementsByTagName(t)[0];a.async=1;a.src=u;m.parentNode.insertBefore(a,m)
    })(window,document,\'script\',\'' . Config::getConfig('mautic-url') . '/mtc.js\',\'mt\');
    mt(\'send\', \'pageview\');' : NULL;
    }

}
