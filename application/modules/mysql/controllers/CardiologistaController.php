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
 * * Controlador de processamento MySQL.
 * 
 * @category   Controllers
 * @package    MySQL
 * @copyright  Copyright (c) 2013 José Roniérison <ronierison.silva@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html GPL v3
 * @version    1.0
 * @date       14.07.2013
 */

class Mysql_CardiologistaController extends Zend_Controller_Action
{
    /**
     *
     * @var type 
     */
    protected $_default_namespace;
    
    public function init()
    {
       $this->getHelper('viewRenderer')->setNoRender();
       $this->getHelper('layout')->disableLayout();
       
       $this->_default_namespace = new Zend_Session_Namespace('Target');
        
        if(!isset($this->_default_namespace->MySQLInjection)){
            exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'dionisofala',                    
                        'label' => 'Error',
                        'message' => 'Não foi possível encontrar uma sessão aberta'
                    )
                ),
            )));
        }
    }
    
    
    /**
     * Usa funções do MySQL para descobrir o número do colunas para comando UNION.
     */
    public function contanumerodecolunasAction()
    {
        $MySQLInjection = $this->_default_namespace->MySQLInjection;
        
        $MySQLInjection->countColumns();
        
        $numberOfColumns = $MySQLInjection->getNumberOfColumns();
        
        if($numberOfColumns <= 0){
            exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'command',                    
                        'label' => 'Comando',
                        'message' => $MySQLInjection->getLastCommand()
                    ),
                    array(
                        'type' => 'classe',                    
                        'label' => 'Resultado',
                        'message' => 'Não foi possível encontrar o número de colunas.'
                    ),
                    array(
                        'type' => 'dionisofala',                    
                        'label' => 'Error',
                        'message' => 'Não foi possível encontrar o número de colunas'
                    )
                ),
            )));
        }
        
        
        exit(Zend_Json::encode(array(
            'status' => true,
            'messages' => array(
                array(
                    'type' => 'command',                    
                    'label' => 'Comando',
                    'message' => $MySQLInjection->getLastCommand()
                ),
                array(
                    'type' => 'classe',                    
                    'label' => 'Número de colunas',
                    'message' => $numberOfColumns
                ),
                array(
                    'type' => 'classe',                    
                    'label' => 'Coluna de saída',
                    'message' => $MySQLInjection->getOutPutColumn()
                )
            ),
        )));
    }
    
    /**
     * Descobre a versão do banco de dados
     */
    public function examinaversaodobancoAction()
    {
        $defaultNamespace = new Zend_Session_Namespace('Target');
 
        $MySQLInjection = $defaultNamespace->MySQLInjection;
        $Database = $defaultNamespace->Database;
        
        try {
            $MySQLInjection->getVersion($Database);
        } catch (Exception $exc) {
            exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'command',
                        'label' => 'Comando',
                        'message' => $MySQLInjection->getLastCommand()
                    ),
                    array(
                        'type' => 'classe',
                        'label' => 'Exception message',
                        'message' => $exc->getMessage()
                    ),
                    array(
                        'type' => 'dionisofala',                    
                        'label' => 'Error',
                        'message' => 'Não foi possível encontrar versão do banco de dados'
                    )
                 )
            )));
        }

        
        exit(Zend_Json::encode(array(
            'status' => true,
            'messages' => array(
                array(
                    'type' => 'command',
                    'label' => 'Comando',
                    'message' => $MySQLInjection->getLastCommand()
                ),
                array(
                    'type' => 'classe',
                    'label' => 'Versão do banco de dados',
                    'message' => $Database->getVersion()
                )                   
             )
        )));
    }
    
    public function extracaodebancosAction()
    {
        $defaultNamespace = new Zend_Session_Namespace('Target');
        
        $MySQLInjection = $defaultNamespace->MySQLInjection;
        $Database = $defaultNamespace->Database;
        
        try {
            $MySQLInjection->getDbs($Database);
        } catch (Exception $exc) {            
            exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'command',
                        'label' => 'Comando',
                        'message' => $MySQLInjection->getLastCommand()
                    ),
                    array(
                        'type' => 'classe',
                        'label' => 'Exception message',
                        'message' => $exc->getMessage()
                    ),
                    array(
                        'type' => 'dionisofala',                    
                        'label' => 'Error',
                        'message' => 'Não foi possível encontrar versão dos bancos do site'
                    )
                 )
            )));
        }
        
        $dbs = $Database->getDb();
        foreach($dbs as $key => $db){
            $dbsname[$key] = $db->getName();
        }
        
        exit(Zend_Json::encode(array(
            'status' => true,
            'dbs' => $dbsname,
            'messages' => array(
                array(
                    'type' => 'command',
                    'label' => 'Comando',
                    'message' => $MySQLInjection->getLastCommand()
                ),
                array(
                    'type' => 'classe',
                    'label' => 'Número de bancos',
                    'message' => count($dbs)
                )                   
             )
        )));
   }
   
   /**
    * Extraí tabelas do banco
    */   
   public function extracaodetabelasAction()
   {
       if(!isset($_POST['db_key'])){
           exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'dionisofala',
                        'label' => 'Error ',
                        'message' => 'De qual banco de dados devo fazer a consulta de tabelas? Nenhum fôra informado!'
                    )
                 )
            )));
       }
       
       $defaultNamespace = new Zend_Session_Namespace('Target');
        
        $MySQLInjection = $defaultNamespace->MySQLInjection;
        $Database = $defaultNamespace->Database;
        
        $db = $Database->getDb($_POST['db_key']);
        
        if($db !== false){
            $db->cleanTables();
            
            try {
                $MySQLInjection->getDbTables($db);
            } catch (Exception $exc) {
                exit(Zend_Json::encode(array(
                    'status' => false,
                    'messages' => array(
                        array(
                            'type' => 'command',
                            'label' => 'Comando',
                            'message' => $MySQLInjection->getLastCommand()
                        ),
                        array(
                            'type' => 'dionisofala',
                            'label' => 'Exception message',
                            'message' => $exc->getMessage()
                        )
                     )
                )));
            }
        }
        
        $tables = $db->getTable();
        
        if($tables === false){
           exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'command',
                        'label' => 'Comando',
                        'message' => $MySQLInjection->getLastCommand()
                    ),
                    array(
                        'type' => 'classe',
                        'label' => 'Error',
                        'message' => 'Não foi possível encontrar nenhuma tabela para este banco'
                    ),
                    array(
                        'type' => 'dionisofala',
                        'label' => 'Error',
                        'message' => 'Não foi possível encontrar nenhuma tabela para este banco'
                    )
                 )
            )));
        }
        
        foreach($tables as $table){
            $arrayTables[] = $table->getName();
        }
        
        exit(Zend_Json::encode(array(
            'status' => true,
            'tables' => $arrayTables,
            'dbname' => $db->getName(),
            'messages' => array(
                array(
                    'type' => 'command',
                    'label' => 'Comando',
                    'message' => $MySQLInjection->getLastCommand()
                ),
                array(
                    'type' => 'classe',
                    'label' => 'Tabelas encontradas em "'.$db->getName().'"',
                    'message' => count($arrayTables)
                )
             )
        )));    
   }
   
   /**
    * 
    */
   public function extracaodecolunasAction()
   {
       $defaultNamespace = new Zend_Session_Namespace('Target');
       
       if(!isset($_POST['table_key']) || !isset($_POST['db_key']) 
               || !isset ($defaultNamespace->MySQLInjection) 
               || ! isset($defaultNamespace->Database)){
           exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'dionisofala',
                        'label' => 'Error ',
                        'message' => 'Requisição inválida. Cade o nome da tabela do banco de dados?? Nenhum fôra informado!'
                    )
                 )
            )));
       }
        
       $MySQLInjection = $defaultNamespace->MySQLInjection;
       $Database = $defaultNamespace->Database;
        
       $db = $Database->getDb($_POST['db_key']);
        
        if($db !== false){
            $table = $db->getTable($_POST['table_key']);
            
            if($table !== false){
                $table->cleanColumn();
                
                try {
                    $MySQLInjection->getDbTableColumns($table);
                } catch (Exception $exc) {
                    exit(Zend_Json::encode(array(
                        'status' => false,
                        'messages' => array(
                            array(
                                'type' => 'command',
                                'label' => 'Comando',
                                'message' => $MySQLInjection->getLastCommand()
                            ),
                            array(
                                'type' => 'classe',
                                'label' => 'Exception message',
                                'message' => $exc->getMessage()
                            ),
                            array(
                                'type' => 'dionisofala',
                                'label' => 'Exception message',
                                'message' => $exc->getMessage()
                            )
                         )
                    )));
                }
            }
        }
        
        $columns = $table->getColumn();
        
        if($columns === false){
            exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'command',
                        'label' => 'Comando',
                        'message' => $MySQLInjection->getLastCommand()
                    ),
                    array(
                        'type' => 'classe',
                        'label' => 'Colunas',
                        'message' => 'Nenhum coluna encontrada para esta tabela'
                    ),
                    array(
                        'type' => 'dionisofala',
                        'label' => 'Colunas',
                        'message' => 'Nenhum coluna encontrada para esta tabela'
                    )
                    
                 )
            )));
        }
        
        foreach($columns as $column){
            $arrayColumns[] = $column->getName();
        } 
        
        exit(Zend_Json::encode(array(
            'status' => true,
            'columns' => $arrayColumns,
            'messages' => array(
                array(
                    'type' => 'command',
                    'label' => 'Comando',
                    'message' => $MySQLInjection->getLastCommand()
                ),
                array(
                    'type' => 'classe',
                    'label' => 'Número de colunas de "'.$table->getName().'"',
                    'message' => count($arrayColumns)
                )
             )
        )));     
   }
   
   /**
    * Extrai dados de uma determinada tabela
    */
   public function extracaodedadosAction()
   {
        $defaultNamespace = new Zend_Session_Namespace('Target');
       
       if(!isset($_POST['table_key']) || !isset($_POST['db_key']) 
               || !isset ($defaultNamespace->MySQLInjection) 
               || ! isset($defaultNamespace->Database)){
           exit(Zend_Json::encode(array(
                'status' => false,
                'messages' => array(
                    array(
                        'type' => 'dionisofala',
                        'label' => 'Error ',
                        'message' => 'Requisição inválida. Cade o nome da tabela do banco de dados?? Nenhum fôra informado!'
                    ),
                    array(
                        'type' => 'dionisofala',
                        'label' => 'Error ',
                        'message' => 'Há a possibilidade de falta registros de sessão também! =('
                    )
                 )
            )));
       }
       
        $MySQLInjection = $defaultNamespace->MySQLInjection;
        $Database = $defaultNamespace->Database;
       
        $db = $Database->getDb($_POST['db_key']);
        
        if($db !== false){
            $table = $db->getTable($_POST['table_key']);
            
            if($table !== false){
                $table->cleanData();
                
                try {
                    $MySQLInjection->getDbTableData($table, $db);
                } catch (Exception $exc) {
                    exit(Zend_Json::encode(array(
                        'status' => false,
                        'messages' => array(
                            array(
                                'type' => 'command',
                                'label' => 'Comando ',
                                'message' => $MySQLInjection->getLastCommand()
                            ),
                            array(
                                'type' => 'classe',
                                'label' => 'Exception ',
                                'message' => $exc->getMessage()
                            ),
                            array(
                                'type' => 'dionisofala',
                                'label' => 'Exception ',
                                'message' => $exc->getMessage()
                            )
                    ))));
                }
            }
        }
        
        $tableData = $table->getData();
        //Zend_Debug::dump($$TableData[0]);
        //Zend_Debug::dump($TableData);
        exit(Zend_Json::encode(array(
            'status' => true,
            'data' => $tableData,
            'messages' => array(
                array(
                    'type' => 'command',
                    'label' => 'Comando ',
                    'message' => $MySQLInjection->getLastCommand()
                ),
                array(
                    'type' => 'classe',
                    'label' => 'Dados encontrados na tabela "'.$table->getName().'"',
                    'message' => count($tableData)
                )
        ))));
   }
}
?>
