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

class Magecomp_Adminhtml_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getDateForFilename()
    {
        return Mage::getSingleton('core/date')->date('Y-m-d_H-i-s');
    }
}