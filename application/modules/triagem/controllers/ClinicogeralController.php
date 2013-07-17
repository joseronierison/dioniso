<?php
/**
 * Controlador que faz a triagem dos dados para enviar módulo responsável pelo
 * melhor tratamento do ataque.
 * 
 * @author José Roniérison
 * @date 15.07.2013
 */
class Triagem_ClinicogeralController extends Zend_Controller_Action
{
    public function init()
    {
       $this->getHelper('viewRenderer')->setNoRender();
       $this->getHelper('layout')->disableLayout(); 
    }
    
    /**
     * Usa métodos do procurador para descobrir quem deverá assumir a missão.
     */
    public function examespreliminaresAction()
    {
        if(!isset($_POST['url'])){
            exit(Zend_Json::encode(array(
                'status' => false,
                'database' => 'Nenhuma',
                'messages' => array(
                    'type' => 'classe',                    
                    'label' => 'Mensagem',
                    'message' => 'Passe a URL para que possamos fazer a análise preliminar.'
                 )
            )));
        }
        
        $AtackIsPossible = false;
        
        $Database = new Target_Database();
        $Procurador = new Procurador($_POST['url']);
        
        $MySQLInjection = new MySQLInjection($Procurador);
        
        if($MySQLInjection->DBisMySQL()){
           $Database->setType('MySQL');
           $AtackIsPossible = true;
        }else{
            exit(Zend_Json::encode(array(
                'status' => $AtackIsPossible,
                'messages' => array(
                    array(
                    'type' => 'dionisofala',                    
                    'label' => 'Error',
                    'message' => 'Não foi possível descobrir qual a base de dados do site.'
                    )
                 )
             )));
        }
        
        $defaultNamespace = new Zend_Session_Namespace('Target');
        $defaultNamespace->unsetAll();
        
        if (! isset($defaultNamespace->MySQLInjection)) {
            $defaultNamespace->MySQLInjection = $MySQLInjection; // MysqlInfo
            $defaultNamespace->Database = $Database;
        }

        
        exit(Zend_Json::encode(array(
            'status' => $AtackIsPossible,
            'database' => $Database->getType(),
            'messages' => array(
                array(
                'type' => 'info',                    
                'label' => 'Alvo',
                'message' => $Procurador->getUrl()
                ),
                array(
                'type' => 'command',
                'label' => 'Comando Utilizado',
                'message' => $Procurador->getCommand()
                ),
                array(
                'type' => 'classe',
                'label' => 'Tipo de base dados',
                'message' => $Database->getType()
                ),
                array(
                'type' => 'classe',
                'label' => 'Variável $_GET (porta de entrada)',
                'message' => $Procurador->getTargetGetVar()
                )
            ),
        )));
        
    }
    
    
}

?>
