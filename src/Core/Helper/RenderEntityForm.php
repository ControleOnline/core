<?php

namespace Core\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Core\Model\DefaultModel;

class RenderEntityForm extends AbstractHelper implements ServiceLocatorAwareInterface {

    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function __invoke($entity, $params = false) {
        $view = $this->getView();
        $e = explode('Core\\Entity\\', get_class($entity))[1];
        $default_model = new DefaultModel();
        $default_model->initialize($this->getServiceLocator()->getServiceLocator());
        $default_model->setConfig($this->getServiceLocator()->getServiceLocator()->get('Config'));
        $default_model->setEntity('Core\\Entity\\' . $e);
        $form = $default_model->form($e, $params);        
        $view->form = $form;
        return $view->render('Core/default/form.phtml');
    }

}
