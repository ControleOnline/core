<?php

namespace Core\Helper;

use Company\Model\CompanyModel;


class Cron {

    /**
     * @var \Zend\ServiceManager\ServiceManager $_serviceLocator
     */
    protected static $_serviceLocator;

    /**
     * @var \Doctrine\ORM\EntityManager $_em
     */
    protected static $_em;
    protected static $_companyModel;

    public function initialize(\Zend\ServiceManager\ServiceManager $serviceLocator) {
        if (!self::$_serviceLocator) {
            self::$_serviceLocator = $serviceLocator;
            self::$_em = $serviceLocator->get('\Doctrine\ORM\EntityManager');
            self::$_companyModel = new CompanyModel();
            self::$_companyModel->initialize(self::$_serviceLocator);
        }
    }

    public static function run() {
        if (self::$_companyModel->getDefaultCompany() && self::$_companyModel->getLoggedPeopleCompany() && self::$_companyModel->getLoggedPeopleCompany()->getId() == self::$_companyModel->getDefaultCompany()->getId()) {
            $orderModel = new \Sales\Model\OrderModel();
            $orderModel->initialize(self::$_serviceLocator);
            $orderModel->closeBillingInvoice();
            $orderModel->generateInvoiceTax();
            $orderModel->generateBillingInvoice();
            $orderModel->generatePurchasingInvoice();
            $orderModel->clearSaleOrders();
            $orderModel->clearOrderInvoice();
            $orderModel->selectFirstQuote();
            
            $financeModel = new \Finance\Model\FinanceModel();
            $financeModel->initialize(self::$_serviceLocator);
            $financeModel->cancelInvoices();
        }
    }

}
