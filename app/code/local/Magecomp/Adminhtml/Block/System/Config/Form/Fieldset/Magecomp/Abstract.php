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

class Magecomp_Adminhtml_Block_System_Config_Form_Fieldset_Magecomp_Abstract
	extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    protected function _getFooterHtml($element)
    {
        $html = parent::_getFooterHtml($element);
        $html .= Mage::helper('adminhtml/js')->getScript("
            $$('td.form-buttons')[0].update('');
            $('{$element->getHtmlId()}' + '-head').setStyle('background: none;');
            $('{$element->getHtmlId()}' + '-head').writeAttribute('onclick', 'return false;');
            $('{$element->getHtmlId()}').show();
        ");
        return $html;
    }
}
