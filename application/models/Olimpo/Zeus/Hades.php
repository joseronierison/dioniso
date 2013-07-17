<?php
/**
 * Classe especializada em fazer busca de banco de tabelas no banco de dados
 * 
 * @abstract
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 15.07.2013
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
