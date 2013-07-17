<?php
/**
 * @todo @final: Classe final do procurador. Faz busca no HTML da resposta, 
 * procurando pelas informações requeridas pelo SQL Injection.
 * 
 * @author José Roniérison
 * @date 14.07.2013
 */

final class Procurador extends Procurador_Estagiario_Abstract {
    /**
     * Código de identificação do arquivo de modelo. 
     * @todo Identificador, código único.
     */
    const codeFile = 3;
    
    /**
     * Método construtor
     * 
     * @param String $url Url do alvo a ser atacado
     */
    public function __construct($url) {
        
        $this->setUrl($url)
                ->getUrlsVars();
        
        $this->saveCleanHTMLTags()
                ->discoverInjectionInfo();
    }
}
?>
