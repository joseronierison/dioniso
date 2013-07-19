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
 * @abstract   Classe especializada em fazer busca de banco de tabelas no banco de dados
 * @category   Models
 * @package    MySQL
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       15.07.2013
 */

abstract class Olimpo_Zeus_Hades extends Olimpo_Zeus_Hades_Odim {
    
    /**
     * Pega tabelas de um determinado banco de dados
     * 
     * @param Target_Database_Db $db
     */
    public function getDbTables(Target_Database_Db $db)
    {
       try {
            //Lista os tabelas apartir da informação do nome do banco enviado em hexadecimal
            $arraysTables = $this->getDbInfo('table_name', 'information_schema.tables', array('table_schema', '=', self::ASCIIToHex($db->getName())));
        } catch (Exception $exc) {
            throw $exc;
            return;
        }
        if(count($arraysTables) > 0){
            foreach($arraysTables as $tablename){
                $Table = new Target_Database_Db_Table($tablename);
                
                $db->addTable(clone($Table));
            }
        }
        //Zend_Debug::dump($tables);
    }
}

?>
