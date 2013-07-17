<?php

/* include Zend Session */
require_once ('Zend/Session/Namespace.php');

/* inicializando a sessao */
//Zend_Session::start();
//
///* persistir a sessao por 1 mes */
//Zend_Session::rememberMe(60 * 60 * 24 * 7 * 4);


/*
 * Reporta todos os erros que ocorrerem :
 * Fase de desenvolvimento.
 */
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors',true);

/*
 *  Define as constantes do funcionamento do MVC
 */
$ActualPath = realpath(dirname(__FILE__));
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $ActualPath.'/application');
defined('ROOT_PATH') || define('ROOT_PATH', $ActualPath);
defined('PUBLIC_PATH') || define('PUBLIC_PATH', $ActualPath);

//temporário
//defined('USER_DIR') || define('USER_DIR', 'application/data/users');

/*
 *  Define o nivel da aplicaÃ§Ã£o
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Aqui são os include paths muito importante configuralos corretamente
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH, //inclui o diretório da aplicaÃ§Ã£o
    APPLICATION_PATH.'/libs', //inclui o diretório da library
    APPLICATION_PATH.'/models', // inclui pasta dos models
    APPLICATION_PATH.'/views/helpers/',
    get_include_path(), //inclui os demais includes paths já pre definidos
)));

/*
 * Zend_Application
 */
require_once 'Zend/Application.php';
########## INICIO DOS REQUIRES ################
require_once "Zend/Loader/Autoloader.php";
########## FINAL DOS REQUIRES ################


try{
/*
 * Essa predefiniÃ§Ã£o Ã© muito importante pois ela nos permite utilizar um recurso bem legal, 
 *  nÃ£o precisamos mais utilizar require, require_once ou include em nossos objetos desde
 *  que sigamos uma estrutura de NameSpaces.
 *  Ver mais detalhes em: http://www.marcosborges.com/blog/?page_id=132#NameSpace
*/
    $autoloader = Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
    
 // Cria a aplicaÃ§Ã£o, inicia e roda
    $application = new Zend_Application( APPLICATION_ENV, APPLICATION_PATH.'/configs/application.ini' );

    $application->bootstrap();
    //$application->run();

    $frontController = Zend_Controller_Front::getInstance();

    $frontController->setControllerDirectory(
        array(
            'index' => APPLICATION_PATH.'/modules/index/controllers',
            'documentation' => APPLICATION_PATH.'/modules/documentation/controllers',
            'triagem' => APPLICATION_PATH.'/modules/triagem/controllers',
            'mysql' => APPLICATION_PATH.'/modules/mysql/controllers',
     ));
    
    /*
     * Request Class
     */
    //Zend_Debug::dump($_GET);
   
    $request = new Zend_Controller_Request_Http();
    /*
     * Adiciona caminhoa para os helpers.
     */
    $view = Zend_Layout::getMvcInstance()->getView();
    $view->addHelperPath( 'application/views/helpers/');
    
    $Uri = TransformURI($_GET);
    
    $UriAux = explode('/', $Uri);
    $request->setRequestUri($Uri)
            ->setModuleName($UriAux[0])
            ->setControllerName($UriAux[1])
            ->setActionName($UriAux[2]);
    //Zend_Debug::dump($request);
    $frontController->throwExceptions(true); 
    
    
    $frontController->dispatch($request);
   
}catch(Exception $exc){
  //Zend_Debug::dump($exc);
  exit(Zend_Json::encode(array(
    'status' => false,
    'messages' => array(
        'type' => 'classe',
        'label' => 'Mensagem de erro',
        'message' => $exc->getMessage()
    )
  )));
}

/*
 * Recebe o valor de $_GET e transforma em URL amigável
 */
function TransformURI($Uri){
    $return = null;
    //Zend_Debug::dump($Uri);
    if(is_array($Uri)){
        if(isset($Uri['module'])){
            $return = '/'.strtolower($Uri['module']);
        }else{
            $return = '/index';
        }
        
        if(isset($Uri['controller'])){
            $return .= '/'.strtolower($Uri['controller']);
        }else{
            $return .= '/index';
        }

        if(isset($Uri['action'])){
            $return .= '/'.strtolower($Uri['action']);
        }else{
            $return .= '/index';
        }
        
    }
    
    return $return;
    
}


?>
