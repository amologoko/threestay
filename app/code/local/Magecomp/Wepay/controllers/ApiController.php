    <?php

require_once Mage::getBaseDir('lib') . DS . 'wepay' . DS . 'wepay.php';

class Magecomp_Wepay_ApiController extends Mage_Core_Controller_Front_Action {

	protected $_wepay = null;

	public function startAction() {

		$this->initWepay();

		try {
            $customer_owner = $this->getOwner();
			$wepay = new Wepay($customer_owner->getWepayAccessToken());
			$quote = $this->getSession()->getQuote();
			$payment = $quote->getPayment();
			$shipping = $quote->getShippingAddress();
			$billing = $quote->getBillingAddress();

			$quote->reserveOrderId()->save();

			$region = Mage::getSingleton('directory/region');

			$state = $region->loadByName($billing->getRegion(), $billing->getCountryId())->getCode();
			$addr_obj = new stdClass();
			$addr_obj->name = $billing->getName();
			$addr_obj->email = $quote->getCustomerEmail();
			$addr_obj->phone_number = $billing->getTelephone();
			$addr_obj->address = $billing->getSteetFull();
			$addr_obj->city = $billing->getCity();
			$addr_obj->state = $state;
			$addr_obj->zip = $billing->getPostcode();


			$amount = floatval(number_format($quote->getBaseGrandTotal(), 2, '.', ''));
            $app_fee_percent_config = (float)Mage::getStoreConfig('airhotels/custom_group/airhotels_servicetax');
            $price = 0;
            $secret_key = 0;
            $property_app_fee = 0;
            foreach ($quote->getAllItems() as $item) {
                $price = $item->getProduct()->getPrice();
                $secret_key = Mage::getModel('catalog/product')->load($item->getProduct()->getId())->getSecretKey();
                $property_app_fee = Mage::getModel('catalog/product')->load($item->getProduct()->getId())->getPropertyAppFee();
            }

           $app_fee = 1;
           if($secret_key != null){
               $app_fee = $app_fee_percent_config;
           }else{
               if($property_app_fee != null){
                   $app_fee = $property_app_fee;
               }else{
                   $app_fee = 0;
               }
           }

            $app_fee_amount =  ($price / 100) * $app_fee;

			$payer='';
			if($this->getConfig('fee_payer')==0)
			{
				$payer = 'payee';
			}
			else
			{
				$payer = 'payer';
			}

            $preapproval = $wepay->request('/preapproval/create', array(
                    'account_id' => $customer_owner->getWepayAccountId(),
                    'amount' => $amount,
                    'short_description' => "Order Number: " . $quote->getReservedOrderId(),
                    'mode' => "regular",
                    'reference_id' => $quote->getId(),
                    'fee_payer' => $payer,
                    'app_fee' => $app_fee_amount,
                    'redirect_uri' => Mage::getUrl('wepay/api/return'),
                    'prefill_info' => $addr_obj,
                    'period' => 'once',
                )
            );

            $payment->setAdditionalInformation(Magecomp_Wepay_Model_Wepay::ADD_INFO_PREAPPROVAL_ID_KEY, $preapproval->preapproval_id)->save();
            $this->getResponse()->setRedirect($preapproval->preapproval_uri);
			return;
		} catch (Exception $e) {
			Mage::getSingleton('checkout/session')->addError($e->getMessage());
		}

		$this->_redirect('checkout/cart');
	}

	public function returnAction() {
        $preapproval_id = $this->getRequest()->getParam('preapproval_id');
        if ($this->_getRefererUrl() == Mage::getUrl('checkout/onepage/success') ||
            $this->_getRefererUrl() == Mage::getUrl('checkout/onepage') ||
            $preapproval_id == null
        ) {
            $this->_redirect('checkout/cart');
            return;
        }
		$this->initWepay();
        $customer_owner = $this->getOwner();


		$quote = $this->getSession()->getQuote();

		$quote->collectTotals();
		$payment = $quote->getPayment();

		// check returned checkout id with session's saved checkout id
		if ($preapproval_id != $payment->getAdditionalInformation(Magecomp_Wepay_Model_Wepay::ADD_INFO_PREAPPROVAL_ID_KEY)) {
			Mage::getSingleton('checkout/session')->addError('An error occurred while connecting to your wepay payment. (1)');
			Mage::log('Incorrect wepay callback. Checkout ID mismatch: quote_id: ' . $quote->getId() . ' payment_id:  ' . $payment->getId() . 'param: ' . $preapproval_id . ' quote: ' . $payment->getAdditionalInformation(Magecomp_Wepay_Model_Wepay::ADD_INFO_CHECKOUT_ID_KEY));
			$this->_redirect('checkout/cart');
			return;
		}

		try {
			// get wepay info to confirm
			$wepay = new Wepay($customer_owner->getWepayAccessToken());
			$info = $wepay->request('preapproval', array('preapproval_id' => $preapproval_id));

			if ($info->reference_id != $quote->getId()) {
				Mage::getSingleton('checkout/session')->addError('An error occurred while connecting to your wepay payment. (2)');
				$this->_redirect('checkout/cart');
				return;
			}


			$service = Mage::getModel('sales/service_quote', $quote);
			$service->submitAll();
			$quote->save();

			$order = $service->getOrder();
			if (!$order) {
				Mage::throwException('An error occurred while connecting to your wepay payment. (3)');
			}

            $payment = $order->getPayment();
            $payment->setAdditionalData(serialize(array('preapproval_id' => $info->preapproval_id)))
            ->save();
			$this->getSession()->setLastQuoteId($quote->getId())
				   ->setLastSuccessQuoteId($quote->getId())
				   ->setLastOrderId($order->getId());

			switch ($order->getState()) {
				// even after placement paypal can disallow to authorize/capture, but will wait until bank transfers money
				case Mage_Sales_Model_Order::STATE_PENDING_PAYMENT:
					// TODO
					break;
				// regular placement, when everything is ok
				case Mage_Sales_Model_Order::STATE_PROCESSING:
				case Mage_Sales_Model_Order::STATE_COMPLETE:
				case Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW:
					$order->sendNewOrderEmail();
					break;
			}

			$this->getResponse()->setRedirect(Mage::getUrl('checkout/onepage/success'));
			return;
		} catch (Exception $e) {
			Mage::getSingleton('checkout/session')->addError($e->getMessage());
			Mage::logException($e);
		}

		$this->_redirect('checkout/cart');
	}

	protected function initWepay() {

		if (!Mage::registry(Magecomp_Wepay_Model_Wepay::INIT_REGISTRY_KEY)) {
			if ($this->getConfig('testmode') == 1) {
				Wepay::useStaging($this->getConfig('client_id'), $this->getConfig('password'));
			} else {
				Wepay::useProduction($this->getConfig('client_id'), $this->getConfig('password'));
			}
			Mage::register(Magecomp_Wepay_Model_Wepay::INIT_REGISTRY_KEY, true);
		}
	}

	protected function getSession() {
		return Mage::getSingleton('checkout/session');
	}

	protected function getOnePage() {
		return Mage::getSingleton('checkout/type_onepage');
	}

	protected function getConfig($path, $storeId = null) {
		$path = 'payment/wepay/' . $path;
		return Mage::getStoreConfig($path, $storeId);
	}

	public function getCheckoutMethod() {
		if (Mage::getSingleton('customer/session')->isLoggedIn()) {
			return Mage_Checkout_Model_Type_Onepage::METHOD_CUSTOMER;
		}
		if (!$this->_quote->getCheckoutMethod()) {
			if (Mage::helper('checkout')->isAllowedGuestCheckout($this->_quote)) {
				$this->_quote->setCheckoutMethod(Mage_Checkout_Model_Type_Onepage::METHOD_GUEST);
			} else {
				$this->_quote->setCheckoutMethod(Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER);
			}
		}
		return $this->_quote->getCheckoutMethod();
	}

	// plugin method listener
	public function adminAction() {

		$key = Mage::app()->loadCache(Magecomp_Wepay_Helper_Data::WEPAY_PLUGIN_API_AUTH);
		$auth = $this->getRequest()->getParam('auth');
		$post = $this->getRequest()->getParams();
		if ($auth == $key && isset($post['account_id']) && isset($post['access_token'])) {
			$post = $this->getRequest()->getParams();

			$resource = new Mage_Core_Model_Resource_Setup('core_setup');

			$resource->setConfigData('payment/wepay/account_id', $post['account_id']);
			$resource->setConfigData('payment/wepay/client_id', $post['app_id']);
			$resource->setConfigData('payment/wepay/access_token', Mage::helper('core')->encrypt($post['access_token']));
			$resource->setConfigData('payment/wepay/password', Mage::helper('core')->encrypt($post['app_secret']));
			
			Mage::getConfig()->cleanCache();
		}

		Mage::log('admin wepay hit');
		//$this->_redirectUrl(Mage_Adminhtml_Helper_Data::getUrl('adminhtml/system_config/edit', array('section' => 'payment')));
	}

    public function confirmAction()
    {
        $session = Mage::getSingleton('customer/session');
        $order_id = $this->getRequest()->getParam('order_id');
        $secret_key = "";

        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::helper('adminhtml')->getUrl('wepay/api/confirm',array('order_id'=>$order_id)));
        if ($session->isLoggedIn() && $order_id != null) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
            $fromdateVal = '';
            $todateVal = '';
            foreach ($order->getAllItems() as $item){
                $options = $item->getProductOptions();
                $productId = $item->getProductId();
                $productData = Mage::getModel('catalog/product')->load($productId);
                $productName = $productData->getName();
                $hostId = $productData->getUserid();
                $secret_key = $productData->getSecretKey();
                $property_app_fee = $productData->getPropertyAppFee();
                $fromdateVal = $options['info_buyRequest']['fromdate'];
                $todateVal = $options['info_buyRequest']['todate'];
            }
                $fromDateArr = explode("@", $fromdateVal);
                $fromdate = date('Y-m-d', mktime(0, 0, 0, $fromDateArr[0], $fromDateArr[1], $fromDateArr[2]));
                $toDateArr = explode("@", $todateVal);
                $todate = date('Y-m-d', mktime(0, 0, 0, $toDateArr[0], $toDateArr[1] - 1, $toDateArr[2]));

                $tPrefix = (string)Mage::getConfig()->getTablePrefix();
                $booking_table = $tPrefix . 'airhotels_booking';
                $resource = Mage::getSingleton('core/resource');
                $read = $resource->getConnection('core_write');
                $access_code = 0;
            if ($secret_key != null) {
                $soap = new Zend_Soap_Client("http://3stay.cloudapp.net/Service1.svc?wsdl", array('compression' => SOAP_COMPRESSION_ACCEPT));
                $soap->setSoapVersion(SOAP_1_1);
                $access_code = $soap->GenerateCode(array("KeyCode" => $secret_key, "StartDate" => $fromdate, "EndDate" => $todate))->GenerateCodeResult;
            }
            $read->query("UPDATE $booking_table SET `access_code` = '".$access_code."' WHERE `order_item_id` = '".$order->getId()."'");
            $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
            if ($currentCustomer->getEmail() == $this->getOwner($order)->getEmail()) {
                if ($order->getStatusLabel() != 'Processing') {
                    $this->_redirectUrl(Mage::getUrl('no-route'));
                    return;
                }

                if (!$order->canInvoice()) {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
                }
                $this->initWepay();
                $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
                $billing = $order->getBillingAddress();
                $additional_information = $order->getPayment()->additional_information;
                $wepay_preapproval_id = $additional_information['wepay_preapproval_id'];
                $region = Mage::getSingleton('directory/region');
                $state = $region->loadByName($billing->getRegion(), $billing->getCountryId())->getCode();
                $addr_obj = new stdClass();
                $addr_obj->name = $billing->getName();
                $addr_obj->email = $order->getCustomerEmail();
                $addr_obj->phone_number = $billing->getTelephone();
                $addr_obj->address = $billing->getSteetFull();
                $addr_obj->city = $billing->getCity();
                $addr_obj->state = $state;
                $addr_obj->zip = $billing->getPostcode();

                $price = 0;
                foreach ($order->getAllItems() as $item) {
                    $price = $item->getProduct()->getPrice();
                }

                $amount = floatval(number_format($order->getBaseGrandTotal(), 2, '.', ''));
                $app_fee_percent_config = (float)Mage::getStoreConfig('airhotels/custom_group/airhotels_servicetax');
                $app_fee = 1;
                if($secret_key != null){
                   $app_fee = $app_fee_percent_config;
                }else{
                   if($property_app_fee != null){
                       $app_fee = $property_app_fee;
                   }else{
                       $app_fee = 0;
                   }
                }

                $app_fee_amount = ($price / 100) * $app_fee;
                $payer = '';
                if ($this->getConfig('fee_payer') == 0) {
                    $payer = 'payee';
                } else {
                    $payer = 'payer';
                }
                $owner = $this->getOwner($order);
                $access_token = $owner->getWepayAccessToken();
                $owner->getWepayAccountId();
                $wepay = new Wepay($access_token);
                try {
                    $checkout = $wepay->request('/checkout/create', array(
                            'account_id' => $owner->getWepayAccountId(),
                            'amount' => $amount,
                            'short_description' => "Order Number: " . $order->getId(),
                            'type' => "GOODS",
                            'preapproval_id' => $wepay_preapproval_id,
                            'fee_payer' => $payer,
                            'app_fee' => $app_fee_amount,
                            'auto_capture' => 1,
                            'prefill_info' => $addr_obj,
                        )
                    );
                } catch (Exception $e) {
                    Mage::getSingleton('checkout/session')->addError($e->getMessage());
                }

                if ($checkout->checkout_id) {
                    try {
                        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                        if (!$invoice->getTotalQty()) {
                            Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
                        }
                        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                        $invoice->register();
                        $transactionSave = Mage::getModel('core/resource_transaction')
                            ->addObject($invoice)
                            ->addObject($invoice->getOrder());
                        $transactionSave->save();
                    } catch (Exception $e) {
                        Mage::getSingleton('checkout/session')->addError($e->getMessage());
                    }
                }

                if ($order->getStatusLabel() == 'Complete' && $checkout->checkout_id) {
                    $this->updateBookingStatus($order_id);
                    Mage::getSingleton('core/session')->addSuccess("You have successfuly confirmed order: #". $order_id);
                    $this->_redirectUrl(Mage::helper('adminhtml')->getUrl('booking/property/show'));
                } else {
                    Mage::getSingleton('core/session')->addError("Error, order can't be confirmed");
                    $this->_redirectUrl(Mage::helper('adminhtml')->getUrl('booking/property/show'));
                }
            } else {
                $this->_redirectUrl(Mage::getUrl('no-route'));
            }
        } else {
            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            return;
        }
    }

    public function cancelAction()
    {
        $session = Mage::getSingleton('customer/session');
        $order_id = $this->getRequest()->getParam('order_id');
        if ($session->isLoggedIn() && $order_id != null) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
            $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
            if ($currentCustomer->getEmail() == $this->getOwner($order)->getEmail()) {
                if ($order->getStatusLabel() != 'Processing') {
                    $this->_redirectUrl(Mage::getUrl('no-route'));
                    return;
                }
                $this->initWepay();
                $additional_information = $order->getPayment()->additional_information;
                $access_token = $this->getOwner($order)->getWepayAccessToken();
                $wepay_preapproval_id = $additional_information['wepay_preapproval_id'];

                $wepay = new Wepay($access_token);

                $preapproval = $wepay->request('/preapproval/cancel', array(
                        'preapproval_id' => $wepay_preapproval_id
                    )
                );

                $order->cancel();
                $order->setStatus('canceled');
                $order->save();

                if ($preapproval->state == 'cancelled' && $order->getStatusLabel() == 'Canceled') {
                     Mage::getSingleton('core/session')->addSuccess("You have successfuly canceled order: ". $order_id);
                     $this->_redirectUrl(Mage::helper('adminhtml')->getUrl('booking/property/show'));
                } else {
                    Mage::getSingleton('core/session')->addError("Error, can't cancel this order!");
                    $this->_redirectUrl(Mage::helper('adminhtml')->getUrl('booking/property/show'));
                }
            } else {
                $this->_redirectUrl(Mage::getUrl('no-route'));
            }
        } else {
            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            return;
        }

    }

    public function authAction()
    {
        $loggedin_customer = Mage::getSingleton('customer/session')->getCustomer();
        $user_email = $loggedin_customer->getEmail();
        $client_id = $this->getConfig('client_id');
        $client_secret = $this->getConfig('password');

        if (Mage::app()->getRequest()->getParam('code') != null) {
            $this->initWepay();
            $wepay = new Wepay($this->getConfig('access_token'));
            $getTokenResult = $wepay->getToken(Mage::app()->getRequest()->getParam('code'), Mage::helper('adminhtml')->getUrl('wepay/api/auth'));
            $user_id = $getTokenResult->user_id;
            $access_token = $getTokenResult->access_token;
            $createAccountResult = $this->createAccount($access_token);
            $account_id = $createAccountResult->account_id;
            $loggedin_customer->setWepayAccountId($account_id);
            $loggedin_customer->setWepayAccessToken($access_token);
            $loggedin_customer->save();
            $this->_redirectUrl(Mage::helper('adminhtml')->getUrl('booking/property/form'));
        } else {
            $url = 'https://www.wepay.com/v2/oauth2/authorize?';
            if ($this->getConfig('testmode') == 1) {
                $url = 'https://stage.wepay.com/v2/oauth2/authorize?';
            }
            $urlParameters = array(
                'client_id' => $client_id,
                'scope' => 'manage_accounts,view_balance,collect_payments,view_user,send_money,preapprove_payments',
                'redirect_uri' => Mage::helper('adminhtml')->getUrl('wepay/api/auth'),
                'user_email' => $user_email
            );
            $url .= http_build_query($urlParameters);
            $this->_redirectUrl($url);
        }

    }

    private function createAccount($access_token)
    {
        $this->initWepay();
        $wepay = new Wepay($access_token);
        try {
            $result = $wepay->request('/account/create', array(
                    'name' => 'threestay_account_name',
                    'description' => 'threestay_account_deskciption',
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('checkout/session')->addError($e->getMessage());
        }
        return $result;
    }

    private function getOwner($order = false)
    {
        if ($order) {
            $resource = $order;
        } else {
            $resource = Mage::getModel('checkout/cart')->getQuote();
        }
        $productId = 0;
        foreach ($resource->getAllItems() as $item) {
            $productId = $item->getProduct()->getId();
        }
        $model = Mage::getModel('catalog/product');
        $_product = $model->load($productId);
        $hostId = $_product->getUserid(); //property owner Id
        return Mage::getModel('customer/customer')->load($hostId);
    }

    public function isHaveAccountAction(){
        $session = Mage::getSingleton('customer/session');
        if($session-> isLoggedIn() && $session->getCustomer()->getWepayAccountId() == null){
            echo 0;
        }else{
            echo 1;
        }

    }

    private function updateBookingStatus($order_id){
        $airhotels = Mage::getModel('airhotels/airhotels');
        $airhotels->updateStatus($order_id,1);
    }
}