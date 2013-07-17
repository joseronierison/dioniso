<?php

/**
 * Classe de estrutura do banco de dados. Organizador de informações.
 * Contém bancos, que por sua vez contém tabelas, que por sua vez contem 
 * a lista de colunas e suas informações.
 * 
 * @final
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 14.07.2013
 */

final class Target_Database
{
    /**
     * @var String Versão do banco de dados
     */
    protected $_version;
    
    /**
     * @var String Tipo do banco de dados. ex.: MySQL, Postgree, MSSQL, Oracle ..
     */
    protected $_type;
    
    /**
     * @var Array Target_Database_Db de banco de dados
     */
    protected $_dbs;
    
    /**
     * Adiciona banco a database
     * @param Target_Database_Db $db Objeto de banco de dados
     * @return Target_Database
     */
    public function addDb(Target_Database_Db $db)
    {
        $this->_dbs[] = $db;
        return $this;
    }
    
    /**
     * Pega banco da database case informado a key e o registro exista, senão é
     * enviado todos os registro de banco.
     * 
     * @param Integer $key chave de localização do banco no array
     * @return Target_Database_Db | array | null
     */
    public function getDb($key = null)
    {
        if(isset($this->_dbs[$key])){
            return $this->_dbs[$key];
        }
        
        if($key === null){
            return $this->_dbs;
        }
        
        return false;
    }
    
    /**
     * Seta versão do banco
     * @param String $version Versão do banco de dados
     * @return Target_Database
     */
    public function setVersion($version)
    {
        $this->_version = $version;
        return $this;
    }
    
    /**
     * Pega versão do banco
     * @return String
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * Seta tipo do banco
     * @param String $version Tipo do banco de dados
     * @return Target_Database
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }
    
    /**
     * Pega tipo do banco
     * @return String
     */
    public function getType()
    {
        return $this->_type;
    }
    
    
}
?>
