<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category    ZendX
 * @package     ZendX_JQuery
 * @subpackage  View
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license     http://framework.zend.com/license/new-bsd     New BSD License
 * @version     $Id: DatePicker.php 20165 2010-01-09 18:57:56Z bkarwin $
 */

/**
 * @see ZendX_JQuery_Form_Element_UiWidget
 */
require_once "UiWidget.php";

/**
 * Form Element for jQuery DatePicker View Helper
 *
 * @package    ZendX_JQuery
 * @subpackage Form
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
  */
class ZendX_JQuery_Form_Element_MaskedInput extends ZendX_JQuery_Form_Element_UiWidget
{
    public $helper = "maskedInput";

    /**
     * jQuery related parameters of this form element.
     *
     * @var array
     */
    public $jQueryParams = array();

    /**
     * Just here to prevent errors.
     *
     * @var array
     */
    public $options = array();

    /**
     * Constructor
     *
     * @param  mixed $spec
     * @param  mixed $options
     * @return void
     */
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath('ZendX_JQuery_Form_Decorator', 'ZendX/JQuery/Form/Decorator', 'decorator');
        parent::__construct($spec, $options);
    }

    /**
     * Load default decorators
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
        return; //Força o objeto a carregar as classes com $this->setElementDecorators
        
        /*
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
        
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('UiWidgetElement')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                 ->addDecorator('HtmlTag', array('tag' => 'dd'))
                 ->addDecorator('Label', array('tag' => 'label'));
        }
        */

    }

    /**
     * Seta os decoradores
     *
     * @return class ZendX_JQuery_Form_Element_MaskedInput
     */
    public function setElementDecorators(array $Decorators)
    {
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            unset($Decorators[0]);
            
            $this->addDecorator('UiWidgetElement')
                 ->addDecorator('Errors');
            
            foreach($Decorators as $Decorator){
                $this->addDecorator($Decorator[0], $Decorator[1]);
            }
        }
        return $this;
    }
}
?>