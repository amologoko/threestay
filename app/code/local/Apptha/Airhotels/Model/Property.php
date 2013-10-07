<?php
/**
 * @ Author      : Apptha team
 * @package      : Apptha_Airhotels
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Modified Date:June 2012
 */
class Apptha_Airhotels_Model_Property extends Mage_Core_Model_Abstract {
    /* Get Property Collection */

    public function getpropertycollection() {
        $PropertyCollection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToFilter('type_id', array('eq' => 'property'))
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('userid')
                ->addAttributeToSelect('bedtype')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('Price')
                ->addAttributeToSelect('description')
                ->addAttributeToSelect('short_description')
                ->addAttributeToSelect('propertytype')
                ->addAttributeToSelect('amenity')
                ->addAttributeToSelect('totalrooms')
                ->addAttributeToSelect('propertyadd')
                ->addAttributeToSelect('privacy')
                ->addAttributeToSelect('status')
                ->addAttributeToSelect('city')
                ->addAttributeToSelect('state')
                ->addAttributeToSelect('country')
                ->addAttributeToSelect('cancelpolicy')
                ->addAttributeToSelect('pets')
                ->addAttributeToSelect('maplocation')
                ->addAttributeToSelect('accomodates')
                 ->addAttributeToSelect('propertyapproved');
        return $PropertyCollection;
    }

    /* Get Current trip */

    public function currentTrip() {

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $cusId = $customer->getId();
        $todayDate = strtotime(Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        $todayDate = date('Y/m/d', $todayDate);
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_write');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $booking_table = $tPrefix . 'airhotels_booking';
        $date_range = $read->select()
                ->from(array('ct' => $booking_table), array('ct.entity_id', 'ct.fromdate', 'ct.todate'))
                ->where('ct.customer_id =? ', $cusId)
                ->where('ct.fromdate <=?', $todayDate)
                ->where('ct.todate >=?', $todayDate)
                ->where('ct.order_status =?', '1');
        $range = $read->fetchAll($date_range);
        $count = count($range);
        for ($i = 0; $i <= $count; $i++) {
            $fromdate[] = $range[$i][fromdate];
            $todate[] = $range[$i][todate];
            $productId[] = $range[$i][entity_id];
        }
        return array($productId, $fromdate, $todate);
    }

    /* Get Previous trip */

    public function previousTrip() {

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $cusId = $customer->getId();
        $todayDate = strtotime(Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));

        $todayDate = date('Y/m/d', $todayDate);
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_write');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $booking_table = $tPrefix . 'airhotels_booking';
        $date_range = $read->select()
                ->from(array('ct' => $booking_table), array('ct.entity_id', 'ct.fromdate', 'ct.todate'))
                ->where('ct.customer_id =? ', $cusId)
                ->where('ct.fromdate <?', $todayDate)
                ->where('ct.todate <?', $todayDate)
                ->where('ct.order_status =?', '1');
        $range = $read->fetchAll($date_range);
        $count = count($range);
        for ($i = 0; $i <= $count; $i++) {
            $fromdate[] = $range[$i][fromdate];
            $todate[] = $range[$i][todate];
            $productId[] = $range[$i][entity_id];
        }

        return array($productId, $fromdate, $todate);
    }

    /* Get Upcoming trip */

    public function upcomingTrip() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $cusId = $customer->getId();
        $todayDate = strtotime(Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        $todayDate = date('Y/m/d', $todayDate);
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_write');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $booking_table = $tPrefix . 'airhotels_booking';
        $date_range = $read->select()
                ->from(array('ct' => $booking_table), array('ct.entity_id', 'ct.fromdate', 'ct.todate'))
                ->where('ct.customer_id =? ', $cusId)
                ->where('ct.fromdate >?', $todayDate)
                ->where('ct.order_status =?', '1');
        $range = $read->fetchAll($date_range);
        $count = count($range);
        for ($i = 0; $i <= $count; $i++) {
            $fromdate[] = $range[$i][fromdate];
            $todate[] = $range[$i][todate];
            $productId[] = $range[$i][entity_id];
        }
        return array($productId, $fromdate, $todate);
    }

    /**
     * Get the similarlocation property
     * @param string $city
     * @param int $productId
     * @return array  $PropertyCollection
     */
    public function getSimilarLocation($city, $productId) {
        $PropertyCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('entity_id', array('neq' => $productId))
                        ->addAttributeToFilter('city', array('like' => $city . "%"))
                        ->addFieldToFilter(array(array('attribute' => 'status', 'eq' => '1')))
                        ->setPageSize(10)->setOrder('created_at', 'desc');

        return $PropertyCollection;
    }

    /**
     * To get other property details by using customer id
     * 
     * @param int $customerid
     * @param int $productId
     * @return array  $PropertyCollection
     */
    public function getSimilarCustomer($customerid, $productId) {
        $PropertyCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('entity_id', array('neq' => $productId))
                        ->addAttributeToFilter('Userid', array('eq' => $customerid))
                        ->addFieldToFilter(array(array('attribute' => 'status', 'eq' => '1')))
                        ->setPageSize(10)->setOrder('created_at', 'desc');
        return $PropertyCollection;
    }

    /**
     * Get the response time and short description about client
     * 
     * @param int $responseTime
     * @param string $more_host
     * @return array  $selectResult1
     */
    public function updateProfileInformation($responseTime, $more_host) {
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $customer_photo_table = $tPrefix . 'airhotels_customer_photo';


        $customer_id = Mage::getSingleton('customer/session')->getId();

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');

        $selectResult = $write->query("select * from $customer_photo_table where `customer_id` = '$customer_id' ");

        $selectResult1 = 0;
        if (count($selectResult->fetchAll()) == 0) {
            $selectResult1 = $write->query("insert into $customer_photo_table(`response_time`,`more_host`) values('$responseTime','$more_host')    ");
        } else {
            $selectResult1 = $write->query("update $customer_photo_table set response_time = '$responseTime' , more_host = '$more_host'  where `customer_id` = '$customer_id' ");
        }


        return $selectResult1;
    }

    /**
     * Get the first four popular propertys based on number of bookings
     * 
     * @return array $results 
     */
    public function getPopularProperty() {
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $airhotels_booking = $tPrefix . 'airhotels_booking';
        $catalog_product = $tPrefix . 'catalog_product_entity_int';
        $dealstatus[0] = 1;
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'status');
        $write = Mage::getSingleton('core/resource')->getConnection('core_');
        $selectResult = $write->query("SELECT DISTINCT (cp.entity_id)
                                            FROM  $airhotels_booking AS  `cp` 
                                            INNER JOIN  $catalog_product AS  `pei` 
                                            ON pei.entity_id = cp.entity_id
                                            WHERE pei.value =1
                                            AND pei.attribute_id = $attributeId
                                            LIMIT 4 ");
        $results = $selectResult->fetchAll();
        return $results;
    }

    /**
     * Get first four most rated propertys
     * @return array $products
     */
    public function getRatedProperty() {
    
        $products = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToFilter('status', array('eq' => 1))
               ->addAttributeToFilter('propertyapproved',array('eq' => 1)) 
                 ->addAttributeToFilter('type_id', array('eq' => 'property'))
                ->addAttributeToSelect("*")
                ->setVisibility(array(2,3,4));
    $products->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  
                                array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');                
    $products->setOrder('rating_summary', 'desc');
    $products->setPageSize(4);
        return $products;
    }

    /**
     * Filter all the cusotmer review for particular host properties
     *
     * @return array $propertyReview
     */
    public function getreview() {
        $customerid = Mage::app()->getRequest()->getParam('id');
        $propertyReview = array();
        $PropertyCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                ->addAttributeToFilter('status', array('eq' => 1))
                ->addAttributeToFilter('propertyapproved',array('eq' => 1)) 
                ->addAttributeToFilter('Userid', array('eq' => $customerid));
        foreach ($PropertyCollection as $property) {
            $propertyId[] = $property->getId();
        }

        $reviewsTotal = Mage::getModel('review/review')->getResourceCollection();
        $reviewsTotal->addStoreFilter(Mage::app()->getStore()->getId())
                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                ->setDateOrder()
                ->addRateVotes()
                ->load();
        $reviewsTotal->addEntityFilter('product IN(?)', $propertyId);
        foreach ($reviewsTotal as $review) {
            $propertyReview[] = $review;
        }
        return $propertyReview;
    }
    /**
     * Get the Wishlist collection based on the customer login
     *
     * @return array  $PropertyCollection
     */
    public function getWishList() {
        $_itemCollection = Mage::helper('wishlist')->getWishlistItemCollection();
        $_itemsInWishList = array();
        $count = 0 ;
        foreach ($_itemCollection as $_item) {
            $_product = $_item->getProduct();
            $ids[$count] = $_product->getId();
            $count = $count+1;
        }
          $PropertyCollection = Mage::getModel('airhotels/property')->getpropertycollection()
                         ->addAttributeToFilter('status', array('eq' => 1))
                         ->addAttributeToFilter('propertyapproved',array('eq' => 1)) 
                         ->addAttributeToSelect('url_path')
                        ->addAttributeToFilter('entity_id', array('in' => $ids));
        return $PropertyCollection;
    }
    public function deleteProperty($entity_id) {

        
        $admin_email_id = Mage::getStoreConfig('airhotels/custom_email/admin_email_id'); 
        $toMailId   = Mage::getStoreConfig("trans_email/ident_$admin_email_id/email");
        $toName     = Mage::getStoreConfig("trans_email/ident_$admin_email_id/name");

       $templeId       =  (int)Mage::getStoreConfig('airhotels/custom_email/propertydelete_template');
       //if it is user template then this process is continue
        if ($templeId) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
        } else {   //  we are calling default template
            $emailTemplate = Mage::getModel('core/email_template')
                            ->loadDefault('airhotels_custom_email_propertydelete_template');
        }
        //get proeprty details
        $property = Mage::getModel('catalog/product')->load($entity_id);
        $pname = $property->getName();
        $userid = $property->getUserid();
        $customer = Mage::getModel('customer/customer')->load($userid);
        $recipient = $customer->getEmail();//Property Email Owner
        $cname = $customer->getName();//Property Email Owner
        $emailTemplate->setSenderName($toName);     //mail sender name
        $emailTemplate->setSenderEmail($toMailId);  //mail sender email id
        
          
         $emailTemplateVariables = (array('ownername'=> $toName,'pname' =>$pname )); 
          
        $emailTemplate->setDesignConfig(array('area' => 'frontend'));
        
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body

        $emailTemplate->send($recipient, $senderName, $emailTemplateVariables);  //send mail to customer email ids
        
        //delete collection
        Mage::register('isSecureArea', true); /* set secure admin area*/
        Mage::getModel('catalog/product')->setId($entity_id)->delete();
         Mage::unregister('isSecureArea'); /* un set secure admin area*/
          
    }
    
     public function adminApproval($entity_id) {
        //update collection
        
        Mage::getModel('catalog/product')->load($entity_id)->setPropertyapproved(0)->save();
        
        $admin_email_id = Mage::getStoreConfig('airhotels/custom_email/admin_email_id'); 
        $toMailId   = Mage::getStoreConfig("trans_email/ident_$admin_email_id/email");
        $toName     = Mage::getStoreConfig("trans_email/ident_$admin_email_id/name");

       $templeId       =  (int)Mage::getStoreConfig('airhotels/custom_email/propertyapproval_template');
       //if it is user template then this process is continue
        if ($templeId) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
        } else {   //  we are calling default template
            $emailTemplate = Mage::getModel('core/email_template')
                            ->loadDefault('airhotels_custom_email_propertyapproval_template');
        }
        //get proeprty details
        $property = Mage::getModel('catalog/product')->load($entity_id);
        $pname = $property->getName();
        $userid = $property->getUserid();
        $customer = Mage::getModel('customer/customer')->load($userid);
        $recipient = $customer->getEmail();//Property Email Owner
        $cname = $customer->getName();//Property Email Owner
        $emailTemplate->setSenderName($toName);     //mail sender name
        $emailTemplate->setSenderEmail($toMailId);  //mail sender email id
        
           $emailTemplateVariables = (array('ownername'=> $toName,'pname' =>$pname,'cname'=>$cname )); 
          
        $emailTemplate->setDesignConfig(array('area' => 'frontend'));
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body

        $emailTemplate->send($recipient, $senderName, $emailTemplateVariables);  //send mail to customer email ids
    }
    
    public function newProperty($entity_id) {
        //update collection
        
        Mage::getModel('catalog/product')->load($entity_id)->setPropertyapproved(1)->save();
        
        $admin_email_id = Mage::getStoreConfig('airhotels/custom_email/admin_email_id'); 
        $toMailId   = Mage::getStoreConfig("trans_email/ident_$admin_email_id/email");
        $toName     = Mage::getStoreConfig("trans_email/ident_$admin_email_id/name");
       $templeId       =  (int)Mage::getStoreConfig('airhotels/custom_email/newproperty_template');
       //if it is user template then this process is continue
        if ($templeId) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
        } else {   //  we are calling default template
            $emailTemplate = Mage::getModel('core/email_template')
                            ->loadDefault('airhotels_custom_email_newproperty_template');
        }
        //get proeprty details
        $property = Mage::getModel('catalog/product')->load($entity_id);
       $pname = $property->getName();
        $purl = $property->getProductUrl();
        $userid = $property->getUserid();
        $customer = Mage::getModel('customer/customer')->load($userid);
         $recipient = $customer->getEmail();//Property Email Owner
        $cname = $customer->getName();//Property Email Owner
        $emailTemplate->setSenderName($toName);     //mail sender name
        $emailTemplate->setSenderEmail($toMailId);  //mail sender email id
        
         $emailTemplateVariables = (array('ownername'=> $toName,'pname' =>$pname,'purl' => $purl, 'cname'=>$cname )); 
          
        $emailTemplate->setDesignConfig(array('area' => 'frontend'));
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body

        $emailTemplate->send($recipient, $senderName, $emailTemplateVariables);  //send mail to customer email ids
    }
}

?>