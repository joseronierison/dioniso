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
 * @category   Index
 * @package    Main
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       14.07.2013
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

/*
 *  Define o nivel da aplicação
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Aqui são os includes paths.
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH, //inclui o diretório da aplicação
    APPLICATION_PATH.'/libs/Zend', // inclui arquivos do Zend
    APPLICATION_PATH.'/libs', //inclui o diretório da library
    APPLICATION_PATH.'/models', // inclui pasta dos models
    APPLICATION_PATH.'/views/helpers/',
    get_include_path(), //inclui os demais includes paths já pre definidos
)));


/* include Zend Session */
require_once ('Zend/Session/Namespace.php');

/*
 * Zend_Application
 */
require_once 'Zend/Application.php';
########## INICIO DOS REQUIRES ################
require_once "Zend/Loader/Autoloader.php";
########## FINAL DOS REQUIRES ################


try{
   $autoloader = Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
    
    // Cria a aplicação, inicia e roda
    $application = new Zend_Application( APPLICATION_ENV, APPLICATION_PATH.'/configs/application.ini' );

    $application->bootstrap();

    $frontController = Zend_Controller_Front::getInstance();

    $frontController->setControllerDirectory(
        array(
            'index' => APPLICATION_PATH.'/modules/index/controllers',
            'documentation' => APPLICATION_PATH.'/modules/documentation/controllers',
            'triagem' => APPLICATION_PATH.'/modules/triagem/controllers',
            'mysql' => APPLICATION_PATH.'/modules/mysql/controllers',
     ));
    
   
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
    
    $frontController->throwExceptions(true); 
    
    
    $frontController->dispatch($request);
   
}catch(Exception $exc){
  exit(Zend_Json::encode(array(
    'status' => false,
    'messages' => array(
        'type' => 'classe',
        'label' => 'Erro',
        'message' => $exc->getMessage()
    )
  )));
}

/*
 * Recebe o valor de $_GET e transforma em URL amigável
 */
function TransformURI($Uri){
    $return = null;
    
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
