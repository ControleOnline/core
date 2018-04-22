<?php

namespace Core\Controller;

use Core\Controller\AbstractController;
use Core\Helper\Format;
use User\Model\UserModel;
use Assets\Helper\Header;
use Core\Model\ErrorModel;
use Core\Helper\Api;

class DefaultController extends AbstractController {

    public $login_view = 'user';
    public $create_account_view = 'user';
    public $profile_view = 'user';
    public $contact_request_view = 'user';
    protected $module_name;

    /**
     * @var \User\Model\UserModel
     */
    protected $_userModel;

    /**
     * @var \User\Model\PeopleModel
     */
    protected $_peopleModel;

    public function setConfig($config) {
        $this->_config = $config;
    }

    private function getForm($entity_id = null) {
        $return = [];
        $id = $entity_id ? : $this->params()->fromQuery('id');
        $this->_model->setViewMethod('form');
        $this->_model->setParam('id', $id);
        $this->_view->setTerminal(true);
        if ($this->_entity) {
            $return = $this->_model->discovery($this->_entity, null, true);
        }
        if ($this->_entity_children) {
            $return['id'] = $id;
            $return['children'] = $this->_model->discovery($this->_entity_children, $this->_entity, true);
        }
        $return['method'] = $id ? 'PUT' : 'POST';
        return $return;
    }

    private function alterData() {
        return Format::returnData($this->_model->discovery($this->_entity));
    }

    private function insertData() {
        return Format::returnData($this->_model->discovery($this->_entity));
    }

    private function getDataById($id) {
        if ($this->_entity_children) {
            $this->_model->setMethod('GET');
            $data = $this->_model->discovery($this->_entity_children, $this->_entity);
        } elseif ($id) {
            $data = $this->_model->discovery($this->_entity);
        }
        return Format::returnData($data, false, $this->params()->fromQuery('page') ? : 1, $this->_model->getTotalResults());
    }

    private function getAllData() {
        $data = $this->_model->discovery($this->_entity);
        return Format::returnData($data, false, $this->params()->fromQuery('page') ? : 1, $this->_model->getTotalResults());
    }

    private function getData() {
        $id = $this->params()->fromQuery('id');
        if ($id) {
            $return = $this->getDataById($id);
        } else {
            $return = $this->getAllData();
        }
        return $return;
    }

    public function indexAction() {
        $this->initialize();
        try {
            $return = [];
            switch ($this->_method) {
                case 'DELETE':
                case 'PUT':
                    $return = $this->alterData();
                    break;
                case 'POST':
                    $return = $this->insertData();
                    break;
                case 'GET':
                    $return = ($this->_viewMethod == 'form') ? [] : $this->getData();
                    break;
            }
            switch ($this->_viewMethod) {
                case 'form':
                    $return['response']['form'] = $this->getForm(isset($return['response']['data']['id']) ? $return['response']['data']['id'] : null);
                    break;
            }
            $return['response']['method'] = $this->_method;
            $return['response']['view_method'] = $this->_viewMethod;
        } catch (\Exception $e) {
            $return = array('response' => array('error' => array('code' => $e->getCode(), 'message' => $e->getMessage()), 'success' => false));
        }
        $this->_view->setVariables($return);
        return $this->_view;
    }

    public function userInUseAction() {
        $usermame = $this->params()->fromQuery('username');
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $user = $this->_userModel->getEntity()->findOneBy(array('username' => $usermame));
        $this->_view->setVariables(array('data' => false));
        $user ? ErrorModel::addError('User in use') : false;
        return $this->_view;
    }

    public function getImageProfileAction() {
        $usermame = $this->params()->fromQuery('username');
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $user = $this->_userModel->getEntity()->findOneBy(array('username' => $usermame));
        $this->_view->setVariables($user ? Format::returnData(array(
                            'user' => array(
                                'name' => ucwords(strtolower($user->getPeople()->getName())),
                                'image' => array(
                                    'url' => $user->getPeople()->getImage()->getUrl()
                                )
                    ))) : ErrorModel::addError('User not found'));
        return $this->_view;
    }

    public function profileImageAction() {
        $defaultImgProfile = 'public/assets/img/default/profile.png';
        $userId = $this->params()->fromQuery('id');
        if ($userId) {
            $this->_peopleModel = new UserModel();
            $this->_peopleModel->initialize($this->serviceLocator);
            $this->_peopleModel->setEntity('Core\\Entity\\Image');
            $image = $this->_peopleModel->getEntity()->find($userId);
        }
        $file = $image && is_file($image->getPath()) ? $image->getPath() : $defaultImgProfile;
        $imageContent = file_get_contents($file);
        $response = $this->getResponse();
        $response->setContent($imageContent);
        $finfo = new \finfo();
        $fileMimeType = $finfo->file($file, FILEINFO_MIME_TYPE);
        $response
                ->getHeaders()
                ->addHeaderLine('Content-Transfer-Encoding', 'binary')
                ->addHeaderLine('Content-Type', $fileMimeType ? : 'image/svg+xml');
        return $response;
    }

    public function deleteAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $delete = $this->_userModel->delete($this->params()->fromPost('id'));
        return $delete ? $this->_view : ErrorModel::addError('Error removing this user!');
    }

    public function deleteAddressAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $delete = $this->_userModel->deleteAddress($this->params()->fromPost('id'));
        return $delete ? $this->_view : ErrorModel::addError('Error removing this address!');
    }

    public function deleteDocumentAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $delete = $this->_userModel->deleteDocument($this->params()->fromPost('id'));
        return $delete ? $this->_view : ErrorModel::addError('Error removing this document!');
    }

    public function deletePhoneAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $delete = $this->_userModel->deletePhone($this->params()->fromPost('id'));
        return $delete ? $this->_view : ErrorModel::addError('Error removing this phone!');
    }

    public function deleteEmailAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $delete = $this->_userModel->deleteEmail($this->params()->fromPost('id'));
        return $delete ? $this->_view : ErrorModel::addError('Error removing this email!');
    }

    public function profileAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        if (!$this->_userModel->loggedIn()) {
            return \Core\Helper\View::redirectToLogin($this->_renderer, $this->getResponse(), $this->getRequest(), $this->redirect());
        }
        $this->_view->setTemplate($this->profile_view . '/default/profile');
        return $this->_view;
    }

    public function forgotPassword() {
        return $this->_view;
    }

    public function forgotUsername() {
        return $this->_view;
    }

    public function addPhoneAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $phone = $this->params()->fromPost('phone');
        $ddd = $this->params()->fromPost('ddd');

        if ($this->params()->fromPost() && $this->_userModel->loggedIn() && $ddd && $phone) {
            $new_phone = $this->_userModel->addUserPhone($ddd, $phone);
            $this->_view->setVariables(Format::returnData($new_phone));
        } elseif ($this->params()->fromPost()) {
            ErrorModel::addError('The ddd field is required.');
            ErrorModel::addError('The phone field is required.');
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function addAddressAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $params = $this->params()->fromPost();
        if ($params && $this->_userModel->loggedIn()) {
            $new_address = $this->_userModel->addUserAddress($params);
            $this->_view->setVariables(Format::returnData(array(
                        'id' => $new_address->getId(),
                        'street' => $new_address->getStreet()->getStreet(),
                        'number' => $new_address->getNumber(),
                        'complement' => $new_address->getComplement(),
                        'nickname' => $new_address->getNickname(),
                        'district' => $new_address->getStreet()->getDistrict()->getDistrict(),
                        'cep' => $new_address->getStreet()->getCep()->getCep(),
                        'city' => $new_address->getStreet()->getDistrict()->getCity()->getCity(),
                        'state' => $new_address->getStreet()->getDistrict()->getCity()->getState()->getState(),
                        'country' => $new_address->getStreet()->getDistrict()->getCity()->getState()->getCountry()->getCountryname()
            )));
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function addDocumentAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $document = $this->params()->fromPost('document');
        $document_type = $this->params()->fromPost('document-type');

        if ($this->params()->fromPost() && $this->_userModel->loggedIn() && $document && $document_type) {
            $new_document = $this->_userModel->addUserDocument($document, $document_type);
            $this->_view->setVariables(Format::returnData($new_document));
        } elseif ($this->params()->fromPost()) {
            ErrorModel::addError('The document field is required.');
        } else {
            $document_types = $this->_userModel->getDocumentTypes();
            $this->_view->setVariables(array('document_types' => $document_types));
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function addEmailAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $email = $this->params()->fromPost('email');

        if ($this->params()->fromPost() && $this->_userModel->loggedIn() && $email) {
            $new_email = $this->_userModel->addUserEmail($email);
            $this->_view->setVariables(Format::returnData($new_email));
        } elseif ($this->params()->fromPost()) {
            ErrorModel::addError('The email field is required.');
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function addUserAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);

        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        $confirm_password = $this->params()->fromPost('confirm-password');

        if ($this->params()->fromPost() && $this->_userModel->loggedIn() && $username && $password && $confirm_password) {
            $new_user = $this->_userModel->addUser($username, $password, $confirm_password);
            $this->_view->setVariables(Format::returnData($new_user));
        }

        $this->_view->setTerminal(true);
        return $this->_view;
    }

    public function loginAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        $login_referrer = $this->params()->fromQuery('login-referrer');
        if ((!$username || !$password) && $this->_userModel->loggedIn()) {
            Header::addArbitraryRequireJsFile(DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'login', 'user-default-login');
            return $this->redirect()->toUrl($login_referrer ? : $this->_renderer->basePath('/user/profile'));
        } elseif ($username && $password) {
            $this->_userModel->login($username, $password);
            $user = $this->_userModel->getLoggedUser();
            $this->_view->setVariables(Format::returnData($user ? array('apiKey' => $user->getApiKey()) : false));
        }
        $this->_view->setVariable('login_referrer', $login_referrer);
        $this->_view->setVariable('create_account_view', $this->create_account_view);
        $this->_view->setTemplate($this->login_view . '/default/login');
        return $this->_view;
    }

    public function createCorporateAccountAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $document_number = Format::onlyNumbers($this->params()->fromPost('document-number'));
        $password = $this->params()->fromPost('password');
        $confirm_password = $this->params()->fromPost('confirm-password');
        $email = $this->params()->fromPost('email');

        $data = Api::nvGet('PessoasEmpresasTk', array(
                    'documento' => $document_number
        ));
        if ($data && $data['CADASTRAIS']) {
            $user = new UserModel();
            $user->initialize($this->serviceLocator);
            $user->createAccount(is_array($data['CADASTRAIS']['CPF']) ? $data['CADASTRAIS']['CPF'][0] : $data['CADASTRAIS']['CPF'], $email, is_array($data['CADASTRAIS']['NOME']) ? $data['CADASTRAIS']['NOME'][0] : $data['CADASTRAIS']['NOME'], $password, $confirm_password, 'client');
            if (!ErrorModel::getErrors()) {
                $user->addUserDocument(is_array($data['CADASTRAIS']['CPF']) ? $data['CADASTRAIS']['CPF'][0] : $data['CADASTRAIS']['CPF'], 'CPF');
            }

            if (!ErrorModel::getErrors()) {
                if ($data['EMAILS']) {
                    foreach ($data['EMAILS'] AS $mail) {
                        $user->addUserEmail(is_array($mail['EMAIL']) ? $mail['EMAIL'][0] : $mail['EMAIL']);
                    }
                }
            }
            if (!ErrorModel::getErrors()) {
                if ($data['TELEFONES']) {
                    foreach ($data['TELEFONES'] AS $phone) {
                        $user->addUserPhone(is_array($phone['DDD']) ? $phone['DDD'][0] : $phone['DDD'], is_array($phone['TELEFONE']) ? $phone['TELEFONE'][0] : $phone['TELEFONE']);
                    }
                }
            }
            if (!ErrorModel::getErrors()) {
                if ($data['ENDERECOS']) {
                    $data['ENDERECOS'] = isset($data['ENDERECOS']['CEP']) ? array($data['ENDERECOS']) : $data['ENDERECOS'];
                    foreach ($data['ENDERECOS'] AS $enderecos) {
                        $params['address-nickname'] = $enderecos['TITULO'] ? : 'Endereço ' . $enderecos['POSICAO'];
                        $params['cep'] = is_array($enderecos['CEP']) ? $enderecos['CEP'][0] : $enderecos['CEP'];
                        $params['street'] = (is_array($enderecos['TIPO']) ? $enderecos['TIPO'][0] : $enderecos['TIPO']) . ' ' . (is_array($enderecos['LOGRADOURO']) ? $enderecos['LOGRADOURO'][0] : $enderecos['LOGRADOURO']);
                        $params['address-number'] = is_array($enderecos['NUMERO']) && $enderecos['NUMERO'][0] ? $enderecos['NUMERO'][0] : ($enderecos['NUMERO'] ? : 1);
                        $params['complement'] = is_array($enderecos['COMPLEMENTO']) && $enderecos['COMPLEMENTO'][0] ? $enderecos['COMPLEMENTO'][0] : ($enderecos['COMPLEMENTO'] ? : '');
                        $params['district'] = is_array($enderecos['BAIRRO']) ? $enderecos['BAIRRO'][0] : $enderecos['BAIRRO'];
                        $params['city'] = is_array($enderecos['CIDADE']) ? $enderecos['CIDADE'][0] : $enderecos['CIDADE'];
                        $params['state'] = is_array($enderecos['UF']) ? $enderecos['UF'][0] : $enderecos['UF'];
                        $params['country'] = 'Brazil';
                        $user->addUserAddress($params);
                    }
                }
            }
        } else {
            ErrorModel::addError('Document not found');
        }
    }

    public function createCorporateUserCompanyAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $user = new UserModel();
        $user->initialize($this->serviceLocator);
        $params = $this->params()->fromPost();
        if (!$user->loggedIn()) {
            return \Core\Helper\View::redirectToLogin($this->_renderer, $this->getResponse(), $this->getRequest(), $this->redirect());
        } elseif ($params && $user->loggedIn()) {
            $cnpj = Format::onlyNumbers($this->params()->fromPost('document-number'));
            $data = Api::nvGet('PessoasEmpresasTk', array(
                        'documento' => $cnpj
            ));
            if ($data && $data['CADASTRAIS'] && $data['ENDERECOS']) {
                $params['document-number'] = is_array($data['CADASTRAIS']['CNPJ']) ? $data['CADASTRAIS']['CNPJ'][0] : $data['CADASTRAIS']['CNPJ'];
                $params['name'] = is_array($data['CADASTRAIS']['RAZAO']) ? $data['CADASTRAIS']['RAZAO'][0] : $data['CADASTRAIS']['RAZAO'];
                $params['alias'] = $data['CADASTRAIS']['NOME_FANTASIA'] ? ( is_array($data['CADASTRAIS']['NOME_FANTASIA']) ? $data['CADASTRAIS']['NOME_FANTASIA'][0] : $data['CADASTRAIS']['NOME_FANTASIA']) : (is_array($data['CADASTRAIS']['RAZAO']) ? $data['CADASTRAIS']['RAZAO'][0] : $data['CADASTRAIS']['RAZAO']);
                $data['ENDERECOS'] = isset($data['ENDERECOS']['CEP']) ? array($data['ENDERECOS']) : $data['ENDERECOS'];
                foreach ($data['ENDERECOS'] AS $enderecos) {
                    $params['address-nickname'] = $enderecos['TITULO'] ? : 'Endereço ' . $enderecos['POSICAO'];
                    $params['cep'] = is_array($enderecos['CEP']) ? $enderecos['CEP'][0] : $enderecos['CEP'];
                    $params['street'] = (is_array($enderecos['TIPO']) ? $enderecos['TIPO'][0] : $enderecos['TIPO']) . ' ' . (is_array($enderecos['LOGRADOURO']) ? $enderecos['LOGRADOURO'][0] : $enderecos['LOGRADOURO']);
                    $params['address-number'] = is_array($enderecos['NUMERO']) && $enderecos['NUMERO'][0] ? $enderecos['NUMERO'][0] : ($enderecos['NUMERO'] ? : 1);
                    $params['complement'] = is_array($enderecos['COMPLEMENTO']) && $enderecos['COMPLEMENTO'][0] ? $enderecos['COMPLEMENTO'][0] : ($enderecos['COMPLEMENTO'] ? : '');
                    $params['district'] = is_array($enderecos['BAIRRO']) ? $enderecos['BAIRRO'][0] : $enderecos['BAIRRO'];
                    $params['city'] = is_array($enderecos['CIDADE']) ? $enderecos['CIDADE'][0] : $enderecos['CIDADE'];
                    $params['state'] = is_array($enderecos['UF']) ? $enderecos['UF'][0] : $enderecos['UF'];
                    $params['country'] = 'Brazil';
                }

                $companymodel = new \Company\Model\CompanyModel();
                $companymodel->initialize($this->serviceLocator);
                $people = $companymodel->addCompany($params);


                if ($people) {
                    $this->_view->setVariables(Format::returnData(array(
                                'company' => $people->getName(),
                                'alias' => $people->getAlias()
                    )));
                }
            } else {
                ErrorModel::addError('Document not found');
            }
        }
        return $this->_view;
    }

    public function createAccountAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $name = $this->params()->fromPost('name');
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        $confirm_password = $this->params()->fromPost('confirm-password');
        $ddd = $this->params()->fromPost('ddd');
        $phone = $this->params()->fromPost('phone');
        $email = $this->params()->fromPost('email');
        if ($username && !$this->_userModel->loggedIn()) {
            Header::addArbitraryRequireJsFile(DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'create-account', 'user-default-create-account');
            $this->_userModel->createAccount($username, $email, $name, $password, $confirm_password, $this->module_name);
            $this->_userModel->addUserPhone($ddd, $phone);
            $this->_view->setVariables(Format::returnData($this->_userModel->getLoggedUser()));
        } elseif ($this->_userModel->loggedIn()) {
            return $this->redirect()->toUrl('/' . $this->_renderer->basePath($this->profile_view . '/profile'));
        }
        $this->_view->setVariable('create_account_view', $this->create_account_view);
        $this->_view->setTemplate($this->create_account_view . '/default/create-account');
        return $this->_view;
    }

    public function changePasswordAction() {
        $name = $this->params()->fromPost('name');
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        $confirm_password = $this->params()->fromPost('confirm-password');

        if ($username && $this->_userModel->loggedIn()) {
            $this->_userModel->changeUser($username, $name, $password, $confirm_password);
        }
    }

    public function logoutAction() {
        $this->_userModel = new UserModel();
        $this->_userModel->initialize($this->serviceLocator);
        $this->_userModel->logout();
        return \Core\Helper\View::redirectToLogin($this->_renderer, $this->getResponse(), $this->getRequest(), $this->redirect(), false, $this->module_name);
    }

    public function contactRequestAction() {
        $this->_view->setTemplate($this->contact_request_view . '/default/contact-request');
        return $this->_view;
    }

}
