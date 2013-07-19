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
 * @abstract   A classe abstrata Procurador_Estagiario_Aprendiz_Abstract é responsável por buscar as possíveis variáveis 
 *              $_GET para serem atacadas pelo estagiário.
 * @category   Models
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       14.07.2013
 */

abstract class Procurador_Estagiario_Aprendiz_Abstract {
    /**
     * Código de identificação do arquivo de modelo. 
     * @todo Identificador, código único.
     */
    const codeFile = 1;
    
    /**
     * Variáveis $_GET da url informada pelo usuário
     * @array of @strings
     */
    protected $_url_variables;
    
    /**
     * Url para ataque
     * @string
     */
    protected $_url;
    
    /**
     * Domínio que está sobre ataque
     * @var @string
     */
    protected $_domain;
    
    
    /**
     *  Recebe a URL enviada pelo usuário e descobre as possívels variáveis get 
     * para fazer o ataque.
     * @string $url -> url (ex.: http://www.target.com.br/detalhe-noticia.php?id=1)
     * 
     * @return NULL
     */
    protected function getUrlsVars()
    {
        if(empty($this->_url)){
            throw new Exception('Impossível realizar um ataque sem ter um alvo.', self::codeFile.'00001');
            return;
        }
        
        //Quebra a variável em duas apartir da interrogação (?)
        $brokenUrl = explode('?', $this->_url);
        
        $this->_domain = $brokenUrl[0];
        
        //Se houver algo após a interrogação (?)
        if(isset($brokenUrl[1])){
            $varsWd = explode('&', $brokenUrl[1]);
            
            if(count($varsWd)>0){
                foreach($varsWd as $varwd){
                    $var = explode('=', $varwd);
                    
                    $this->_url_variables[] = array($var[0], (isset($var[1])? $var[1] : NULL));
                }
            }
        }
    }
    
    /**
     * Seta URL para as requisições
     * 
     * @param array url Alvo a ser atacado $url
     * @return Procurador_Estagiario_Aprendiz_Abstract
     */
    public function setUrl($url)
    {
        $this->_url =  $url;
        return $this;
    }
    
    /**
     * Pega url enviada do alvo.
     * 
     * @return Nette\Utils\Strings
     */
    public function getUrl()
    {
        return $this->_url;
    }
}
?>
