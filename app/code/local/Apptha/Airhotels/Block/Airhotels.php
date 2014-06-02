<?php

/**
 *
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2012 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 */
class Apptha_Airhotels_Block_Airhotels extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        $title = Mage::getStoreConfig('airhotels/custom_group/airhotels_title');
        $this->getLayout()->getBlock('head')->setTitle($this->__($title));
        return parent::_prepareLayout();
    }

    public function getAirhotels() {
        if (!$this->hasData('airhotels')) {
            $this->setData('airhotels', Mage::registry('airhotels'));
        }
        return $this->getData('airhotels');
    }

    public function showratingCode($count=0) {
        for ($x = 1; $x <= $count; $x++) {
            echo "<img style='float:left'  src='" .$this->getSkinUrl('images/red.png'). "' width='16' height='16' alt='' />";
        }
        for ($i = $x; $i <= 5; $i++) {
            echo "<img style='float:left'  src='" .$this->getSkinUrl('images/grey.png'). "' width='16' height='16' alt=''/>";
        }
    }
    public function Airhotelshomepage()
    {
        $banner_count = (int) Mage::getStoreConfig('airhotels/custom_banner/banner_count');
        if ($banner_count) { $banner_count = 10; }
        $homepage_banner = Mage::getStoreConfig('airhotels/custom_banner/homepage_banner');
        switch($homepage_banner) {
        case 1:
               $_productCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', array('eq' => 1))
                        ->addAttributeToFilter('propertyapproved',array('eq' => 1))
                        ->setOrder('created_at', 'desc')
                        ->setPageSize($banner_count);
         break;
       case 2:
               $_productCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', array('eq' => 1))
                        ->addAttributeToFilter('banner', array('eq' => 1))
                         ->addAttributeToFilter('propertyapproved',array('eq' => 1))
                        ->setOrder('created_at', 'desc')
                        ->setPageSize($banner_count);
         break;
        case 3:
                $_productCollection = Mage::getResourceModel('reports/product_collection')
                     ->addAttributeToSelect('*')
                     ->addAttributeToFilter('status', array('eq' => 1))
                     ->addAttributeToFilter('type_id', array('eq' => 'property'))
                     ->addAttributeToFilter('name', array('neq' => ''))
                      ->addAttributeToFilter('propertyapproved',array('eq' => 1))
                     ->setPageSize($banner_count);
                         break;
        }
        $collection_count = $_productCollection->count();
        if(empty($collection_count))
        {
            $_productCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', array('eq' => 1))
                        ->addAttributeToFilter('propertyapproved',array('eq' => 1))
                        ->setOrder('created_at', 'desc')
                        ->setPageSize($banner_count);

        }
      return $_productCollection;

    }

    public function getPropertyReservedDays($productId){
        $result = Mage::getModel('airhotels/airhotels')->getPropertyReservedDays($productId);
        $dates = array();

        foreach ($result as $booking) {
            $from_date = $booking['fromdate'];
            $to_date = $booking['todate'];

            if ($from_date == $to_date) {
                $dates[] = $from_date;
                continue;
            } elseif ($to_date == date('Y-m-d', strtotime($from_date . '+ 1 day'))) {
                $dates[] = $from_date;
                $dates[] = $to_date;
                continue;
            } else {
                do {
                    $dates[] = $from_date;
                    $from_date = date('Y-m-d', strtotime($from_date . '+ 1 day'));
                } while ($from_date <= $to_date);
            }
        }
        $unavailCalendarDates = $this->getCalendarUnavailableDays($productId);
        return json_encode(array_merge($dates,$unavailCalendarDates));
    }

    function getCalendarUnavailableDays($productId){
        $unavailDatesFetched = Mage::getModel('airhotels/airhotels')->getCalendarUnavailableDays($productId);
        $unavailDates = array();
        foreach($unavailDatesFetched as $value){
            $unavailDates[] = date('Y-m-d',mktime (0,0,0,$value['month'],$value['blockfrom'],$value['year']));//$value['year'].'-'.$value['month'].'-'.$value['blockfrom$value['blockfrom']'];
        }
        return $unavailDates;
    }

}