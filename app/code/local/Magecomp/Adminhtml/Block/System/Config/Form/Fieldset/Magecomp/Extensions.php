<?php
/**
 * Magecomp
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magecomp EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magecomp.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magecomp.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.magecomp.com/ for more information
 * or send an email to sales@magecomp.com
 *
 * @category   Magecomp
 * @package    Magecomp_Adminhtml
 * @copyright  Copyright (c) 2009 Magecomp (http://www.magecomp.com/)
 * @license    http://www.magecomp.com/LICENSE-1.0.html
 */

/**
 * Magecomp Adminhtml extension
 *
 * @category   Magecomp
 * @package    Magecomp_Adminhtml
 * @author     Magecomp Dev Team <dev@magecomp.com>
 */

class Magecomp_Adminhtml_Block_System_Config_Form_Fieldset_Magecomp_Extensions
	extends Magecomp_Adminhtml_Block_System_Config_Form_Fieldset_Magecomp_Abstract
{
	protected $_dummyElement;
	protected $_fieldRenderer;
	protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
		$html = $this->_getHeaderHtml($element);

		$modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());

		sort($modules);

        foreach ($modules as $moduleName) {
            $name = explode('_', $moduleName, 2);
        	if (!isset($name) || $name[0] != 'Magecomp') {
        		continue;
        	}
        	$html.= $this->_getFieldHtml($element, $moduleName);
        }
        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    protected function _getFieldRenderer()
    {
    	if (empty($this->_fieldRenderer)) {
    		$this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
    	}
    	return $this->_fieldRenderer;
    }

    protected function _getFooterHtml($element)
    {
        $html = parent::_getFooterHtml($element);
        $html = '<h4>' . $this->__('Installed Magecomp Extensions') . '</h4>' . $html;

        return $html;
    }

    protected function _getFieldHtml($fieldset, $moduleName)
    {
    	$moduleConfig = Mage::getConfig()->getNode('modules/' . $moduleName);

        $field = $fieldset->addField($moduleName, 'label',
            array(
                'name'          => $moduleName,
                'label'         => $moduleConfig->extension_name,
                'value'         => 'v' . $moduleConfig->version,
            ))->setRenderer($this->_getFieldRenderer());

		return $field->toHtml();
    }
}
