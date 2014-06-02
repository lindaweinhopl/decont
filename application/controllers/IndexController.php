<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->flashMessenger->getMessages(); 
    }


    public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $user_mapper = new Application_Model_UserMapper();
        $role_mapper = new Application_Model_RoleMapper();
        $appdata_mapper = new Application_Model_AppdataMapper();

        $whiteboard = $appdata_mapper->getAppdataByProperty('whiteboard');
        // throw new Exception(print_r($whiteboard, true));
        if (!$whiteboard->id){
            $whiteboard = new Application_Model_Appdata();
            $whiteboard->property = 'whiteboard';
            $whiteboard->modified_by = 1;
        }

        $user_role = $role_mapper->getRoleById($identity->role);
        $this->view->whiteboard = $whiteboard;
        $this->view->modifier = $user_mapper->getUserById($whiteboard->modified_by);
        $this->view->user_role = $user_role;

        if($this->getRequest()->isPost()){
            $whiteboard_text = $_POST['edit_wb_text'];
            if ($user_role->edit_report == 0){
                $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for editing the whiteboard.'));
                $this->_redirect('/');
            }
            $whiteboard->value = $whiteboard_text;
            
            $appdata_mapper->save($whiteboard);
            $this->flashMessenger->addMessage(array('confirmation' => 'Whiteboard successfully updated'));
            $this->_redirect('/');
        }
    }

    public function contactAction()
    {
        if($this->getRequest()->isPost()) {     
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            
            $input = array($name, $email, $subject, $message);
            foreach ($input as $key){
                if (strlen($input) == 0 || strlen($input) > 255){
                    $this->flashMessenger->addMessage(array('error' => 'Each field must be filled with at least one character and no more than 255'));
                    $this->_redirect('/index/contact');
                }
            }
            $formcontent = $message;
            $recipient = "admin@e-uvt.ro";
            $subject = "[decont] $subject";
            $mailheader = "From: $email \r\n";
            $result = mail($recipient, $subject, $formcontent, $mailheader);
            if (!$result){
                $this->flashMessenger->addMessage(array('error' => 'Error sending message.'));
                $this->_redirect('/index/contact');
            }
            $this->flashMessenger->addMessage(array('error' => 'Your message has been sent.'));
            $this->_redirect('/index/contact');
        }
    }

}







