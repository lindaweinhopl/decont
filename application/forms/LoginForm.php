<?php
class Application_Form_LoginForm extends Zend_Form
{
    public function init()
    {
        $email = $this->createElement('text','email_');
        $email->setLabel('Email: *')
              ->setRequired(true);

        $password = $this->createElement('password','password_');
        $password->setLabel('Password: *')
                 ->setRequired(true);

        $signin = $this->createElement('submit','signin');
        $signin->setLabel('Sign in')
               ->setIgnore(true);

        $this->addElements(array(
            $email,
            $password,
            $signin,
        ));
    }
}