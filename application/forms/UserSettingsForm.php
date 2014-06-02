<?php
class Application_Form_UserSettingsForm extends Zend_Form
{
    public function init()
    {
        $first_name = $this->createElement('text','first_name');
        $last_name = $this->createElement('text','last_name');
        $email = $this->createElement('text','email');
        $password0 = $this->createElement('password','password0');
        $password1 = $this->createElement('password','password1');
        $password2 = $this->createElement('password','password2');

        $register = $this->createElement('submit','register');
        $register->setLabel('Register')
                 ->setIgnore(true);

        $this->addElements(array(
            $first_name,
            $last_name,
            $email,
            $password0,
            $password1,
            $password2,
            $register,
        ));
    }
}
