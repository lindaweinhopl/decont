<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';
    protected $_primary = 'id';
    protected $_referenceMap    = array(
        'User' => array(
            'columns'           => array('created_by'),
            'refTableClass'     => 'User',
            'refColumns'        => array('id')
        ),
        'Role' => array(
            'columns'           => array('role'),
            'refTableClass'     => 'Role',
            'refColumns'        => array('id')
        )
    );
    protected $_dependentTables = array('Document', 'User');
}