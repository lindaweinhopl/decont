<?php

class Application_Model_DocumentMapper
{
    protected $_db_table;
    
    public function __construct()
    {
        //  Instantiate the Table Data Gateway for the document table
        $this->_db_table = new Application_Model_DbTable_Document();
    }
    
    public function save(Application_Model_Document $document_object)
    {
        //  Create an associative array
        //  of the data you want to update
        $data = array(
            'submited_by'   => $document_object->submited_by,
            'link'          => $document_object->link,
            'data'          => $document_object->data
        );

        if(is_null($document_object->data)){
            $data['data'] = '{}';
        }

        //  Check if the document object has an ID
        //  if not, it means the document is new
        //  if yes, then it means you're updating an old document

        if(is_null($document_object->id)) {
            $data['date_submited'] = date('Y-m-d H:i:s');
            $this->_db_table->insert($data);
        } 
        else {
            $this->_db_table->update($data, array('id = ?' => $document_object->id));
        }
    }
    
    public function getdocumentById($id)
    {
        //  use the Table Gateway to find the row that
        //  the id represents
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('Document not found');
        }
        
        //  if found, get the result, and map it to the
        //  corresponding Data Object

        $row = $result->current();
        $document_object = new Application_Model_Document($row);
        //return the document object
        return $document_object;
    }

    public function getDocuments($options, $query_only = false){
        // query_only => instead of returning the rows, the proper select query
        // is returned

        $select = $this->_db_table->select();

        if (isset($options['limit'])){
            $select->limit($options['limit'], 0);
        }

        // throw new Exception(serialize($options['where']));
        if(isset($options['whereLike']))
            foreach($options['whereLike'] as $column => $value){
                $select->where('upper('.$column . ") LIKE upper(?)", '%'.$value.'%');
            }
            

        if (isset($options['where'])){
            foreach($options['where'] as $column => $value)
                $select->where($column . " = ?", $value);
        }
        if (isset($options['orWhere'])){
            $ind = 0;
            $qry = "";
            foreach($options['orWhere'] as $i){
                // TODO: sanitize values
                foreach($i as $column => $value){
                if ($ind == 0)
                    $qry .= $column . " = " . "'$value'";
                else
                    $qry .= " OR ". $column . " = " ."'$value'";
                $ind++;
                }       
            }
            $select->where($qry);
        }

        if (isset($options['order'])){
            foreach($options['order'] as $column => $value){
                if ($value === "ASC"){
                    $type = $value;
                }
                else
                    $type = 'DESC';
                $select->order($column. ' ' .$type);
            }
        }
        else
            $select->order('date_submited DESC');

        if ($query_only)
            return $select;

        $rows = $this->_db_table->fetchAll($select);
        return $rows;
    }

    public function delete($id){
        $result = $this->_db_table->find($id);
        
        //  if not found, throw an exception
        if( count($result) == 0 ) {
            throw new Exception('Document not found');
        }

        $where = $this->_db_table->getAdapter()->quoteInto('id = ?', $id);
 
        $this->_db_table->delete($where);
        
    }
}
