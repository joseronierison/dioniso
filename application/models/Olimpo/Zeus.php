<?php
/**
 * Classe especializada em fazer busca de banco de dados, listando até os que
 * que não são correntes.
 * 
 * @abstract
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 15.07.2013
 */
abstract class Olimpo_Zeus extends Olimpo_Zeus_Hades {
    
    /**
     * Pega todos os banco de dados de um banco de dados
     * 
     * @param type $database Data base do sitio
     */
    public function getDbs(Target_Database $database)
    {
        try {
            //Lista os bancos apartir da informação da tabela information_schema.schemata
            $dbs = $this->getDbInfo('schema_name', 'information_schema.schemata');
        } catch (Exception $exc) {
            throw $exc;
            return;
        }
        
        if(count($dbs) > 0){
            foreach($dbs as $dbname){
                $Db = new Target_Database_Db($dbname);
                
                $database->addDb(clone($Db));                
            }
        }
    }
}
?>
