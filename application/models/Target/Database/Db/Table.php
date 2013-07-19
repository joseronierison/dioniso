<?php
/**
 * Dioniso, Analysis tool safety
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0-standalone.html
 *
 * @final: Container de informações de tabela de banco de dados.
 * @category   Models
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       15.07.2013
 */

final class Target_Database_Db_Table {
    /**
     * @var String Nome da tabela
     */
    protected $_name;
    
    /**
     * @var array of Target_Database_Db_Table Tabelas_Row contidas nesta tabela
     */
    protected $_rows;
    
    /**
     * @var Array of array Dados da tabela
     */
    protected $_data;
    
    /**
     * Construtor da classe.
     * 
     * @param String $tableName Nome da tabela
     */
    public function __construct($tableName)
    {
        $this->_name = $tableName;
    }
    
    /**
     * Adiciona coluna à tabela
     * 
     * @param Target_Database_Db_Table_Row $column
     * @return Target_Database_Db_Table
     */
    public function addColumn(Target_Database_Db_Table_Row $column)
    {
        $this->_rows[] = $column;
        return $this;
    }
    
    /**
     *  Pega colunas de uma tabela
     *
     * @param Integer|null, $key Key da coluna no array
     */
    public function getColumn($key = null)
    {
        if($key === null){
            return $this->_rows;
        }
        
        if(isset($this->_rows[$key])){
            return $this->_rows[$key];
        }
        
        return false;
    }
    
    /**
     * Limpa colunas
     * 
     * @param Integer $key Key do array de colunas
     * @return bollean
     */
    public function cleanColumn($key = null)
    {
        if($key === null){
            $this->_rows = null;
            return true;
        }
        
        if(isset($this->_rows[$key])){
            unset($this->_rows[$key]);
            return true;
        }
        
        return false;
    }
    
    /**
     * @return String Nome da tabela
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Adiciona registro à tabela
     * 
     * @param array $data
     * @return \Target_Database_Db_Table
     */
    public function addData(array $data)
    {
        $this->_data[] = $data;
        return $this;
    }
    
    /**
     * Pega registro da tabela
     * 
     * @param Integer $key
     * @return bool | array
     */
    public function getData($key = null)
    {
        if($key === null){
            return $this->_data;
        }
        
        if(isset($this->_data[$key])){
            return $this->_data[$key];
        }
        
        return false;
    }
    
    /**
     * Exclui dados da tabela
     * 
     * @param type $key
     * @return boolean
     */
    public function cleanData($key = null)
    {
        if($key === null){
            $this->_data = null;
            return true;
        }
        
        if(isset($this->_data[$key])){
            unset($this->_data[$key]);
            return true;
        }        
        return false;
    }
}
?>
