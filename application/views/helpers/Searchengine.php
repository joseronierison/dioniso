<?
/*
 * Helper de busca
 * 
 * @author José Roniérison
 * @date 17 de Junho de 2013
 */
class Zend_View_Helper_Searchengine extends Zend_View_Helper_Abstract
{
    private $_tablesInfo;
    /*
     * Objeto da tabela principal
     */
    protected $_mainTableObject = null;
    
    /*
     * Relacionamentos
     */
    protected $_relationships = null;
    
    /*
     * Div que receberá o conteúdo da busca
     */
    protected $_divId = null;
    
    /*
     * Campos de busca na tabela principal
     */
    protected $_mainTableSearchRows = null;
    
    
    /*
     * search moda
     */
    protected $_allRowsToSearch = true;
    
    /*
     * select rows
     */
    protected $_allRowsToSelect = true;
    
    protected $_searchTable = null;
    
    public function searchengine($mainTableObject = null, $options = null)
    {
        $this->setMainTableObject($mainTableObject)
                ->addTableInfo('main', $mainTableObject, $options);        
        
        $this->_searchTable = array(
            'tags' => array('class' => 'table table-hover'),
            'tr' => array('tags' => '')        
        );
        
        return $this;   
    }
    
    /*
     * 
     */
    public function draw()
    {
        echo '
            <script type="text/javascript" src="application/layouts/common/js/searchengine.js"></script>
            <div class="well"><center />
                <form class="form-search" name="search-engine-form" id="serachengine-form" method="post" />
                  <input type="text" id="search-key-input" class="input-xlarge search-query" placeholder="Digite uma chave para busca" />
                  <button onclick="search();" id="btn-search" type="button" class="btn btn-info"><i class="icon-search"></i></button>
                  
                </form>
                <p>+ <a href="#advanced-search" id="link-advanced-search"> Pesquisa Avançada</a></p>
                </center>
              </div>
              
              ';
        //Zend_Debug::dump($this->_tablesInfo);
    }
    
    /*
     * Faz a busca
     * $keys => array(
     *   rowname => array(
     *      m
     *   )
     * );
     */
    public function search($keys)
    {
        
    }
    
    public function drawSearchData($data)
    {
        
    }
    
    /*
     * $options = array(
     *  'rowname' => array(
     *     'visible' => default true,
     *     'searchable' => default true
     *     'name' => default[row_name)
     *     'relationship' => array(
     *          array ('rows' => table.name, 'name' => 'table.row', 'mode' => default multselect)     *          
     *     )
     *     'enum' => array(
     *          'mode' => default checkbox
     *     )
     *   )
     * );
     */
    private function addTableInfo($name, $object, $options = null)
    {
        $metadata = $object->info('metadata');
        
        if($options != null){
            foreach($metadata as $rowname => $rowinfo){
                if(array_key_exists($rowname, $options) !== false){
                    $metadata[$rowname]['OPTIONS'] = $options[$rowname];
                }
            }
        }
        
        $this->_tablesInfo[$name] = $metadata;
        return $this;
    }
    
    public function setMainTableObject($mainTableObject)
    {
        $this->_mainTableObject = $mainTableObject;
        return $this;
    }
    
    public function addRelationship($intKey, $forKey, $options = null)
    {
        $this->_relationships[] = array('intkey' => $intKey, 'forkey' => $forKey, 'options' => $options);
        return $this;
    }
    
    public function setDivId($divId)
    {
        $this->_divId = $divId;
        return $this;
    }
    
    public function setSearchMode($searchMode)
    {
        $this->_allRowsToSearch = (bool)$searchMode;
        return $this;
    }
    
    public function setSelectMode($selectMode)
    {
        $this->_allRowsToSelect = $selectMode;
        return $this;
    }
}
?>
