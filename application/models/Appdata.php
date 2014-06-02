<?php

class Application_Model_Appdata
{
    //  declare the appdata's attributes
    private $id;
    private $modified_by;
    private $date_modified;
    private $property;
    private $value;

    public function __construct($appdata_row = null)
    {
        if( !is_null($appdata_row) && $appdata_row instanceof Zend_Db_Table_Row) {
            $this->id              = $appdata_row->id;
            $this->modified_by     = $appdata_row->modified_by;
            $this->date_modified   = date('Y-m-d H:i:s');
            $this->property        = $appdata_row->property;
            $this->value           = $appdata_row->value;
        }
    }
    
    //  magic function __set to set the
    //  attributes of the appdata model
    public function __set($name, $value)
    {
        switch($name) {
            case 'id':
                //if the id isn't null, you shouldn't update it!
                if( !is_null($this->id) ) {
                    throw new Exception('Cannot update appdata\'s id!');
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

