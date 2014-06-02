<?php

class Application_Model_Document
{
    //  declare the document's attributes
    private $id;
    private $submited_by;
    private $date_submited;
    private $data;
    private $link;

    //  upon construction, map the values
    //  from the $document_row if available
    public function __construct($document_row = null)
    {
        if( !is_null($document_row) && $document_row instanceof Zend_Db_Table_Row) {
            $this->id              = $document_row->id;
            $this->submited_by     = $document_row->submited_by;
            $this->date_submited   = $document_row->date_submited;
            $this->data            = $document_row->data;
            $this->link            = $document_row->link;
        }
    }
    
    //  magic function __set to set the
    //  attributes of the Document model
    public function __set($name, $value)
    {
        switch($name) {
            case 'id':
                //if the id isn't null, you shouldn't update it!
                if( !is_null($this->id) ) {
                    throw new Exception('Cannot update Document\'s id!');
                }
                break;
            case 'date_created':
                //same goes for date_created
                if( !is_null($this->date_created) ) {
                    throw new Exception('Cannot update Document\'s date_created');
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

