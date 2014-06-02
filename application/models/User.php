<?php
class Application_Model_User
{
    //declare the user's attributes
    private $id;
    private $email;
    private $password;
    private $salt;
    private $date_created;
    private $role;
    private $first_name;
    private $last_name;
    private $created_by;

    //upon construction, map the values
    //from the $user_row if available
    public function __construct($user_row = null)
    {
        if( !is_null($user_row) && $user_row instanceof Zend_Db_Table_Row ) {
            $this->id             = $user_row->id;
            $this->email          = $user_row->email;
            $this->password       = $user_row->password;
            $this->salt           = $user_row->salt;
            $this->date_created   = $user_row->date_created;
            $this->role           = $user_row->role;
            $this->first_name     = $user_row->first_name;
            $this->last_name      = $user_row->last_name;
            $this->created_by     = $user_row->created_by;
        }
    }
    
    //magic function __set to set the
    //attributes of the User model
    public function __set($name, $value)
    {
        switch($name) {
            case 'id':
                //if the id isn't null, you shouldn't update it!
                if( !is_null($this->id) ) {
                    throw new Exception('Cannot update User\'s id!');
                }
                break;
            case 'date_created':
                //same goes for date_created
                if( !is_null($this->date_created) ) {
                    throw new Exception('Cannot update User\'s date_created');
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