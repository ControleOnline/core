<?php

namespace Core\Helper;

class View {

    public static function setDefaultVariables($view, \Zend\ServiceManager\ServiceManager $serviceLocator) {

        $view->default_user_image_profile = '/assets/img/default/profile.png';
        $view->default_company_logo = '/assets/img/default/logo.png';

        if (class_exists('\User\Model\UserModel')) {
            $userModel = new \User\Model\UserModel();
            $userModel->initialize($serviceLocator);
            $view->user = $userModel->getLoggedUser();
            $view->userPeople = $userModel->getLoggedUserPeople();
            $view->userModel = $userModel;
        }
        if (class_exists('\Company\Model\CompanyModel')) {
            $companyModel = new \Company\Model\CompanyModel();
            $companyModel->initialize($serviceLocator);
            $view->defaultCompany = $companyModel->getDefaultCompany();
            $view->userCompany = $companyModel->getLoggedPeopleCompany();
        }
    }

}
