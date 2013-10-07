<?php
/**
 * @ Author     : Apptha team
 * @package     : Apptha_Airhotels
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 */
class Apptha_Airhotels_Model_Source_Banner extends Varien_Object
{
     public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>'Recent Property'),
            array('value' => 2, 'label'=>'Selected Property'),
            array('value' => 3, 'label'=>'Popular Property'),
        );
    }
}