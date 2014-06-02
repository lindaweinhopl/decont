<?php

class Application_Model_DbTable_Appdata extends Zend_Db_Table_Abstract
{

    protected $_name = 'appdata';
    protected $_primary = 'id';
    protected $_referenceMap    = array(
        'User' => array(
            'columns'           => array('modified_by'),
            'refTableClass'     => 'User',
            'refColumns'        => array('id')
        ),
    );
}

