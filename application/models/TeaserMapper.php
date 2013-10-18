<?php

class Application_Model_TeaserMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Teaser');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Teaser $teaser)
    {
        $data = array(
            'email'   => $teaser->getEmail(),
            'active' => $teaser->getActive(),
            'datetime' => date('Y-m-d H:i:s'),
            'validation' => $teaser->getValidation(),
        );
 
        //if (null === ($id = $guestbook->getId())) {
        //    unset($data['id']);
            $this->getDbTable()->insert($data);
        //} else {
        //    $this->getDbTable()->update($data, array('id = ?' => $id));
        //}
    }
 
    public function find($email, Application_Model_Teaser $teaser)
    {
        $result = $this->getDbTable()->find($email);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $teaser->setEmail($row->email)
                  ->setActive($row->active)
                  ->setDatetime($row->datetime)
                  ->setValidation($row->validation);
        return $teaser;
    }
    
    public function exists($email) {
        $result = $this->getDbTable()->find($email);
        if (0 == count($result)) {
            return false;
        }
        return true;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Teaser();
            $entry->setEmail($row->email)
                  ->setActive($row->active)
                  ->setDatetime($row->datetime)
                  ->setValidation($row->validation);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function update(Application_Model_Teaser $teaser){
        $data = array(
            'email'   => $teaser->getEmail(),
            'active' => $teaser->getActive(),
            'datetime' => date('Y-m-d H:i:s'),
            'validation' => $teaser->getValidation(),
        );
        $result = $this->getDbTable()->update($data, array('email = ?' => $teaser->getEmail()));
        
    }

}

