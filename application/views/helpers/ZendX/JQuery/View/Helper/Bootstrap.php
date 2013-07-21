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
 * Bootstrap do Zend Framework
 * 
 * @category   Views
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       15.07.2013
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload(){
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
    }

    protected function _init()
    {
        
    }

    /**
     * Tem que ser a primeira função a ser inicializada.
     * Cria o adaptador de conexão para o banco de dados.
     */
    protected function _initConnection()
    {
        $config = new Zend_Config_Ini('configs/application.ini', APPLICATION_ENV);
        try{
		$db = Zend_Db::factory($config->db);

		$registry = Zend_Registry::getInstance();
		$registry->set('db', $db);

		Zend_Db_Table::setDefaultAdapter($db);
	}catch(Zend_Db_Exception $e){
		echo "Não foi possíel realizar a conexão com o banco de dados.< br/> ".$e->getMessage();
		exit;
    	}
        
    }
    
   
    protected function _initView()
    {
        $view = new Zend_View ();

        ZendX_JQuery::enableView( $view );
        $viewrenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewrenderer->setView( $view );
        Zend_Controller_Action_HelperBroker::addHelper( $viewrenderer );
        
        $this->bootstrap ( "layout" );
        $layout = $this->getResource ( 'layout' );
        $view = $layout->getView ();
        $view->addHelperPath ( 'ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper' );
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer ();
        $viewRenderer->setView ( $view );
        Zend_Controller_Action_HelperBroker::addHelper ( $viewRenderer );
    }

}
?>