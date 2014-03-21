<?php

class Ip_Swatches_Model_Resource_Input_Option extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $posttype_id = 'option_id';

    protected function _construct()
    {
        $this->_init('swatches/input_option', $this->posttype_id);
    }

    public function loadByAttribute($attr, $value)
    {
        $table = $this->getMainTable();
        $read = $this->_getReadAdapter();
        $where = $read->quoteInto("$attr = ?", $value);
        $sql = $read->select()
            ->from($table, array($this->posttype_id))
            ->where($where);
        return $read->fetchOne($sql);
    }

}