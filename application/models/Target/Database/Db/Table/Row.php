<?php

/**
 * Container de informações de colunas e dados de banco
 * 
 * @final
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 15.07.2013
 */

final class Target_Database_Db_Table_Row {
    /**
     * @var String Nome da coluna
     */
    protected $_name;
    
    
    /**
     * Construtor da class
     * 
     * @param type $columnname
     */
    public function __construct($columnname)
    {
        $this->_name = $columnname;
    }
    
    /**
     * Retorna Nome da coluna
     * @return String
     */
    public function getName()
    {
        return $this->_name;
    }
}
?>
