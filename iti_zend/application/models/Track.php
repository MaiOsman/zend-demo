<?php

class Application_Model_Track extends Zend_Db_Table_Abstract
{
    protected $_name = 'track';

    function getAllTracks(){
        return $this->fetchAll()->toArray();
    }
    function addNewTrack($trackData)
    {
        $row = $this->createRow();
        $row->tr_name = $trackData['tr_name'];
        $row->save();
    }

}
