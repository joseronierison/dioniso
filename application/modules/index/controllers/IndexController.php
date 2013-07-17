<?php

/**
 * Controlador de visualização da página principal.
 * 
 * @author José Roniérison <ronierison.silva@gmail.com>
 * @data 14.07.2013
 */
class Index_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->js = array(
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-transition.js'),
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-alert.js'),
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-modal.js'),
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-scrollspy.js'),
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-dropdown.js'),
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-tab.js'),
            array('src' => 'public/twitter-bootstrap-v2.0.3/js/bootstrap-tooltip.js'),
            array('src' => 'application/modules/index/views/scripts/index/js/status-bar.js'),
            array('src' => 'application/modules/index/views/scripts/index/js/main-events.js'),
            array('src' => 'application/modules/index/views/scripts/index/js/mysql.js')
        );
        
        $this->view->css = array(
            array('href' => 'application/modules/index/views/scripts/index/css/main.css')  
        );
    }

    /**
     * Mostra modal de carregamento
     */
    public function loadingAction()
    {
        $this->getHelper('layout')->disableLayout(); 
    }
    
    /*
     * Renderiza pÃ¡gina de login
     */
    public function indexAction()
    {
        $this->view->titulo = "Hacker Tool By José Roniérison";
    }
    
    public function cancelarconsultaAction()
    {
        $this->getHelper('viewRenderer')->setNoRender();
        $this->getHelper('layout')->disableLayout();
        
        $defaultNamespace = new Zend_Session_Namespace('Target');
        $defaultNamespace->unsetAll();
        
        exit(Zend_Json::encode(array(
            'status' => true,
            'messages' => array(
                array(
                    'type' => 'dionisofala',
                    'label' => 'Cancelamento de requisição',
                    'message' => 'A requisição foi cancelada'
                )
            )
        )));
    }
    
}

?>
