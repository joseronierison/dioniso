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
 * @final: Classe final do procurador. Faz busca no HTML da resposta, 
 * procurando pelas informações requeridas pelo SQL Injection.
 * @category   Models
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       14.07.2013
 */

final class Procurador extends Procurador_Estagiario_Abstract {
    /**
     * Código de identificação do arquivo de modelo.
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
