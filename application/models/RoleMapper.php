<?php

class Application_Model_RoleMapper
{
    protected $_db_table;
    
    public function __construct()
    {
        //  Instantiate the Table Data Gateway for the role table
        $this->_db_table = new Application_Model_DbTable_Role();
    }
    
    public function save(Application_Model_Role $role_object)
    {
        //  Create an associative array
        //  of the data you want to update
        $data = array(
            'name'           => $role_object->name,
            'description'    => $role_object->description,
            'create_user'    => $role_object->create_user,            
            'delete_user'    => $role_object->delete_user,
            'edit_user'      => $role_object->edit_user,
            'create_report'  => $role_object->create_report,
            'delete_report'  => $role_object->delete_report,
            'edit_report'    => $role_object->edit_report,
            'create_role'    => $role_object->create_role,
            'delete_role'    => $role_object->delete_role,
            'edit_role'      => $role_object->edit_role,
        );

        //  Check if the role object has an ID
        //  if not, it means the role is new
        //  if yes, then it means you're updating an old role
        // throw new Exception(print_r($data, True));
        if(is_null($role_object->id)) {            
            $this->_db_table->insert($data);
        }
        else {
            $this->_db_table->update($data, array('id = ?' => $role_object->id));
        }
    }
    
    public function getRoleById($id)
    {
        //  use the Table Gateway to find the row that
        //  the id represents
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('Role not found');
        }
        
        //  if found, get the result, and map it to the
        //  corresponding Data Object

        $row = $result->current();
        $role_object = new Application_Model_Role($row);
        //return the role object
        return $role_object;
    }

    public function seekRoleByName($name){
        $select = $this->_db_table->select()->where('name = ?', $name);
        $result = $this->_db_table->fetchAll($select);

        if( count($result) == 0 ) {
            return false;
        }
        return true;
    }

    public function getRoles(){
        $rows = $this->_db_table->fetchAll();
        return $rows;
    }

    public function delete($id){
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('Role not found');
        }

        $where = $this->_db_table->getAdapter()->quoteInto('id = ?', $id);
 
        $this->_db_table->delete($where);
        
    }
}
