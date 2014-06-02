<?php

class Application_Model_DbTable_Document extends Zend_Db_Table_Abstract
{

    protected $_name = 'document';
    protected $_primary = 'id';
    protected $_referenceMap    = array(
        'User' => array(
            'columns'           => array('submited_by'),
            'refTableClass'     => 'User',
            'refColumns'        => array('id')
        ),
    );
}

