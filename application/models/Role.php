<?php

class Application_Model_Role
{
    //  declare the role's attributes
    private $id;
    private $name;
    private $description;
    private $create_user;
    private $delete_user;
    private $edit_user;
    private $create_report;
    private $edit_report;
    private $delete_report;
    private $create_role;
    private $edit_role;
    private $delete_role;

    //  upon construction, map the values
    //  from the $role_row if available
    public function __construct($role_row = null)
    {
        if( !is_null($role_row) && $role_row instanceof Zend_Db_Table_Row) {
            $this->id              = $role_row->id;
            $this->name            = $role_row->name;
            $this->description     = $role_row->description;
            $this->create_user     = $role_row->create_user;
            $this->delete_user     = $role_row->delete_user;
            $this->edit_user       = $role_row->edit_user;
            $this->create_report   = $role_row->create_report;
            $this->edit_report     = $role_row->edit_report;
            $this->delete_report   = $role_row->delete_report;
            $this->create_role     = $role_row->create_role;
            $this->edit_role       = $role_row->edit_role;
            $this->delete_role     = $role_row->delete_role;
        }
    }
    
    //  magic function __set to set the
    //  attributes of the role model
    public function __set($name, $value)
    {
        switch($name) {
            case 'id':
                //if the id isn't null, you shouldn't update it!
                if( !is_null($this->id) ) {
                    throw new Exception('Cannot update Role\'s id!');
                }
                break;
        }
        
        //set the attribute with the value
        $this->$name = $value;
    }
    
    public function __get($name)
    {
        return $this->$name;
    }
}

