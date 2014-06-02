<?php

class Application_Model_AppdataMapper
{
    protected $_db_table;
    
    public function __construct()
    {
        //  Instantiate the Table Data Gateway for the appdata table
        $this->_db_table = new Application_Model_DbTable_Appdata();
    }
    
    public function save(Application_Model_appdata $appdata_object)
    {
        //  Create an associative array
        //  of the data you want to update
        $data = array(
            'modified_by'    => $appdata_object->modified_by,
            'date_modified'  => $appdata_object->date_modified,            
            'property'       => $appdata_object->property,
            'value'          => $appdata_object->value,
        );

        //  Check if the appdata object has an ID
        //  if not, it means the appdata is new
        //  if yes, then it means you're updating an old appdata
        // throw new Exception(print_r($data, True));
        if(is_null($appdata_object->id)) {            
            $this->_db_table->insert($data);
        }
        else {
            $this->_db_table->update($data, array('id = ?' => $appdata_object->id));
        }
    }
    
    public function getAppdataById($id)
    {
        //  use the Table Gateway to find the row that
        //  the id represents
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('Appdata not found');
        }
        
        //  if found, get the result, and map it to the
        //  corresponding Data Object

        $row = $result->current();
        $appdata_object = new Application_Model_Appdata($row);
        //return the appdata object
        return $appdata_object;
    }

    public function getAppdataByProperty($property){
        $select = $this->_db_table->select()->where('property= ?', $property);
        $result = $this->_db_table->fetchAll($select);

        $row = $result->current();
        $appdata_object = new Application_Model_Appdata($row);
        //return the appdata object
        return $appdata_object;
    }

    public function getAppdata(){
        $rows = $this->_db_table->fetchAll();
        return $rows;
    }

    public function delete($id){
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('appdata not found');
        }

        $where = $this->_db_table->getAdapter()->quoteInto('id = ?', $id);
 
        $this->_db_table->delete($where);
        
    }
}
