<?php

namespace Core\Helper;

class View {

    public static function setDefaultVariables($controller, \Zend\ServiceManager\ServiceManager $serviceLocator) {

        $controller->_view->default_user_image_profile = '/assets/img/default/profile.png';
        $controller->_view->default_company_logo = '/assets/img/default/logo.png';

        if (class_exists('\User\Model\UserModel')) {
            $userModel = new \User\Model\UserModel();
            $userModel->initialize($serviceLocator);
            $controller->_view->user = $userModel->getLoggedUser();
            $controller->_view->userPeople = $userModel->getLoggedUserPeople();
            $controller->_view->userModel = $userModel;
        }
        $controller->_module_name = in_array(ucfirst($controller->_module_name), array('Company', 'Carrier', 'Client')) ? $controller->_module_name : 'Company';
        $model = '\\' . ucfirst($controller->_module_name) . '\\Model\\' . ucfirst($controller->_module_name) . 'Model';
        if (class_exists($model)) {
            $controller->_companyModel = new $model();
            $controller->_companyModel->initialize($serviceLocator);
            if (get_class($controller) != 'stdClass') {
                $company_id = $controller->params()->fromQuery('company_id') ?: $controller->params()->fromQuery($controller->_module_name);
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

    public static function redirectToLogin($renderer, $response, $request, $redirect, $referrer = true) {
        $url = $request->getUri()->getPath();
        $params = $request->getUri()->getQueryAsArray() ?: array();
        $redirect->toUrl($renderer->basePath('/user/login/' . ($referrer && $url != '/user/logout' ? '?login-referrer=' . $url . $params ? rawurlencode('&' . http_build_query($params)) : '' : '')));
        $response->sendHeaders();
        exit;
    }

}
