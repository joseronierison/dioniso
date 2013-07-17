<?php
/**
 * JRSS-Software
 *
 * LICENSE
 *
 * Helper desensolvido para Zend_Framework, fazendo intera��o do zendo com o
 * maskedinput do JQuery
 *
 * @category    ZendX
 * @package     ZendX_JQuery
 * @subpackage  View
 * @copyright  Copyright (c) 2012 JRSS-Software
 * @version     1.0
 */

/**
 * @see Zend_Registry
 */
require_once "Zend/Registry.php";

/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/**
 * jQuery Date Picker View Helper
 *
 * @uses 	   Zend_View_Helper_FormText
 * @package    ZendX_JQuery
 * @subpackage View
 * @copyright  Copyright (c) 2012 JRSS-Software
 */
class ZendX_JQuery_View_Helper_MaskedInput extends ZendX_JQuery_View_Helper_UiWidget
{
    
    /**
     * Make a jQuery MaskedInput
     *
     * @link   http://plugins.jquery.com/index.php?q=project/maskedinput
     * @param  string $id
     * @param  string $value
     * @param  array  $params jQuery Widget Parameters
     * @param  array  $attribs HTML Element Attributes
     * @return string
     */
    public function MaskedInput($id, $value = null, array $params = array(), array $attribs = array())
    {
        $attribs = $this->_prepareAttributes($id, $value, $attribs);

        if(!isset($params['maskformat'])){
            require_once "ZendX/JQuery/Exception.php";
            throw new ZendX_JQuery_Exception("Cannot create the mask if won't give mask format.");
        }

        $MaskFormat = $params['maskformat'];
        unset($params['maskformat']);

        $params = ZendX_JQuery::encodeJson($params);

                
        $js = sprintf('%s("#%s").mask("%s",%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $MaskFormat,
                $params
        );

        $this->jquery->addOnLoad($js);

        return $this->view->formText($id, $value, $attribs);
    }


}