<?php
class Application_Model_UserMapper
{
    protected $_db_table;
    
    public function __construct()
    {
        //Instantiate the Table Data Gateway for the User table
        $this->_db_table = new Application_Model_DbTable_User();
    }
    
    public function save(Application_Model_User $user_object, $update_password = false)
    {
        //Create an associative array
        //of the data you want to update
        $data = array(
            'email'        => $user_object->email,
            'role'         => $user_object->role,
            'first_name'   => $user_object->first_name,
            'last_name'    => $user_object->last_name,
            'created_by'   => $user_object->created_by,
        );
        
        if(is_null($user_object->role)){
            $data['role'] = 'guest';
        }
        //Check if the user object has an ID
        //if no, it means the user is a new user
        //if yes, then it means you're updating an old user
        if(is_null($user_object->id)) {
            $data['salt'] = sha1(time());
            $data['password'] = sha1($user_object->password . $data['salt']);
            $data['date_created'] = date('Y-m-d H:i:s');
            $this->_db_table->insert($data);
        } else {
            if($update_password == true){
                $data['password'] = sha1($user_object->password . $user_object->salt);
            }
            $this->_db_table->update($data, array('id = ?' => $user_object->id));
        }
    }
    
    public function getUserById($id)
    {
        //use the Table Gateway to find the row that
        //the id represents
        $result = $this->_db_table->find($id);
        
        //if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('User not found');
        }
        
        //if found, get the result, and map it to the
        //corresponding Data Object
        $row = $result->current();
        $user_object = new Application_Model_User($row);
        
        //return the user object
        return $user_object;
    }

    public function getUserByEmail($email){
        $select = $this->getDbTable()->select()->where('email = ?', $email);
        $result = $this->getDbTable()->fetchAll($select);

        if( count($result) == 0 ) {
            throw new Exception('User not found');
        }
        
        //if found, get the result, and map it to the
        //corresponding Data Object
        $row = $result->current();
        $user_object = new Application_Model_User($row);
        
        //return the user object
        return $user_object;
    }

    public function seekUserByEmail($email){
        $select = $this->_db_table->select()->where('email = ?', $email);
        $result = $this->_db_table->fetchAll($select);

        if( count($result) == 0 ) {
            return false;
        }
        return true;
    }

    public function delete($id){
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('User not found');
        }

        $where = $this->_db_table->getAdapter()->quoteInto('id = ?', $id);
 
        $this->_db_table->delete($where);
        
    }

    public function getUsers(){
        $rows = $this->_db_table->fetchAll();
        return $rows;
    }


}