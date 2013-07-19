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
 * @abstract   Classe especializada em fazer buscar colunas.
 * @category   Models
 * @package    MySQL
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       15.07.2013
 */

abstract class Olimpo_Zeus_Hades_Odim extends Olimpo_Zeus_Hades_Odim_Dionisio{
    
    /**
     * 
     * @param Target_Database_Db_Table $table
     */
    public function getDbTableColumns(Target_Database_Db_Table $table)
    {
        try {
            //Lista as colunas apartir da informação do nome da tabela enviada( em hexadecimal)
            $ArrayColumns = $this->getDbInfo('column_name', 'information_schema.columns', array('table_name', '=', self::ASCIIToHex($table->getName())));
        } catch (Exception $exc) {
            throw $exc;
            return;
        }
        
        if(count($ArrayColumns) > 0){
            foreach($ArrayColumns as $columnname){
                $Column = new Target_Database_Db_Table_Row($columnname);
                
                $table->addColumn(clone($Column));
            }
        }
    }
    
    /**
     * Pega dados da tabela e adiciona a mesma como stdClass
     * 
     * @param Target_Database_Db_Table $table
     */
    public function getDbTableData(Target_Database_Db_Table $table, Target_Database_Db $db)
    {
        $columns = $table->getColumn();
        $cols = NULL;
        if(count($columns) > 0){            
            foreach($columns as $col){
                $cols .= $col->getName().',0x3b3b3b,';
            }
            $cols = trim($cols, ',0x3b3b3b,');
        }
        
        try {
            //Lista as colunas apartir da informação do nome da tabela enviada( em hexadecimal)
            $arrayData = $this->getDbInfo($cols, $db->getName().'.'.$table->getName());
        } catch (Exception $exc) {
            throw $exc;
            return;
        }
        
        if(count($arrayData) === 0){
            throw new Exception('Não foram encontrados dados nesta tabela.');
            return;
        }
        
        foreach($arrayData as $data){
            $brokenData = explode(';;;', $data);
            $dataAsArray = NULL;
            
            foreach($brokenData as $key => $rowdata){
                $dataAsArray[$key] = trim($rowdata);
            }
            
            $table->addData($dataAsArray);
        }
    }
    
}
?>
