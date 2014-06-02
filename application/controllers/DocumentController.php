<?php


class DocumentController extends Zend_Controller_Action
{

    public function init()
    {
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->flashMessenger->getMessages();
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)){
            $this->flashMessenger->addMessage(array('error' => 'You must be logged in to perform this action. Please log in or register.'));
            $this->_redirect('/');
        }
    }

    public function indexAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $request = $this->getRequest();
        
        $page = $request->getQuery('page');

        $query = array();
        $query['sort'] = $request->getQuery('sort');
        $query['sort_type'] = $request->getQuery('sort_type');
        $query['search'] = $request->getQuery('search');
        $query['nr_results'] = $request->getQuery('nr_results');
        $query['user'] = $request->getQuery('user');

        $this->view->query = $query;

        if (is_null($query['nr_results']) || ((int)$query['nr_results'] > 45))
            $nr_results = 12;
        else
            $nr_results = $query['nr_results'];

        if (is_null($page))
            $page=1;

        $document_mapper = new Application_Model_DocumentMapper();
        $fetch_options = array();
        if (isset($query['search']))
            $fetch_options['whereLike']['data'] = $query['search'];
        if (isset($query['user']) && $query['user'] != null)
            $fetch_options['where']['submited_by']= $query['user'];

        if (isset($query['sort'])){
            if (!isset($query['sort_type']))
                $sort_type = 'DESC';
            else
                $sort_type = $query['sort_type'];

            $fetch_options['order'][$query['sort']] = $sort_type;
        }

        $latest_posts = $document_mapper->getDocuments($fetch_options, true);

        $paginator = Zend_Paginator::factory($latest_posts);
        $paginator->setItemCountPerPage($nr_results);
        $paginator->setCurrentPageNumber($page);
        
        $this->view->paginator = $paginator;
    }

    public function addAction()
    {
        $document = new Application_Model_Document();
        $document_mapper = new Application_Model_DocumentMapper();

        $identity = Zend_Auth::getInstance()->getIdentity();

        $role_mapper = new Application_Model_RoleMapper();
        $user_role = $role_mapper->getRoleById($identity->role);

        if ($user_role->create_report == 0){
                $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for adding documents.'));
                $this->_redirect('/user');
        }

        if($this->getRequest()->isPost()) {
            $hash = sha1(getdate()[0]);
            $document->data        = $_POST['data'];
            $document->link        = '/documents/'. $identity->id . '/' . $hash;
            $document->submited_by = $identity->id;
            $document_mapper->save($document);

            $this->flashMessenger->addMessage(array('confirmation' => 'Document has been successfully uploaded.'));
            $this->_redirect('/document');
        }
        
    }

    public function viewAction()
    {
        $user_mapper = new Application_Model_UserMapper();        
        $request = $this->getRequest();
        $id = $request->getQuery('id');
        $document_mapper = new Application_Model_DocumentMapper();
        $document = $document_mapper->getDocumentById($id);

        try{
            $this->view->document = $document;
            $this->view->submiter = new Application_Model_User();
            $this->view->submiter = $user_mapper->getUserById($document->submited_by);
        }
        catch(Exception $e){
            $this->flashMessenger->addMessage(array('error' => 'Document not found.'));
            $this->_redirect('/document');
        }
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $id = $request->getQuery('id');
        $document_mapper = new Application_Model_DocumentMapper();

        $identity = Zend_Auth::getInstance()->getIdentity();

        $role_mapper = new Application_Model_RoleMapper();
        $user_role = $role_mapper->getRoleById($identity->role);

        $request = $this->getRequest();
        $id = $request->getQuery('id');
        $document = $document_mapper->getDocumentById($id);
        $this->view->document = $document;

        if($this->getRequest()->isPost()) {
            if ($user_role->edit_report == 0){
                $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for editing documents.'));
                $this->_redirect('/user');
            }
            $document->data = $_POST['data'];
            $document_mapper->save($document);

            $this->flashMessenger->addMessage(array('confirmation' => 'Document has been successfully uploaded.'));
            $this->_redirect('/document');
        }
    }

    public function deleteAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $document_mapper = new Application_Model_DocumentMapper();
        $role_mapper = new Application_Model_RoleMapper();

        $user_role = $role_mapper->getRoleById($identity->role);
        if ($user_role->delete_report == 0){
            $this->flashMessenger->addMessage(array('error' => 'You do not have the required privilegies for deleting documents.'));
            $this->_redirect('/user');
        }

        $request = $this->getRequest();
        $id = $request->getQuery('id');

        $document_mapper->delete($id);

        $this->flashMessenger->addMessage(array('confirmation' => 'Successfully deleted document.'));
        $this->_redirect('/document');
    }

}



