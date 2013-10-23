<?php
/**
 * @ Author      : Apptha team
 * @package      : Apptha_Airhotels
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Modified Date:June 2012
 */
class Apptha_Airhotels_Model_Observer
{
    public function booking($observer)
    {
#	$event = $observer->getEvent();
#	Mage::log($event);

         //check sytlish theme
                $current_theme = Mage::getStoreConfig('design/theme/default');
                if($current_theme == 'stylish')
                {  
         $fromdate =  Mage::getSingleton('core/session')->getFromdate();

        //$todate = Mage::getSingleton('core/session')->getTodate();

         $todateVal = Mage::getSingleton('core/session')->getTodate();
         $dateArr=explode("-",$todateVal);
         $todate=date('Y-m-d', mktime(0, 0, 0, $dateArr[1], $dateArr[2] - 1, $dateArr[0]));

         if($fromdate && $todate && $fromdate != '' && $todate!= ''){
     	 $accomodate = Mage::getSingleton('core/session')->getAccomodate();
     	 $subtotal = Mage::getSingleton('core/session')->getSubtotal();
     	 $session = Mage::getSingleton('checkout/session');

     	 $productId = "";
	 $secret_key = "2010201120122013";
     	 $orders = Mage::getModel('sales/order')->getCollection()
     	 ->setOrder('created_at','DESC')
     	 ->setPageSize(1)
     	 ->setCurPage(1);

     	 $order_id = $orders->getFirstItem()->getIncrementId();
     	 $order_item_id = $orders->getFirstItem()->getBillingAddressId();
         $baseCurrency = $orders->getFirstItem()->getBaseCurrencyCode();
         $orderCurrency = $orders->getFirstItem()->getOrderCurrencyCode();
         $address = Mage::getModel('sales/order_address')->load($order_item_id)->getBillingAddress();

     	 foreach ($session->getQuote()->getAllItems() as $item){

     	 	$productId = $item->getProductId();
                 $productData = Mage::getModel('catalog/product')->load($productId);
                $productName = $productData->getName();
                $hostId = $productData->getUserid();
		#$secret_key = $productData->getSecretKey();

     	 }
     	 $customer = Mage::getSingleton('customer/session')->getCustomer();
     	 $cusId = $customer->getId();
         $buyerEmail = $customer->getEmail();

     	 $resource = Mage::getSingleton('core/resource');
     	 $read = $resource->getConnection('core_write');
     	 $tPrefix = (string) Mage::getConfig()->getTablePrefix();
     	 $booking_table = $tPrefix . 'airhotels_booking';

         $config = Mage::getStoreConfig('airhotels/custom_group');

          //Order processing fee calcualtion
         $serviceFee = ($subtotal/100) * ($config["airhotels_servicetax"] ) ;
         $serviceFee = number_format($serviceFee ,2,'.','' ) ;

         //Commission fee calculation
         $hostFee = ($subtotal/100) * ($config["airhotels_hostfee"] ) ;
         $hostFee = number_format($hostFee,2,'.','' ) ;

	 #$soap = new Zend_Soap_Client("https://www.kaba-ecode.com/KWWS_WS/KWWSService.asmx?WSDL", array('compression' => SOAP_COMPRESSION_ACCEPT));
	 #$result = $soap->GenerateAccessCode('ThreeStay', 'TestSiteDoor', $fromdate, $todate, '0', 'AlexThreeStay', 'DInErAl47', '', '', '0', $order_id, '', '');
	 #$status = $result['ReturnStatus'];
         #$access_code = $result['AccessCode'];
         $soap = new Zend_Soap_Client("http://2drive.cloudapp.net:8080/Service1.svc?wsdl", array('compression' => SOAP_COMPRESSION_ACCEPT));
	 #$soap = new Zend_Soap_Client("http://3stay.cloudapp.net/Service1.svc?wsdl", array('compression' => SOAP_COMPRESSION_ACCEPT));
	 $soap->setSoapVersion(SOAP_1_1);
	 $access_code = $soap->GenerateCode(array("KeyCode" => $secret_key, "StartDate" => $fromdate, "EndDate" => $todate))->GenerateCodeResult;

         $read->query("Insert into $booking_table (entity_id,product_name,customer_id,customer_email,fromdate,todate,accomodates,host_fee,service_fee,order_id,base_currency_code,order_currency_code,subtotal,order_item_id,access_code)
       	 values ($productId,'".$productName."',$cusId,'".$buyerEmail."','".$fromdate."','".$todate."',$accomodate,$hostFee,$serviceFee,$order_id,'".$baseCurrency."','".$orderCurrency."',$subtotal,$order_item_id,'".$access_code."')");
         Mage::getSingleton('core/session')->setProductID($productId);
	 Mage::getSingleton('core/session')->setAccessCode($access_code);
      	 #$productOptions = $mainItem->getProductOptions();
	 #$productOptions['accessCode'] = $access_code;
	 #$mainItem->setProductOptions($productOptions);
         }
                }
    }

     public function catalog_product_save_before($observer) {
        $product = $observer->getProduct();
        $productType = Mage::app()->getRequest()->getParam('type');
        if ($productType == 'property') {
            $customerId = $product->getUserid();
            $customer = Mage::getModel('customer/customer')->load($customerId);
            if ($customer->getId() == '') {
                 $product->setCanSaveCustomOptions(true);
                Mage::throwException(Mage::helper('adminhtml')->__('User id was invalid'));
            }
        }
        return;
    }
    public function customerlogout()
    {
        foreach (Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item) {
            Mage::getSingleton('checkout/cart')->removeItem($item->getId())->save();
        }
        Mage::getSingleton('checkout/cart')->truncate();
        Mage::getSingleton('checkout/session')->clear();
    }
    public function adminProductSave($observer)
    {
        $property_approval = Mage::getStoreConfig('airhotels/custom_email/property_approval');
         if($property_approval)
         {
        $product            = $observer->getProduct();
       $propertyapproved          = $product['propertyapproved'];
       $propertyunapproval = Mage::getModel('catalog/product')->load($product->getId())->getPropertyapproved();
        if( $propertyapproved && empty($propertyunapproval))
        {
         
       $admin_email_id = Mage::getStoreConfig('airhotels/custom_email/admin_email_id'); 
        $fromMailId   = Mage::getStoreConfig("trans_email/ident_$admin_email_id/email");
        $fromName     = Mage::getStoreConfig("trans_email/ident_$admin_email_id/name");
       
        $templeId       =  (int)Mage::getStoreConfig('airhotels/custom_email/adminapproval_template');
       //if it is user template then this process is continue
        if ($templeId) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
        } else {   //  we are calling default template
            $emailTemplate = Mage::getModel('core/email_template')
                            ->loadDefault('airhotels_custom_email_adminapproval_template');
        }
        
        //get proeprty details
        $property = Mage::getModel('catalog/product')->load($product->getId());
        $pname = $property->getName();
        $purl = $property->getProductUrl();
        $userid = $property->getUserid();
        $customer = Mage::getModel('customer/customer')->load($userid);
        $recipient = $customer->getEmail();//Property Email Owner
        $cname = $customer->getName();//Property Email Owner
        
        $emailTemplate->setSenderName($fromName);     //mail sender name
        $emailTemplate->setSenderEmail($fromMailId);  //mail sender email id
        
           $emailTemplateVariables = (array('ownername'=> $fromName,'pname' =>$pname,'purl' => $purl, 'cname'=>$cname )); 
          
        $emailTemplate->setDesignConfig(array('area' => 'frontend'));
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body

        $emailTemplate->send($recipient, $senderName, $emailTemplateVariables);  //send mail to customer email ids
       
            }
         }
    }
}
?>