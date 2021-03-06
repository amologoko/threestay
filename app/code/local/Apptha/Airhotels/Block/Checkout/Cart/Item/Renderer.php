<?php
/**
 * @ Author      : Apptha team
 * @package      : Apptha_Airhotels
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Modified Date:June 2012
 * Description  : Inherited the class to show details like check in ,check out and Accomodates in cart page 
 */

class Apptha_Airhotels_Block_Checkout_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{
	/**
	 * Show in check in,check out and accomodates in cart table
	 * 
	 * @return array 
	 */
	public function getOptionList()
	{
               //check sytlish theme
                $current_theme = Mage::getStoreConfig('design/theme/default');
                if($current_theme == 'stylish')
                {  
		$fromdate =  Mage::getSingleton('core/session')->getFromdate();
		$todate = Mage::getSingleton('core/session')->getTodate();
		$accomodate = Mage::getSingleton('core/session')->getAccomodate();
		return array(
		array('label' => $this->__(''), 'value' => $accomodate),
		array('label' => $this->__(''), 'value' => date("d-m-Y",strtotime($todate)) ),
		array('label' => $this->__(''), 'value' => date("d-m-Y",strtotime($fromdate)) )
		);
                }
	}
}