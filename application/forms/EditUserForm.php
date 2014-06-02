<?php
class Application_Form_EditUserForm extends Zend_Form
{
    public function init()
    {
        $first_name = $this->createElement('text','first_name');
        $first_name->setLabel('First name: *')
              ->setRequired(true);

        $last_name = $this->createElement('text','last_name');
        $last_name->setLabel('Last name: *')
              ->setRequired(true);

        $email = $this->createElement('text','email');
        $email->setLabel('Email: *')
              ->setRequired(true);

        $password = $this->createElement('password','password');
        $password->setLabel('Password: *')
                 ->setRequired(true);

        $role_mapper = new Application_Model_RoleMapper();
        $roles = $role_mapper->getRoles();

        $options = array();
        foreach($roles as $role){
            $options[$role->id] = $role->name;
        }

        $this->addElement('radio', 'role', array(
            'label'=>'Role',
            'multiOptions'=>$options,
        ));

        $register = $this->createElement('submit','register');
        $register->setLabel('Register')
                 ->setIgnore(true);

        $this->addElements(array(
            $first_name,
            $last_name,
            $email,
            $password,
            $register,
        ));
    }
}
