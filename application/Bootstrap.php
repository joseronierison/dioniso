<?php
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

		// Registra o banco de dados
		$registry = Zend_Registry::getInstance();
		$registry->set('db', $db);

		Zend_Db_Table::setDefaultAdapter($db);
	}catch(Zend_Db_Exception $e){
             //Zend_Debug::dump($e);
		echo "Não foi possíel realizar a conexão com o banco de dados.< br/> ".$e->getMessage();
		exit;
    	}
        
        //ñ é para estar aqui
        $this->_initAcl();
    }
    
    /*
     * Faz configurações referente aos logs
     */
    protected function _initLog()
    {
        
    }
    
    /*
     *
     */
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

    /*
     *
     */
    protected function _initAcl()
    {
        
    }
    
    /*
     * Inicializações referentes ao módule de autenticação
     * 
     * Funções:
     */
    protected function _initAuth()
    {
        
    }
}
?>