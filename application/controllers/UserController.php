<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $user_mapper = new Application_Model_UserMapper();
        $role_mapper = new Application_Model_RoleMapper();

        $this->view->roles = $role_mapper->getRoles();
        $this->view->users = $user_mapper->getUsers();
    }

    public function loginAction()
    {
        $form = new Application_Form_LoginForm();
        $request = $this->getRequest();
        if($request->isPost()) {
            if($form->isValid($request->getPost())){
                $data = $form->getValues();
                $this->initLogin($data['email_'], $data['password_'], $data['remember']);                    
            }
            $this->flashMessenger->addMessage(array('login_error' => 'Invalid email and/or password. Please try again.'));
            $this->_redirect('/');
        }
    }

    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->flashMessenger->addMessage(array('logout_confirmation' => 'Logged out successfully'));
        $this->_redirect('/');
    }

    public function registerAction()
    {
        $user = new Application_Model_User();
        $identity = Zend_Auth::getInstance()->getIdentity();

        $user_mapper = new Application_Model_UserMapper();
        $form = new Application_Form_RegisterForm();

        $role_mapper = new Application_Model_RoleMapper();
        $this->view->roles = $role_mapper->getRoles();

        if($this->getRequest()->isPost()) {
            if($form->isValid($_POST)){
                $data = $form->getValues();

                $user->email      = $data['email'];
                $user->first_name = $data['first_name'];
                $user->last_name  = $data['last_name'];
                $user->password   = $data['password'];
                $user->role       = $data['role'];
                $user->created_by = $identity->id;

                if($user_mapper->seekUserByEmail($data['email'])){
                    $this->flashMessenger->addMessage(array('register_error' => 'Email already used!'));
                    $this->_redirect('/user/register');
                }
                $user_mapper->save($user);
                $this->flashMessenger->addMessage(array('confirmation' => 'User successfully created.'));
                $this->_redirect('/user');
            }
            else{
                $this->flashMessenger->addMessage(array('register_error' => 'Please fill all the fields.'));
                $this->_redirect('/user/register');
            }
        }
    }

    public function initLogin($user_email, $user_password, $remember = false)
    {
        $users = new Application_Model_DbTable_User();
        $auth = Zend_Auth::getInstance();
        $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'user');
        $authAdapter->setIdentityColumn('email')->setCredentialColumn('password');
        $authAdapter->setIdentity($user_email)->setCredential($user_password);
        $authAdapter->setCredentialTreatment('SHA1(CONCAT(?,salt))');
        $result = $auth->authenticate($authAdapter);
        if($result->isValid()) {
            $storage = new Zend_Auth_Storage_Session();
            $storage->write($authAdapter->getResultRowObject());
            $mysession = new Zend_Session_Namespace('mysession');
            $seconds  = 60 * 60 * 24 * 7; // 7 days
            if ($remember) {
                Zend_Session::RememberMe($seconds);
            }
            else {
                Zend_Session::ForgetMe();
            }

            if(isset($mysession->destination_url)) {
                $url = $mysession->destination_url;
                unset($mysession->destination_url);
                $controller->_redirect($url);
            }
            $this->_redirect('/');
        }            
        $this->flashMessenger->addMessage(array('login_error' => 'Invalid email and/or password. Please try again.'));
        $this->_redirect('/');
    }

    public function settingsAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)){
            $this->flashMessenger->addMessage(array('error' => 'You must be logged in to perform this action. Please log in or register.'));
            $this->_redirect('/');
        }
        $user_mapper = new Application_Model_UserMapper();
        $user = $user_mapper->getUserById($identity->id);
        $form = new Application_Form_UserSettingsForm();
        if($this->getRequest()->isPost()) {
            if($form->isValid($_POST)){

                $data = $form->getValues();
                if($user_mapper->seekUserByEmail($data['email']) && $user->email != $data['email']){
                    $this->flashMessenger->addMessage(array('register_error' => 'Email already used!'));
                    $this->_redirect('/user/settings');
                }
                $user->email = $data['email'];
                $user->first_name = $data['first_name'];
                $user->last_name = $data['last_name'];
                $password0 = $data['password0'];
                $password1 = $data['password1'];
                $password2 = $data['password2'];

                if ($password0 != null && $password1 != null){
                    if ($password1 === $password2)
                        $user->password = $password1;
                    else{
                        $this->flashMessenger->addMessage(array('error' => 'Passwords do not match.'));
                        $this->_redirect('/user/settings');    
                    }
                }

                $user_mapper->save($user);
                $identity->fist_name = $user->first_name;
                $identity->last_name = $user->last_name;
                $this->flashMessenger->addMessage(array('confirmation' => 'Your account has been updated.'));
                $this->_redirect('/user/settings');
            }
            else{
                $this->flashMessenger->addMessage(array('register_error' => 'Unable to update profile.'));
                $this->_redirect('/user/settings');
            }
        }
    }

    public function addroleAction()
    {
        $role = new Application_Model_Role();
        $identity = Zend_Auth::getInstance()->getIdentity();

        $user_mapper = new Application_Model_UserMapper();
        $form = new Application_Form_AddRoleForm();

        $role_mapper = new Application_Model_RoleMapper();
        $this->view->roles = $role_mapper->getRoles();

        if($this->getRequest()->isPost()) {
            if($form->isValid($_POST)){
                $data = $form->getValues();

                $role->name          = $data['name'];
                $role->description   = $data['description'];
                $role->create_user   = $data['create_user'];
                $role->delete_user   = $data['delete_user'];
                $role->edit_user     = $data['edit_user'];
                $role->create_report = $data['create_report'];
                $role->delete_report = $data['delete_report'];
                $role->edit_report   = $data['edit_report'];
                $role->create_role   = $data['create_role'];
                $role->delete_role   = $data['delete_role'];
                $role->edit_role     = $data['edit_role'];

                if($role_mapper->seekRoleByName($data['name'])){
                    $this->flashMessenger->addMessage(array('error' => 'A role with the same name already exists. Pick another name.'));
                    $this->_redirect('/user/addrole');
                }
                $role_mapper->save($role);
                $this->flashMessenger->addMessage(array('confirmation' => 'Role successfully added.'));
                $this->_redirect('/user');
            }
            else{
                $this->flashMessenger->addMessage(array('error' => 'Please fill all the fields.'));
                $this->_redirect('/user/addrole');
            }
        }
    }

    public function editroleAction()
    {
        $role = new Application_Model_Role();
        $identity = Zend_Auth::getInstance()->getIdentity();
        $role_mapper = new Application_Model_RoleMapper();
        $user_role = $role_mapper->getRoleById($identity->role);

        if ($user_role->edit_role == 0){
            $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for editing roles.'));
            $this->_redirect('/user');
        }

        $request = $this->getRequest();
        $id = $request->getQuery('id');
        $form = new Application_Form_EditRoleForm();

        $role = $role_mapper->getRoleById($id);
        $this->view->role = $role;

        if($this->getRequest()->isPost()) {
            if($form->isValid($_POST)){
                $data = $form->getValues();

                $role->name          = $data['name'];
                $role->description   = $data['description'];
                $role->create_user   = $data['create_user'];
                $role->delete_user   = $data['delete_user'];
                $role->edit_user     = $data['edit_user'];
                $role->create_report = $data['create_report'];
                $role->delete_report = $data['delete_report'];
                $role->edit_report   = $data['edit_report'];
                $role->create_role   = $data['create_role'];
                $role->delete_role   = $data['delete_role'];
                $role->edit_role     = $data['edit_role'];

                $role_mapper->save($role);
                $this->flashMessenger->addMessage(array('confirmation' => 'Role edited successfully added.'));
                $this->_redirect('/user');
            }
            else{
                $this->flashMessenger->addMessage(array('error' => 'Please fill all the fields.'));
                $this->_redirect('/user/editrole?id=' . $role->id);
            }
        }
    }

    public function deleteroleAction()
    {
        $role = new Application_Model_Role();
        $identity = Zend_Auth::getInstance()->getIdentity();
        $role_mapper = new Application_Model_RoleMapper();
        $user_role = $role_mapper->getRoleById($identity->role);
        $user_mapper = new Application_Model_UserMapper();
        $users = $user_mapper->getUsers();

        if ($user_role->delete_role == 0){
            $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for deleting roles.'));
            $this->_redirect('/user');
        }

        $request = $this->getRequest();
        $id = $request->getQuery('id');

        foreach($users as $user){
            if($user->role == $id){
                $this->flashMessenger->addMessage(array('error' => 'Cannot delete role if there are users having this role.'));
                $this->_redirect('/user');
            }
        }
        $role = $role_mapper->delete($id);
        $this->flashMessenger->addMessage(array('confirmation' => 'Role deleted successfully added.'));
        $this->_redirect('/user');
    }

    public function editAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();

        $user_mapper = new Application_Model_UserMapper();
        $role_mapper = new Application_Model_RoleMapper();

        $user_role = $role_mapper->getRoleById($identity->role);
        if ($user_role->edit_user == 0){
            $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for editing users.'));
            $this->_redirect('/user');
        }

        $request = $this->getRequest();
        $id = $request->getQuery('id');
        $form = new Application_Form_EditUserForm();

        $user = $user_mapper->getUserById($id);
        $this->view->user = $user;
        $this->view->roles = $role_mapper->getRoles();

        if($this->getRequest()->isPost()) {
            if($form->isValid($_POST)){
                $data = $form->getValues();

                $user->email      = $data['email'];
                $user->first_name = $data['first_name'];
                $user->last_name  = $data['last_name'];
                $user->password   = $data['password'];
                $user->role       = $data['role'];
                $user->created_by = $identity->id;

                $user_mapper->save($user);
                $this->flashMessenger->addMessage(array('confirmation' => 'User successfully edited.'));
                $this->_redirect('/user');
            }
            else{
                $this->flashMessenger->addMessage(array('register_error' => 'Please fill all the fields.'));
                $this->_redirect('/user/edit?id='.$id);
            }
        }
    }

    public function deleteAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $user_mapper = new Application_Model_UserMapper();
        $role_mapper = new Application_Model_RoleMapper();

        $user_role = $role_mapper->getRoleById($identity->role);
        if ($user_role->delete_user == 0){
            $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for deleting users.'));
            $this->_redirect('/user');
        }

        $request = $this->getRequest();
        $id = $request->getQuery('id');

        $user = $user_mapper->delete($id);

        $this->flashMessenger->addMessage(array('confirmation' => 'Successfully deleted user.'));
        $this->_redirect('/user');
    }

}
