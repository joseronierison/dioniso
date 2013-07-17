<?php

/**
 * Container de informações do banco de dados
 * 
 * @final
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 15.07.2013
 */

final class Target_Database_Db {
    /**
     * @var String Nome da base de dados
     */
    protected $_name;
    
    /**
     * @var array of Target_Database_Db_Table Tabelas contidas nesse banco de dados
     */
    protected $_tables;
    
    /**
     * Construtor da classe.
     * Precisa de um nome para ser inicializado.
     * 
     * @param String $tableName Nome da tabela
     */
    public function __construct($dbName)
    {
        $this->_name = $dbName;
    }
    
    /**
     * Adiciona tabela encontrada ao banco de dados
     * 
     * @param Target_Database_Db_Table $table Objeto tabela para ser adicionado
     */
    public function addTable(Target_Database_Db_Table $table)
    {
        $this->_tables[] = $table;
        return $this;
    }
    
    /**
     * Destrói todas as tabelas do banco ou uma com
     * @param Integer $key Key da tabela específica
     * @return boolean
     */
    public function cleanTables($key = null)
    {
        if($key === null){
            $this->_tables = null;
            return true;
        }
        
        if(isset($this->_tables[$key])){
            unset($this->_tables[$key]);
            return true;
        }
        
        return false;
    }
    
     /**
     * Pega tabela(s) do banco de dados
      * 
     * @param Integer $key Key de registro da tabela
     * @return bool | false
     */
    public function getTable($key = null)
    {
        if(isset($this->_tables[$key])){
            return $this->_tables[$key];
        }
        
        if($key === null){
            return $this->_tables;
        }
        
        return false;
    }
    
    /**
     * @return String Nome da base de dados
     */
    public function getName()
    {
        return $this->_name;
    }
}
?>
