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
 * @final: Container de informações de colunas e dados de banco.
 * @category   Models
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       15.07.2013
 */

final class Target_Database_Db_Table_Row {
    /**
     * @var String Nome da coluna
     */
    protected $_name;
    
    
    /**
     * Construtor da class
     * 
     * @param type $columnname
     */
    public function __construct($columnname)
    {
        $this->_name = $columnname;
    }
    
    /**
     * Retorna Nome da coluna
     * @return String
     */
    public function getName()
    {
        return $this->_name;
    }
}
?>
