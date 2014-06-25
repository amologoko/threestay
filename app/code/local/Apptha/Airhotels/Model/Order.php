<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order model
 *
 * Supported events:
 *  sales_order_load_after
 *  sales_order_save_before
 *  sales_order_save_after
 *  sales_order_delete_before
 *  sales_order_delete_after
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Apptha_Airhotels_Model_Order extends Mage_Sales_Model_Order {
    /**
     * XML configuration paths
     */

    const XML_PATH_EMAIL_TEMPLATE = 'sales_email/order/template';
    const XML_PATH_EMAIL_GUEST_TEMPLATE = 'sales_email/order/guest_template';
    const XML_PATH_EMAIL_IDENTITY = 'sales_email/order/identity';
    const XML_PATH_EMAIL_COPY_TO = 'sales_email/order/copy_to';
    const XML_PATH_EMAIL_COPY_METHOD = 'sales_email/order/copy_method';
    const XML_PATH_EMAIL_ENABLED = 'sales_email/order/enabled';
    const XML_PATH_EMAIL_OWNER_TEMPLATE = 'sales_email/owner/template';
    const XML_PATH_EMAIL_OWNER_GUEST_TEMPLATE = 'sales_email/owner/guest_template';
    const XML_PATH_UPDATE_EMAIL_TEMPLATE = 'sales_email/order_comment/template';
    const XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE = 'sales_email/order_comment/guest_template';
    const XML_PATH_UPDATE_EMAIL_IDENTITY = 'sales_email/order_comment/identity';
    const XML_PATH_UPDATE_EMAIL_COPY_TO = 'sales_email/order_comment/copy_to';
    const XML_PATH_UPDATE_EMAIL_COPY_METHOD = 'sales_email/order_comment/copy_method';
    const XML_PATH_UPDATE_EMAIL_ENABLED = 'sales_email/order_comment/enabled';
    const XML_PATH_COUPON_TEMPLATE = 'dealcoupon/email/coupon_template';
    const XML_PATH_OWNER_TEMPLATE = 'dealcoupon/email/owner_template';
    const XML_PATH_NO_EMAIL_TEMPLATE = 'dealcoupon/email/email_template';
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    #const XML_PATH_EMAIL_SENDER = 'airhotels/order_reminder/sender_email_identity';
    const XML_PATH_EMAIL_SENDER = 'contacts/email/sender_email_identity';
    /**
     * Email Update template contus
     */
    const XML_PATH_ORDERSTUTS_TEMPLATE = 'airhotels/order_reminder/orderstatus_template';
    const XML_PATH_ORDERSTUTS_APPROVAL_TEMPLATE = 'airhotels/custom_email/adminapproval_template';
    const APPROVAL_TO_OWNER_TEMPLATE = 'airhotels_custom_email_owner_confirmed_template';
    const APPROVAL_WITHOUT_ACCESS_CODE_TO_OWNER_TEMPLATE = 'airhotels_custom_email_owner_confirmed_without_access_key_template';
    const APPROVAL_WITHOUT_ACCESS_CODE_TO_RENTER_TEMPLATE = 'airhotels_custom_email_renter_confirmed_without_access_key_template';
    const CANCELED_TO_RENTER_TEMPLATE = 'airhotels_custom_email_renter_canceled_key_template';
    const CANCELED_TO_OWNER_TEMPLATE = 'airhotels_custom_email_owner_canceled_key_template';
    const AFTER_CONFIRMED_FOR_OWNER = 'sales_email/after_confirmed_for_owner/template';
    const PATH_AFTER_CONFIRMED_FOR_OWNER = 'sales_email/after_confirmed_for_owner/identity';
    const AFTER_CONFIRMED_FOR_RENTER = 'sales_email/after_confirmed_for_renter/template';
    const PATH_AFTER_CONFIRMED_FOR_RENTER = 'sales_email/after_confirmed_for_renter/identity';
    const AFTER_CENCELED_FOR_OWNER = 'sales_email/after_cenceled_for_owner/template';
    const PATH_AFTER_CENCELED_FOR_OWNER = 'sales_email/after_cenceled_for_owner/identity';
    const AFTER_CENCELED_FOR_RENTER = 'sales_email/after_cenceled_for_renter/template';
    const PATH_AFTER_CENCELED_FOR_RENTER = 'sales_email/after_cenceled_for_renter/identity';
    const AFTER_CONFIRM_TO_OWNER_WITHOUT_ACCESS_KEY = 'sales_email/after_confirm_to_owner_without_access_key/template';
    const AFTER_CONFIRM_TO_RENTER_WITHOUT_ACCESS_KEY = 'sales_email/after_confirm_to_renter_without_access_key/template';


    /**
     * Order states
     */
    const STATE_NEW = 'new';
    const STATE_PENDING_PAYMENT = 'pending_payment';
    const STATE_PROCESSING = 'processing';
    const STATE_COMPLETE = 'complete';
    const STATE_CLOSED = 'closed';
    const STATE_CANCELED = 'canceled';
    const STATE_HOLDED = 'holded';
    const STATE_PAYMENT_REVIEW = 'payment_review';

    /**
     * Order statuses
     */
    const STATUS_FRAUD = 'fraud';

    /**
     * Order flags
     */
    const ACTION_FLAG_CANCEL = 'cancel';
    const ACTION_FLAG_HOLD = 'hold';
    const ACTION_FLAG_UNHOLD = 'unhold';
    const ACTION_FLAG_EDIT = 'edit';
    const ACTION_FLAG_CREDITMEMO = 'creditmemo';
    const ACTION_FLAG_INVOICE = 'invoice';
    const ACTION_FLAG_REORDER = 'reorder';
    const ACTION_FLAG_SHIP = 'ship';
    const ACTION_FLAG_COMMENT = 'comment';

    /**
     * Report date types
     */
    const REPORT_DATE_TYPE_CREATED = 'created';
    const REPORT_DATE_TYPE_UPDATED = 'updated';

    protected $_eventPrefix = 'sales_order';
    protected $_eventObject = 'order';
    protected $_addresses = null;
    protected $_items = null;
    protected $_payments = null;
    protected $_statusHistory = null;
    protected $_invoices;
    protected $_tracks;
    protected $_shipments;
    protected $_creditmemos;
    protected $_relatedObjects = array();
    protected $_orderCurrency = null;
    protected $_baseCurrency = null;

    /**
     * Array of action flags for canUnhold, canEdit, etc.
     *
     * @var array
     */
    protected $_actionFlag = array();

    /**
     * Flag: if after order placing we can send new email to the customer.
     *
     * @var bool
     */
    protected $_canSendNewEmailFlag = true;

    /**
     * Initialize resource model
     */
    protected function _construct() {
        $this->_init('sales/order');
    }

    /**
     * Clear order object data
     *
     * @param string $key data key
     * @return Mage_Sales_Model_Order
     */
    public function unsetData($key = null) {
        parent::unsetData($key);
        if (is_null($key)) {
            $this->_items = null;
        }
        return $this;
    }

    /**
     * Retrieve can flag for action (edit, unhold, etc..)
     *
     * @param string $action
     * @return boolean|null
     */
    public function getActionFlag($action) {
        if (isset($this->_actionFlag[$action])) {
            return $this->_actionFlag[$action];
        }
        return null;
    }

    /**
     * Set can flag value for action (edit, unhold, etc...)
     *
     * @param string $action
     * @param boolean $flag
     * @return Mage_Sales_Model_Order
     */
    public function setActionFlag($action, $flag) {
        $this->_actionFlag[$action] = (boolean) $flag;
        return $this;
    }

    /**
     * Return flag for order if it can sends new email to customer.
     *
     * @return bool
     */
    public function getCanSendNewEmailFlag() {
        return $this->_canSendNewEmailFlag;
    }

    /**
     * Set flag for order if it can sends new email to customer.
     *
     * @param bool $flag
     * @return Mage_Sales_Model_Order
     */
    public function setCanSendNewEmailFlag($flag) {
        $this->_canSendNewEmailFlag = (boolean) $flag;
        return $this;
    }

    /**
     * Load order by system increment identifier
     *
     * @param string $incrementId
     * @return Mage_Sales_Model_Order
     */
    public function loadByIncrementId($incrementId) {
        return $this->loadByAttribute('increment_id', $incrementId);
    }

    /**
     * Load order by custom attribute value. Attribute value should be unique
     *
     * @param string $attribute
     * @param string $value
     * @return Mage_Sales_Model_Order
     */
    public function loadByAttribute($attribute, $value) {
        $this->load($value, $attribute);
        return $this;
    }

    /**
     * Retrieve store model instance
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore() {
        $storeId = $this->getStoreId();
        if ($storeId) {
            return Mage::app()->getStore($storeId);
        }
        return Mage::app()->getStore();
    }

    /**
     * Retrieve order cancel availability
     *
     * @return bool
     */
    public function canCancel() {
        if ($this->canUnhold()) {  // $this->isPaymentReview()
            return false;
        }

        $allInvoiced = true;
        foreach ($this->getAllItems() as $item) {
            if ($item->getQtyToInvoice()) {
                $allInvoiced = false;
                break;
            }
        }
        if ($allInvoiced) {
            return false;
        }

        $state = $this->getState();
        if ($this->isCanceled() || $state === self::STATE_COMPLETE || $state === self::STATE_CLOSED) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_CANCEL) === false) {
            return false;
        }

        /**
         * Use only state for availability detect
         */
        /* foreach ($this->getAllItems() as $item) {
          if ($item->getQtyToCancel()>0) {
          return true;
          }
          }
          return false; */
        return true;
    }

    /**
     * Getter whether the payment can be voided
     * @return bool
     */
    public function canVoidPayment() {
        if ($this->canUnhold() || $this->isPaymentReview()) {
            return false;
        }
        $state = $this->getState();
        if ($this->isCanceled() || $state === self::STATE_COMPLETE || $state === self::STATE_CLOSED) {
            return false;
        }
        return $this->getPayment()->canVoid(new Varien_Object);
    }

    /**
     * Retrieve order invoice availability
     *
     * @return bool
     */
    public function canInvoice() {
        if ($this->canUnhold() || $this->isPaymentReview()) {
            return false;
        }
        $state = $this->getState();
        if ($this->isCanceled() || $state === self::STATE_COMPLETE || $state === self::STATE_CLOSED) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_INVOICE) === false) {
            return false;
        }

        foreach ($this->getAllItems() as $item) {
            if ($item->getQtyToInvoice() > 0 && !$item->getLockedDoInvoice()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve order credit memo (refund) availability
     *
     * @return bool
     */
    public function canCreditmemo() {
        if ($this->hasForcedCanCreditmemo()) {
            return $this->getForcedCanCreditmemo();
        }

        if ($this->canUnhold() || $this->isPaymentReview()) {
            return false;
        }

        if ($this->isCanceled() || $this->getState() === self::STATE_CLOSED) {
            return false;
        }

        /**
         * We can have problem with float in php (on some server $a=762.73;$b=762.73; $a-$b!=0)
         * for this we have additional diapason for 0
         */
        if (abs($this->getTotalPaid() - $this->getTotalRefunded()) < .0001) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_EDIT) === false) {
            return false;
        }
        return true;
    }

    /**
     * Retrieve order hold availability
     *
     * @return bool
     */
    public function canHold() {
        $state = $this->getState();
        if ($this->isCanceled() || $this->isPaymentReview() || $state === self::STATE_COMPLETE || $state === self::STATE_CLOSED || $state === self::STATE_HOLDED) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_HOLD) === false) {
            return false;
        }
        return true;
    }

    /**
     * Retrieve order unhold availability
     *
     * @return bool
     */
    public function canUnhold() {
        if ($this->getActionFlag(self::ACTION_FLAG_UNHOLD) === false || $this->isPaymentReview()) {
            return false;
        }
        return $this->getState() === self::STATE_HOLDED;
    }

    /**
     * Check if comment can be added to order history
     *
     * @return bool
     */
    public function canComment() {
        if ($this->getActionFlag(self::ACTION_FLAG_COMMENT) === false) {
            return false;
        }
        return true;
    }

    /**
     * Retrieve order shipment availability
     *
     * @return bool
     */
    public function canShip() {
        if ($this->canUnhold() || $this->isPaymentReview()) {
            return false;
        }

        if ($this->getIsVirtual() || $this->isCanceled()) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_SHIP) === false) {
            return false;
        }

        foreach ($this->getAllItems() as $item) {
            if ($item->getQtyToShip() > 0 && !$item->getIsVirtual() && !$item->getLockedDoShip()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve order edit availability
     *
     * @return bool
     */
    public function canEdit() {
        if ($this->canUnhold()) {
            return false;
        }

        $state = $this->getState();
        if ($this->isCanceled() || $this->isPaymentReview() || $state === self::STATE_COMPLETE || $state === self::STATE_CLOSED) {
            return false;
        }

        if (!$this->getPayment()->getMethodInstance()->canEdit()) {
            return false;
        }

        if ($this->getActionFlag(self::ACTION_FLAG_EDIT) === false) {
            return false;
        }

        return true;
    }

    /**
     * Retrieve order reorder availability
     *
     * @return bool
     */
    public function canReorder() {
        if ($this->canUnhold() || $this->isPaymentReview() || !$this->getCustomerId()) {
            return false;
        }

        $products = array();
        foreach ($this->getItemsCollection() as $item) {
            $products[] = $item->getProductId();
        }

        if (!empty($products)) {
            /*
             * @TODO ACPAOC: Use product collection here, but ensure that product is loaded with order store id, otherwise there'll be problems with isSalable()
             * for configurables, bundles and other composites
             *
             */
            /*
              $productsCollection = Mage::getModel('catalog/product')->getCollection()
              ->setStoreId($this->getStoreId())
              ->addIdFilter($products)
              ->addAttributeToSelect('status')
              ->load();

              foreach ($productsCollection as $product) {
              if (!$product->isSalable()) {
              return false;
              }
              }
             */

            foreach ($products as $productId) {
                $product = Mage::getModel('catalog/product')
                        ->setStoreId($this->getStoreId())
                        ->load($productId);
                if (!$product->getId() || !$product->isSalable()) {
                    return false;
                }
            }
        }

        if ($this->getActionFlag(self::ACTION_FLAG_REORDER) === false) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the payment is in payment review state
     * In this state order cannot be normally processed. Possible actions can be:
     * - accept or deny payment
     * - fetch transaction information
     *
     * @return bool
     */
    public function isPaymentReview() {
        return $this->getState() === self::STATE_PAYMENT_REVIEW;
    }

    /**
     * Check whether payment can be accepted or denied
     *
     * @return bool
     */
    public function canReviewPayment() {
        return $this->isPaymentReview() && $this->getPayment()->canReviewPayment();
    }

    /**
     * Check whether there can be a transaction update fetched for payment in review state
     *
     * @return bool
     */
    public function canFetchPaymentReviewUpdate() {
        return $this->isPaymentReview() && $this->getPayment()->canFetchTransactionInfo();
    }

    /**
     * Retrieve order configuration model
     *
     * @return Mage_Sales_Model_Order_Config
     */
    public function getConfig() {
        return Mage::getSingleton('sales/order_config');
    }

    /**
     * Place order payments
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _placePayment() {
        $this->getPayment()->place();
        return $this;
    }

    /**
     * Retrieve order payment model object
     *
     * @return Mage_Sales_Model_Order_Payment
     */
    public function getPayment() {
        foreach ($this->getPaymentsCollection() as $payment) {
            if (!$payment->isDeleted()) {
                return $payment;
            }
        }
        return false;
    }

    /**
     * Declare order billing address
     *
     * @param   Mage_Sales_Model_Order_Address $address
     * @return  Mage_Sales_Model_Order
     */
    public function setBillingAddress(Mage_Sales_Model_Order_Address $address) {
        $old = $this->getBillingAddress();
        if (!empty($old)) {
            $address->setId($old->getId());
        }
        $this->addAddress($address->setAddressType('billing'));
        return $this;
    }

    /**
     * Declare order shipping address
     *
     * @param   Mage_Sales_Model_Order_Address $address
     * @return  Mage_Sales_Model_Order
     */
    public function setShippingAddress(Mage_Sales_Model_Order_Address $address) {
        $old = $this->getShippingAddress();
        if (!empty($old)) {
            $address->setId($old->getId());
        }
        $this->addAddress($address->setAddressType('shipping'));
        return $this;
    }

    /**
     * Retrieve order billing address
     *
     * @return Mage_Sales_Model_Order_Address
     */
    public function getBillingAddress() {
        foreach ($this->getAddressesCollection() as $address) {
            if ($address->getAddressType() == 'billing' && !$address->isDeleted()) {
                return $address;
            }
        }
        return false;
    }

    /**
     * Retrieve order shipping address
     *
     * @return Mage_Sales_Model_Order_Address
     */
    public function getShippingAddress() {
        foreach ($this->getAddressesCollection() as $address) {
            if ($address->getAddressType() == 'shipping' && !$address->isDeleted()) {
                return $address;
            }
        }
        return false;
    }

    /**
     * Order state setter.
     * If status is specified, will add order status history with specified comment
     * the setData() cannot be overriden because of compatibility issues with resource model
     *
     * @param string $state
     * @param string|bool $status
     * @param string $comment
     * @param bool $isCustomerNotified
     * @return Mage_Sales_Model_Order
     */
    public function setState($state, $status = false, $comment = '', $isCustomerNotified = null) {
        return $this->_setState($state, $status, $comment, $isCustomerNotified, true);
    }

    /**
     * Order state protected setter.
     * By default allows to set any state. Can also update status to default or specified value
     * Сomplete and closed states are encapsulated intentionally, see the _checkState()
     *
     * @param string $state
     * @param string|bool $status
     * @param string $comment
     * @param bool $isCustomerNotified
     * @param $shouldProtectState
     * @return Mage_Sales_Model_Order
     */
    protected function _setState($state, $status = false, $comment = '', $isCustomerNotified = null, $shouldProtectState = false) {
        // attempt to set the specified state
        if ($shouldProtectState) {
            if ($this->isStateProtected($state)) {
                Mage::throwException(Mage::helper('sales')->__('The Order State "%s" must not be set manually.', $state));
            }
        }
        $this->setData('state', $state);

        // add status history
        if ($status) {
            if ($status === true) {
                $status = $this->getConfig()->getStateDefaultStatus($state);
            }
            $this->setStatus($status);
            $history = $this->addStatusHistoryComment($comment, false); // no sense to set $status again
            $history->setIsCustomerNotified($isCustomerNotified); // for backwards compatibility
        }

        // Returning status from paypal and if status equal to complete then we can send the coupon (voucher) for user
        // $status == 'payment_review'
        //if($status == 'complete' || $status == 'payment_review' || $status == 'pending') {
        if ($status == 'complete' || $status == 'processing' || $status == 'canceled') {
            $this->_bookingUpdate($this->getRealOrderId());
            //mail('bhanupriya@contus.in','dharani@contus.in',$this->getRealOrderId());
        }
        return $this;
    }

    public function _bookingUpdate($order_id) {

        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $booking_table = $tprefix . 'airhotels_booking';

        $write->query("UPDATE $booking_table set order_status = 1 where order_id  = $order_id");
        $this->bookingStatus($order_id);
    }

    /**
     * Order Status update mail 
     */
    public function bookingStatus($order_id) {
        $hostEmail = array();
        $value = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        $buyerEmail = $value->getCustomerEmail(); //buyer Email
        $buyerName = $value->getCustomerName(); //buyer Name
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $booking_table = $tprefix . 'airhotels_booking';
        $select = "select * from $booking_table where order_id = $order_id ";
        $result = $read->fetchRow($select);
        $productId = $result['entity_id'];
        $access_code = $result['access_code'];
        $model = Mage::getModel('catalog/product');
        $_product = $model->load($productId);
        $SpaceName = $_product->getName();
        $SpaceUrl = $_product->getProductUrl();
        $hostId = $_product->getUserid(); //property owner Id
        $customer = Mage::getModel('customer/customer')->load($hostId);
        $ownerEmail = $customer->getEmail(); //Property Email Owner
        $hostName = $customer->getName(); //Property  Owner name
        $status = $this->getStatusLabel(); //order Status
        $adminEmail = Mage::getStoreConfig('airhotels/order_reminder/admin_email'); // admin email

        $postObject = new Varien_Object();
        $postObject->setData(array(
            'incrementid' => $order_id,
            'status' => $status,
            'customername' => $buyerName,
            'ownername' => $hostName,
            'spacename' => $SpaceName,
            'spaceurl' => $SpaceUrl,
            'accesscode' => $access_code
        ));

        if ($status == 'Complete') {
            $templateToOwner = ($access_code == 0 ? Mage::getStoreConfig(self::AFTER_CONFIRM_TO_OWNER_WITHOUT_ACCESS_KEY) : Mage::getStoreConfig(self::AFTER_CONFIRMED_FOR_OWNER));
            $templateToRenter = ($access_code == 0 ? Mage::getStoreConfig(self::AFTER_CONFIRM_TO_RENTER_WITHOUT_ACCESS_KEY) : Mage::getStoreConfig(self::AFTER_CONFIRMED_FOR_RENTER));

            $mailTemplate_customer = Mage::getModel('core/email_template');
            $mailTemplate_customer->setSenderName(Mage::getStoreConfig('design/head/default_title'));
            $mailTemplate_customer->setTemplateSubject('Order Status');
            $mailTemplate_customer->addBcc(array($adminEmail));

            $mailTemplate_customer->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                            $templateToRenter, Mage::getStoreConfig(self::PATH_AFTER_CONFIRMED_FOR_RENTER), $buyerEmail, Mage::getStoreConfig('design/head/default_title'), array('order' => $postObject)
            );

            $sTemplate1 = Mage::getModel('core/email_template')
                    ->load(4)
                    ->getProcessedTemplate(array('order' => $postObject));

            $doc1 = new DOMDocument;
            $doc1->loadHTML($sTemplate1);
            $xpath1 = new DOMXPath($doc1);
            $node1 = $xpath1->query('//table')->item(1);
            $html1 = $doc1->saveHTML($node1);
            $text1 = strip_tags($html1, "<a>");
            $this->mailToInbox($mailTemplate_customer, $text1, true);

            $mailTemplate_owner = Mage::getModel('core/email_template');
            $mailTemplate_owner->setSenderName(Mage::getStoreConfig('design/head/default_title'));
            $mailTemplate_owner->setTemplateSubject('Order Status');
            $mailTemplate_owner->addBcc(array($adminEmail));
            $mailTemplate_owner->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                            $templateToOwner, Mage::getStoreConfig(self::PATH_AFTER_CONFIRMED_FOR_OWNER), $ownerEmail, Mage::getStoreConfig('design/head/default_title'), array('order' => $postObject)
            );


            $sTemplate2 = Mage::getModel('core/email_template')
                    ->load(3)
                    ->getProcessedTemplate(array('order' => $postObject));

            $doc2 = new DOMDocument;
            $doc2->loadHTML($sTemplate2);
            $xpath2 = new DOMXPath($doc2);
            $node2 = $xpath2->query('//table')->item(1);
            $html2 = $doc2->saveHTML($node2);
            $text2 = strip_tags($html2, "<a>");
            $this->mailToInbox($mailTemplate_customer, $text2, true);
        } elseif ($status == 'Canceled') {
            $templateToOwner = Mage::getStoreConfig(self::AFTER_CENCELED_FOR_OWNER);
            $templateToRenter = Mage::getStoreConfig(self::AFTER_CENCELED_FOR_RENTER);

            $mailTemplate_customer = Mage::getModel('core/email_template');
            $mailTemplate_customer->setSenderName(Mage::getStoreConfig('design/head/default_title'));
            $mailTemplate_customer->setTemplateSubject('Order Status');
            $mailTemplate_customer->addBcc(array($adminEmail));

            $mailTemplate_customer->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                            $templateToRenter, Mage::getStoreConfig(self::PATH_AFTER_CENCELED_FOR_RENTER), $buyerEmail, Mage::getStoreConfig('design/head/default_title'), array('order' => $postObject)
            );
            $sTemplate3 = Mage::getModel('core/email_template')
                    ->load(6)
                    ->getProcessedTemplate(array('order' => $postObject));

            $doc3 = new DOMDocument;
            $doc3->loadHTML($sTemplate3);
            $xpath3 = new DOMXPath($doc3);
            $node3 = $xpath3->query('//table')->item(1);
            $html3 = $doc3->saveHTML($node3);
            $text3 = strip_tags($html3, "<a>");
            $this->mailToInbox($mailTemplate_customer, $text3, true);

            $mailTemplate_owner = Mage::getModel('core/email_template');
            $mailTemplate_owner->setSenderName(Mage::getStoreConfig('design/head/default_title'));
            $mailTemplate_owner->setTemplateSubject('Order Status');
            $mailTemplate_owner->addBcc(array($adminEmail));
            $mailTemplate_owner->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                            $templateToOwner, Mage::getStoreConfig(self::PATH_AFTER_CENCELED_FOR_OWNER), $ownerEmail, Mage::getStoreConfig('design/head/default_title'), array('order' => $postObject)
            );
            $sTemplate4 = Mage::getModel('core/email_template')
                    ->load(5)
                    ->getProcessedTemplate(array('order' => $postObject));

            $doc4 = new DOMDocument;
            $doc4->loadHTML($sTemplate4);
            $xpath4 = new DOMXPath($doc4);
            $node4 = $xpath->query('//table')->item(1);
            $html4 = $doc4->saveHTML($node4);
            $text4 = strip_tags($html4, "<a>");
            $this->mailToInbox($mailTemplate_customer, $text4, true);
        }
    }

    /**
     * Whether specified state can be set from outside
     * @param $state
     * @return bool
     */
    public function isStateProtected($state) {
        if (empty($state)) {
            return false;
        }
        return self::STATE_COMPLETE == $state || self::STATE_CLOSED == $state;
    }

    /**
     * Retrieve label of order status
     *
     * @return string
     */
    public function getStatusLabel() {
        return $this->getConfig()->getStatusLabel($this->getStatus());
    }

    /**
     * Add status change information to history
     * @deprecated after 1.4.0.0-alpha3
     *
     * @param  string $status
     * @param  string $comment
     * @param  bool $isCustomerNotified
     * @return Mage_Sales_Model_Order
     */
    public function addStatusToHistory($status, $comment = '', $isCustomerNotified = false) {
        $history = $this->addStatusHistoryComment($comment, $status)
                ->setIsCustomerNotified($isCustomerNotified);
        return $this;
    }

    /*
     * Add a comment to order
     * Different or default status may be specified
     *
     * @param string $comment
     * @param string $status
     * @return Mage_Sales_Order_Status_History
     */

    public function addStatusHistoryComment($comment, $status = false) {
        if (false === $status) {
            $status = $this->getStatus();
        } elseif (true === $status) {
            $status = $this->getConfig()->getStateDefaultStatus($this->getState());
        } else {
            $this->setStatus($status);
        }
        $history = Mage::getModel('sales/order_status_history')
                ->setStatus($status)
                ->setComment($comment);
        $this->addStatusHistory($history);
        return $history;
    }

    /**
     * Place order
     *
     * @return Mage_Sales_Model_Order
     */
    public function place() {
        Mage::dispatchEvent('sales_order_place_before', array('order' => $this));
        $this->_placePayment();
        Mage::dispatchEvent('sales_order_place_after', array('order' => $this));
        return $this;
    }

    public function hold() {
        if (!$this->canHold()) {
            Mage::throwException(Mage::helper('sales')->__('Hold action is not available.'));
        }
        $this->setHoldBeforeState($this->getState());
        $this->setHoldBeforeStatus($this->getStatus());
        $this->setState(self::STATE_HOLDED, true);
        return $this;
    }

    /**
     * Attempt to unhold the order
     *
     * @return Mage_Sales_Model_Order
     * @throws Mage_Core_Exception
     */
    public function unhold() {
        if (!$this->canUnhold()) {
            Mage::throwException(Mage::helper('sales')->__('Unhold action is not available.'));
        }
        $this->setState($this->getHoldBeforeState(), $this->getHoldBeforeStatus());
        $this->setHoldBeforeState(null);
        $this->setHoldBeforeStatus(null);
        return $this;
    }

    /**
     * Cancel order
     *
     * @return Mage_Sales_Model_Order
     */
    public function cancel() {
        if ($this->canCancel()) {
            $this->getPayment()->cancel();
            $this->registerCancellation();

            Mage::dispatchEvent('order_cancel_after', array('order' => $this));
        }

        return $this;
    }

    /**
     * Prepare order totals to cancellation
     * @param string $comment
     * @param bool $graceful
     * @return Mage_Sales_Model_Order
     * @throws Mage_Core_Exception
     */
    public function registerCancellation($comment = '', $graceful = true) {
        if ($this->canCancel()) {
            $cancelState = self::STATE_CANCELED;
            foreach ($this->getAllItems() as $item) {
                if ($cancelState != self::STATE_PROCESSING && $item->getQtyToRefund()) {
                    if ($item->getQtyToShip() > $item->getQtyToCancel()) {
                        $cancelState = self::STATE_PROCESSING;
                    } else {
                        $cancelState = self::STATE_COMPLETE;
                    }
                }
                $item->cancel();
            }

            $this->setSubtotalCanceled($this->getSubtotal() - $this->getSubtotalInvoiced());
            $this->setBaseSubtotalCanceled($this->getBaseSubtotal() - $this->getBaseSubtotalInvoiced());

            $this->setTaxCanceled($this->getTaxAmount() - $this->getTaxInvoiced());
            $this->setBaseTaxCanceled($this->getBaseTaxAmount() - $this->getBaseTaxInvoiced());

            $this->setShippingCanceled($this->getShippingAmount() - $this->getShippingInvoiced());
            $this->setBaseShippingCanceled($this->getBaseShippingAmount() - $this->getBaseShippingInvoiced());

            $this->setDiscountCanceled(abs($this->getDiscountAmount()) - $this->getDiscountInvoiced());
            $this->setBaseDiscountCanceled(abs($this->getBaseDiscountAmount()) - $this->getBaseDiscountInvoiced());

            $this->setTotalCanceled($this->getSubtotalCanceled() + $this->getTaxCanceled() + $this->getShippingCanceled() - $this->getDiscountCanceled());
            $this->setBaseTotalCanceled($this->getBaseSubtotalCanceled() + $this->getBaseTaxCanceled() + $this->getBaseShippingCanceled() - $this->getBaseDiscountCanceled());

            $this->_setState($cancelState, true, $comment);
        } elseif (!$graceful) {
            Mage::throwException(Mage::helper('sales')->__('Order does not allow to be canceled.'));
        }
        return $this;
    }

    /**
     * Retrieve tracking numbers
     *
     * @return array
     */
    public function getTrackingNumbers() {
        if ($this->getData('tracking_numbers')) {
            return explode(',', $this->getData('tracking_numbers'));
        }
        return array();
    }

    public function getShippingCarrier() {
        $carrierModel = $this->getData('shipping_carrier');
        if (is_null($carrierModel)) {
            $carrierModel = false;
            /**
             * $method - carrier_method
             */
            if ($method = $this->getShippingMethod()) {
                $data = explode('_', $method);
                $carrierCode = $data[0];
                $className = Mage::getStoreConfig('carriers/' . $carrierCode . '/model');
                if ($className) {
                    $carrierModel = Mage::getModel($className);
                }
            }
            $this->setData('shipping_carrier', $carrierModel);
        }
        return $carrierModel;
    }

    /**
     * Send email with order data
     *
     * @return Mage_Sales_Model_Order
     */
    public function sendNewOrderEmail() {//echo Mage::helper('adminhtml')->getUrl('wepay/api/confirm',array('order_id'=>$this->getIncrementId()));die();
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);

        // Start store emulation process
        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

        try {
            // Retrieve specified view block from appropriate design package (depends on emulated store)
            $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())
                    ->setIsSecureMode(true);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
        } catch (Exception $exception) {
            // Stop store emulation process
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            throw $exception;
        }

        // Stop store emulation process
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

        // Retrieve corresponding email template id and customer name
        if ($this->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
            $customerName = $this->getCustomerName();
        }

        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getCustomerEmail(), $customerName);
        if ($copyTo && $copyMethod == 'bcc') {
            // Add bcc to customer email
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
//            /*host start*/
//         $productId =  Mage::getSingleton('core/session')->getProductID();
//         $model = Mage::getModel('catalog/product');
//         $_product = $model->load($productId);
//         $SpaceName = $_product->getName();
//         $hostId = $_product->getUserid();//property owner Id
//         $customer = Mage::getModel('customer/customer')->load($hostId);
//         $emailInfo->addTo($customer->getEmail(), $customer->getName());
//
//        /*host end*/
        $mailer->addEmailInfo($emailInfo);

        // Email copies are sent as separated emails if their copy method is 'copy'
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails

        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        //  echo "<pre>";var_dump($mailer);die();
        $mailer->setTemplateParams(array(
            'order' => $this,
            'billing' => $this->getBillingAddress(),
            'payment_html' => $paymentBlockHtml
                )
        );
        $sTemplate = Mage::getModel('core/email_template')
                ->load(10)
                ->getProcessedTemplate(array(
            'order' => $this,
            'billing' => $this->getBillingAddress(),
            'payment_html' => $paymentBlockHtml
        ));
        $doc = new DOMDocument;
        $doc->loadHTML($sTemplate);
        $xpath = new DOMXPath($doc);
        $node = $xpath->query('//table')->item(1);
        $html = $doc->saveHTML($node);
        $text = strip_tags($html, "<a>");
        $mailer->send();
        $this->mailToInbox($mailer, $text);

        // send confiramtion mail to owner if it enabeled in config
        if (Mage::getStoreConfig('sales_email/owner/enabled') == 1) {
            if ($this->getCustomerIsGuest()) {
                $ownerTemplateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_OWNER_GUEST_TEMPLATE, $storeId);
                $ownerCustomerName = $this->getBillingAddress()->getName();
            } else {
                $ownerTemplateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_OWNER_TEMPLATE, $storeId);
                $ownerCustomerName = $this->getCustomerName();
            }
            $mailer_2 = Mage::getModel('core/email_template_mailer');
            $emailInfo_2 = Mage::getModel('core/email_info');
            $productId = Mage::getSingleton('core/session')->getProductID();
            $model = Mage::getModel('catalog/product');
            $_product = $model->load($productId);
            $SpaceName = $_product->getName();
            $hostId = $_product->getUserid(); //property owner Id
            $customer = Mage::getModel('customer/customer')->load($hostId);
            $emailInfo_2->addTo($customer->getEmail(), $customer->getName());
            $mailer_2->addEmailInfo($emailInfo_2);
            $mailer_2->setSender(Mage::getStoreConfig('sales_email/order/identity'));
            $mailer_2->setTemplateId($ownerTemplateId);
            $mailer_2->setTemplateParams(array(
                'order' => $this,
                'billing' => $this->getBillingAddress(),
                'payment_html' => $paymentBlockHtml,
                'confirm_link' => Mage::helper('adminhtml')->getUrl('wepay/api/confirm', array('order_id' => $this->getIncrementId())),
                'cancel_link' => Mage::helper('adminhtml')->getUrl('wepay/api/cancel', array('order_id' => $this->getIncrementId()))
                    )
            );

            $sTemplate = Mage::getModel('core/email_template')
                    ->load(1)
                    ->getProcessedTemplate(array(
                'order' => $this,
                'billing' => $this->getBillingAddress(),
                'payment_html' => $paymentBlockHtml,
                'confirm_link' => Mage::helper('adminhtml')->getUrl('wepay/api/confirm', array('order_id' => $this->getIncrementId())),
                'cancel_link' => Mage::helper('adminhtml')->getUrl('wepay/api/cancel', array('order_id' => $this->getIncrementId()))
            ));
            $doc = new DOMDocument;
            $doc->loadHTML($sTemplate);
            $xpath = new DOMXPath($doc);
            $node = $xpath->query('//table')->item(1);
            $html = $doc->saveHTML($node);
            $text = strip_tags($html, "<a>");
            $mailer_2->send();
            $this->mailToInbox($mailer_2, $text);
        }
        //$this->hostEmail($paymentBlockHtml);
        $this->setEmailSent(true);
        $this->_getResource()->saveAttribute($this, 'email_sent');

        return $this;
    }

    function mailToInbox($mailerObj, $text, $isTemplateObj = false) {
        if ($isTemplateObj) {
            $mailContent = $mailerObj->emailText;
        } else {
            $mailContent = $mailerObj->getMailContent()->emailText;
        }


        //$from = date("Y-m-d", strtotime($this->getRequest()->getParam('from')));
        // $to = date("Y-m-d", strtotime($this->getRequest()->getParam('to')));

        $productId = Mage::getSingleton('core/session');
        //echo "<pre>";var_dump($productId);die();
        $model = Mage::getModel('catalog/product');
        $_product = $model->load($productId[0]);
        $SpaceName = $_product->getName();
        $hostId = $_product->getUserid(); //property owner Id
        $owner = Mage::getModel('customer/customer')->load($hostId);
        $productId = Mage::getSingleton('core/session')->getProductIDs();

        $parametyers = array(
            0 => $owner->getId(),
            1 => $productId[0],
            2 => '2014-06-10',
            3 => '2014-06-11',
            4 => '1',
            5 => $text,
            6 => '1',
            7 => '0',
            8 => '65465'
        );
        Mage::getModel('airhotels/airhotels')->saveInbox($parametyers);
    }

    public function getMessageData($messageid) {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $inbox_table = $tprefix . 'airhotels_customer_inbox';
        $select = "select * from $inbox_table where message_id = $messageid ";
        $result = $read->fetchRow($select);
        return $result;
    }

    /**
     * Send email with order update information
     *
     * @param boolean $notifyCustomer
     * @param string $comment
     * @return Mage_Sales_Model_Order
     */
    public function sendOrderUpdateEmail($notifyCustomer = true, $comment = '') {
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendOrderCommentEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_UPDATE_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_COPY_METHOD, $storeId);
        // Check if at least one recepient is found
        if (!$notifyCustomer && !$copyTo) {
            return $this;
        }

        // Retrieve corresponding email template id and customer name
        if ($this->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId);
            $customerName = $this->getCustomerName();
        }

        $mailer = Mage::getModel('core/email_template_mailer');
        if ($notifyCustomer) {
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($this->getCustomerEmail(), $customerName);
            if ($copyTo && $copyMethod == 'bcc') {
                // Add bcc to customer email
                foreach ($copyTo as $email) {
                    $emailInfo->addBcc($email);
                }
            }
            $mailer->addEmailInfo($emailInfo);
        }

        // Email copies are sent as separated emails if their copy method is 'copy' or a customer should not be notified
        if ($copyTo && ($copyMethod == 'copy' || !$notifyCustomer)) {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
            'order' => $this,
            'comment' => $comment,
            'billing' => $this->getBillingAddress()
                )
        );

        $mailer->send();

        return $this;
    }

    protected function _getEmails($configPath) {
        $data = Mage::getStoreConfig($configPath, $this->getStoreId());
        if (!empty($data)) {
            return explode(',', $data);
        }
        return false;
    }

    /*     * ********************* ADDRESSES ************************** */

    public function getAddressesCollection() {
        if (is_null($this->_addresses)) {
            $this->_addresses = Mage::getResourceModel('sales/order_address_collection')
                    ->setOrderFilter($this);

            if ($this->getId()) {
                foreach ($this->_addresses as $address) {
                    $address->setOrder($this);
                }
            }
        }

        return $this->_addresses;
    }

    public function getAddressById($addressId) {
        foreach ($this->getAddressesCollection() as $address) {
            if ($address->getId() == $addressId) {
                return $address;
            }
        }
        return false;
    }

    public function addAddress(Mage_Sales_Model_Order_Address $address) {
        $address->setOrder($this)->setParentId($this->getId());
        if (!$address->getId()) {
            $this->getAddressesCollection()->addItem($address);
        }
        return $this;
    }

    public function getItemsCollection($filterByTypes = array(), $nonChildrenOnly = false) {
        if (is_null($this->_items)) {
            $this->_items = Mage::getResourceModel('sales/order_item_collection')
                    ->setOrderFilter($this);

            if ($filterByTypes) {
                $this->_items->filterByTypes($filterByTypes);
            }
            if ($nonChildrenOnly) {
                $this->_items->filterByParent();
            }

            if ($this->getId()) {
                foreach ($this->_items as $item) {
                    $item->setOrder($this);
                }
            }
        }
        return $this->_items;
    }

    /**
     * Get random items collection with related children
     *
     * @param int $limit
     * @return Mage_Sales_Model_Mysql4_Order_Item_Collection
     */
    public function getItemsRandomCollection($limit = 1) {
        return $this->_getItemsRandomCollection($limit);
    }

    /**
     * Get random items collection without related children
     *
     * @param int $limit
     * @return Mage_Sales_Model_Mysql4_Order_Item_Collection
     */
    public function getParentItemsRandomCollection($limit = 1) {
        return $this->_getItemsRandomCollection($limit, true);
    }

    /**
     * Get random items collection with or without related children
     *
     * @param int $limit
     * @param bool $nonChildrenOnly
     * @return Mage_Sales_Model_Mysql4_Order_Item_Collection
     */
    protected function _getItemsRandomCollection($limit, $nonChildrenOnly = false) {
        $collection = Mage::getModel('sales/order_item')->getCollection()
                ->setOrderFilter($this)
                ->setRandomOrder();

        if ($nonChildrenOnly) {
            $collection->filterByParent();
        }
        $products = array();
        foreach ($collection as $item) {
            $products[] = $item->getProductId();
        }

        $productsCollection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addIdFilter($products)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds())
                /* Price data is added to consider item stock status using price index */
                ->addPriceData()
                ->setPageSize($limit)
                ->load();

        foreach ($collection as $item) {
            $product = $productsCollection->getItemById($item->getProductId());
            if ($product) {
                $item->setProduct($product);
            }
        }

        return $collection;
    }

    public function getAllItems() {
        $items = array();
        foreach ($this->getItemsCollection() as $item) {
            if (!$item->isDeleted()) {
                $items[] = $item;
            }
        }
        return $items;
    }

    public function getAllVisibleItems() {
        $items = array();
        foreach ($this->getItemsCollection() as $item) {
            if (!$item->isDeleted() && !$item->getParentItemId()) {
                $items[] = $item;
            }
        }
        return $items;
    }

    public function getItemById($itemId) {
        return $this->getItemsCollection()->getItemById($itemId);
    }

    public function getItemByQuoteItemId($quoteItemId) {
        foreach ($this->getItemsCollection() as $item) {
            if ($item->getQuoteItemId() == $quoteItemId) {
                return $item;
            }
        }
        return null;
    }

    public function addItem(Mage_Sales_Model_Order_Item $item) {
        $item->setOrder($this);
        if (!$item->getId()) {
            $this->getItemsCollection()->addItem($item);
        }
        return $this;
    }

    /**
     * Whether the order has nominal items only
     *
     * @return bool
     */
    public function isNominal() {
        foreach ($this->getAllVisibleItems() as $item) {
            if ('0' == $item->getIsNominal()) {
                return false;
            }
        }
        return true;
    }

    /*     * ********************* PAYMENTS ************************** */

    public function getPaymentsCollection() {
        if (is_null($this->_payments)) {
            $this->_payments = Mage::getResourceModel('sales/order_payment_collection')
                    ->setOrderFilter($this);

            if ($this->getId()) {
                foreach ($this->_payments as $payment) {
                    $payment->setOrder($this);
                }
            }
        }
        return $this->_payments;
    }

    public function getAllPayments() {
        $payments = array();
        foreach ($this->getPaymentsCollection() as $payment) {
            if (!$payment->isDeleted()) {
                $payments[] = $payment;
            }
        }
        return $payments;
    }

    public function getPaymentById($paymentId) {
        foreach ($this->getPaymentsCollection() as $payment) {
            if ($payment->getId() == $paymentId) {
                return $payment;
            }
        }
        return false;
    }

    public function addPayment(Mage_Sales_Model_Order_Payment $payment) {
        $payment->setOrder($this)
                ->setParentId($this->getId());
        if (!$payment->getId()) {
            $this->getPaymentsCollection()->addItem($payment);
        }
        return $this;
    }

    public function setPayment(Mage_Sales_Model_Order_Payment $payment) {
        if (!$this->getIsMultiPayment() && ($old = $this->getPayment())) {
            $payment->setId($old->getId());
        }
        $this->addPayment($payment);
        return $payment;
    }

    /*     * ********************* STATUSES ************************** */

    /**
     * Enter description here...
     *
     * @return Mage_Sales_Model_Entity_Order_Status_History_Collection
     */
    public function getStatusHistoryCollection($reload = false) {
        if (is_null($this->_statusHistory) || $reload) {
            $this->_statusHistory = Mage::getResourceModel('sales/order_status_history_collection')
                    ->setOrderFilter($this)
                    ->setOrder('created_at', 'desc')
                    ->setOrder('entity_id', 'desc');

            if ($this->getId()) {
                foreach ($this->_statusHistory as $status) {
                    $status->setOrder($this);
                }
            }
        }
        return $this->_statusHistory;
    }

    /**
     * Return collection of order status history items.
     *
     * @return array
     */
    public function getAllStatusHistory() {
        $history = array();
        foreach ($this->getStatusHistoryCollection() as $status) {
            if (!$status->isDeleted()) {
                $history[] = $status;
            }
        }
        return $history;
    }

    /**
     * Return collection of visible on frontend order status history items.
     *
     * @return array
     */
    public function getVisibleStatusHistory() {
        $history = array();
        foreach ($this->getStatusHistoryCollection() as $status) {
            if (!$status->isDeleted() && $status->getComment() && $status->getIsVisibleOnFront()) {
                $history[] = $status;
            }
        }
        return $history;
    }

    public function getStatusHistoryById($statusId) {
        foreach ($this->getStatusHistoryCollection() as $status) {
            if ($status->getId() == $statusId) {
                return $status;
            }
        }
        return false;
    }

    /**
     * Set the order status history object and the order object to each other
     * Adds the object to the status history collection, which is automatically saved when the order is saved.
     * See the entity_id attribute backend model.
     * Or the history record can be saved standalone after this.
     *
     * @param Mage_Sales_Model_Order_Status_History $status
     * @return Mage_Sales_Model_Order
     */
    public function addStatusHistory(Mage_Sales_Model_Order_Status_History $history) {
        $history->setOrder($this);
        $this->setStatus($history->getStatus());
        if (!$history->getId()) {
            $this->getStatusHistoryCollection()->addItem($history);
        }
        return $this;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getRealOrderId() {
        $id = $this->getData('real_order_id');
        if (is_null($id)) {
            $id = $this->getIncrementId();
        }
        return $id;
    }

    /**
     * Get currency model instance. Will be used currency with which order placed
     *
     * @return Mage_Directory_Model_Currency
     */
    public function getOrderCurrency() {
        if (is_null($this->_orderCurrency)) {
            $this->_orderCurrency = Mage::getModel('directory/currency')->load($this->getOrderCurrencyCode());
        }
        return $this->_orderCurrency;
    }

    /**
     * Get formated price value including order currency rate to order website currency
     *
     * @param   float $price
     * @param   bool  $addBrackets
     * @return  string
     */
    public function formatPrice($price, $addBrackets = false) {
        return $this->formatPricePrecision($price, 2, $addBrackets);
    }

    public function formatPricePrecision($price, $precision, $addBrackets = false) {
        return $this->getOrderCurrency()->formatPrecision($price, $precision, array(), true, $addBrackets);
    }

    /**
     * Retrieve text formated price value includeing order rate
     *
     * @param   float $price
     * @return  string
     */
    public function formatPriceTxt($price) {
        return $this->getOrderCurrency()->formatTxt($price);
    }

    /**
     * Retrieve order website currency for working with base prices
     *
     * @return Mage_Directory_Model_Currency
     */
    public function getBaseCurrency() {
        if (is_null($this->_baseCurrency)) {
            $this->_baseCurrency = Mage::getModel('directory/currency')->load($this->getBaseCurrencyCode());
        }
        return $this->_baseCurrency;
    }

    /**
     * Retrieve order website currency for working with base prices
     * @deprecated  please use getBaseCurrency instead.
     *
     * @return Mage_Directory_Model_Currency
     */
    public function getStoreCurrency() {
        return $this->getData('store_currency');
    }

    public function formatBasePrice($price) {
        return $this->formatBasePricePrecision($price, 2);
    }

    public function formatBasePricePrecision($price, $precision) {
        return $this->getBaseCurrency()->formatPrecision($price, $precision);
    }

    public function isCurrencyDifferent() {
        return $this->getOrderCurrencyCode() != $this->getBaseCurrencyCode();
    }

    /**
     * Retrieve order total due value
     *
     * @return float
     */
    public function getTotalDue() {
        $total = $this->getGrandTotal() - $this->getTotalPaid();
        $total = Mage::app()->getStore($this->getStoreId())->roundPrice($total);
        return max($total, 0);
    }

    /**
     * Retrieve order total due value
     *
     * @return float
     */
    public function getBaseTotalDue() {
        $total = $this->getBaseGrandTotal() - $this->getBaseTotalPaid();
        $total = Mage::app()->getStore($this->getStoreId())->roundPrice($total);
        return max($total, 0);
    }

    public function getData($key = '', $index = null) {
        if ($key == 'total_due') {
            return $this->getTotalDue();
        }
        if ($key == 'base_total_due') {
            return $this->getBaseTotalDue();
        }
        return parent::getData($key, $index);
    }

    /**
     * Retrieve order invoices collection
     *
     * @return unknown
     */
    public function getInvoiceCollection() {
        if (is_null($this->_invoices)) {
            $this->_invoices = Mage::getResourceModel('sales/order_invoice_collection')
                    ->setOrderFilter($this);

            if ($this->getId()) {
                foreach ($this->_invoices as $invoice) {
                    $invoice->setOrder($this);
                }
            }
        }
        return $this->_invoices;
    }

    /**
     * Retrieve order shipments collection
     *
     * @return unknown
     */
    public function getShipmentsCollection() {
        if (empty($this->_shipments)) {
            if ($this->getId()) {
                $this->_shipments = Mage::getResourceModel('sales/order_shipment_collection')
                        ->setOrderFilter($this)
                        ->load();
            } else {
                return false;
            }
        }
        return $this->_shipments;
    }

    /**
     * Retrieve order creditmemos collection
     *
     * @return unknown
     */
    public function getCreditmemosCollection() {
        if (empty($this->_creditmemos)) {
            if ($this->getId()) {
                $this->_creditmemos = Mage::getResourceModel('sales/order_creditmemo_collection')
                        ->setOrderFilter($this)
                        ->load();
            } else {
                return false;
            }
        }
        return $this->_creditmemos;
    }

    /**
     * Retrieve order tracking numbers collection
     *
     * @return unknown
     */
    public function getTracksCollection() {
        if (empty($this->_tracks)) {
            $this->_tracks = Mage::getResourceModel('sales/order_shipment_track_collection')
                    ->setOrderFilter($this);

            if ($this->getId()) {
                $this->_tracks->load();
            }
        }
        return $this->_tracks;
    }

    /**
     * Check order invoices availability
     *
     * @return bool
     */
    public function hasInvoices() {
        return $this->getInvoiceCollection()->count();
    }

    /**
     * Check order shipments availability
     *
     * @return bool
     */
    public function hasShipments() {
        return $this->getShipmentsCollection()->count();
    }

    /**
     * Check order creditmemos availability
     *
     * @return bool
     */
    public function hasCreditmemos() {
        return $this->getCreditmemosCollection()->count();
    }

    /**
     * Retrieve array of related objects
     *
     * Used for order saving
     *
     * @return array
     */
    public function getRelatedObjects() {
        return $this->_relatedObjects;
    }

    public function getCustomerName() {
        if ($this->getCustomerFirstname()) {
            $customerName = $this->getCustomerFirstname() . ' ' . $this->getCustomerLastname();
        } else {
            $customerName = Mage::helper('sales')->__('Guest');
        }
        return $customerName;
    }

    /**
     * Add New object to related array
     *
     * @param   Mage_Core_Model_Abstract $object
     * @return  Mage_Sales_Model_Order
     */
    public function addRelatedObject(Mage_Core_Model_Abstract $object) {
        $this->_relatedObjects[] = $object;
        return $this;
    }

    /**
     * Get formated order created date in store timezone
     *
     * @param   string $format date format type (short|medium|long|full)
     * @return  string
     */
    public function getCreatedAtFormated($format) {
        return Mage::helper('core')->formatDate($this->getCreatedAtStoreDate(), $format, true);
    }

    public function getEmailCustomerNote() {
        if ($this->getCustomerNoteNotify()) {
            return $this->getCustomerNote();
        }
        return '';
    }

    /**
     * Processing object before save data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave() {
        parent::_beforeSave();
        $this->_checkState();
        if (!$this->getId()) {
            $store = $this->getStore();
            $name = array($store->getWebsite()->getName(), $store->getGroup()->getName(), $store->getName());
            $this->setStoreName(implode("\n", $name));
        }

        if (!$this->getIncrementId()) {
            $incrementId = Mage::getSingleton('eav/config')
                    ->getEntityType('order')
                    ->fetchNewIncrementId($this->getStoreId());
            $this->setIncrementId($incrementId);
        }

        /**
         * Process items dependency for new order
         */
        if (!$this->getId()) {
            $itemsCount = 0;
            foreach ($this->getAllItems() as $item) {
                $parent = $item->getQuoteParentItemId();
                if ($parent && !$item->getParentItem()) {
                    $item->setParentItem($this->getItemByQuoteItemId($parent));
                } elseif (!$parent) {
                    $itemsCount++;
                }
            }
            // Set items count
            $this->setTotalItemCount($itemsCount);
        }
        if ($this->getCustomer()) {
            $this->setCustomerId($this->getCustomer()->getId());
        }

        if ($this->hasBillingAddressId() && $this->getBillingAddressId() === null) {
            $this->unsBillingAddressId();
        }

        if ($this->hasShippingAddressId() && $this->getShippingAddressId() === null) {
            $this->unsShippingAddressId();
        }

        $this->setData('protect_code', substr(md5(uniqid(mt_rand(), true) . ':' . microtime(true)), 5, 6));
        return $this;
    }

    /**
     * Check order state before saving
     */
    protected function _checkState() {
        if (!$this->getId()) {
            return $this;
        }

        $userNotification = $this->hasCustomerNoteNotify() ? $this->getCustomerNoteNotify() : null;

        if (!$this->isCanceled() && !$this->canUnhold() && !$this->canInvoice() && !$this->canShip()) {
            if (0 == $this->getBaseGrandTotal() || $this->canCreditmemo()) {
                if ($this->getState() !== self::STATE_COMPLETE) {
                    $this->_setState(self::STATE_COMPLETE, true, '', $userNotification);
                }
            }
            /**
             * Order can be closed just in case when we have refunded amount.
             * In case of "0" grand total order checking ForcedCanCreditmemo flag
             */ elseif (floatval($this->getTotalRefunded()) || (!$this->getTotalRefunded() && $this->hasForcedCanCreditmemo())) {
                if ($this->getState() !== self::STATE_CLOSED) {
                    $this->_setState(self::STATE_CLOSED, true, '', $userNotification);
                }
            }
        }

        if ($this->getState() == self::STATE_NEW && $this->getIsInProcess()) {
            $this->setState(self::STATE_PROCESSING, true, '', $userNotification);
        }
        return $this;
    }

    /**
     * Save order related objects
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _afterSave() {
        if (null !== $this->_addresses) {
            $this->_addresses->save();
            $billingAddress = $this->getBillingAddress();
            $attributesForSave = array();
            if ($billingAddress && $this->getBillingAddressId() != $billingAddress->getId()) {
                $this->setBillingAddressId($billingAddress->getId());
                $attributesForSave[] = 'billing_address_id';
            }

            $shippingAddress = $this->getShippingAddress();
            if ($shippingAddress && $this->getShippigAddressId() != $shippingAddress->getId()) {
                $this->setShippingAddressId($shippingAddress->getId());
                $attributesForSave[] = 'shipping_address_id';
            }

            if (!empty($attributesForSave)) {
                $this->_getResource()->saveAttribute($this, $attributesForSave);
            }
        }
        if (null !== $this->_items) {
            $this->_items->save();
        }
        if (null !== $this->_payments) {
            $this->_payments->save();
        }
        if (null !== $this->_statusHistory) {
            $this->_statusHistory->save();
        }
        foreach ($this->getRelatedObjects() as $object) {
            $object->save();
        }
        return parent::_afterSave();
    }

    public function getStoreGroupName() {
        $storeId = $this->getStoreId();
        if (is_null($storeId)) {
            return $this->getStoreName(1); // 0 - website name, 1 - store group name, 2 - store name
        }
        return $this->getStore()->getGroup()->getName();
    }

    /**
     * Resets all data in object
     * so after another load it will be complete new object
     *
     * @return Mage_Sales_Model_Order
     */
    public function reset() {
        $this->unsetData();
        $this->_actionFlag = array();
        $this->_addresses = null;
        $this->_items = null;
        $this->_payments = null;
        $this->_statusHistory = null;
        $this->_invoices = null;
        $this->_tracks = null;
        $this->_shipments = null;
        $this->_creditmemos = null;
        $this->_relatedObjects = array();
        $this->_orderCurrency = null;
        $this->_baseCurrency = null;

        return $this;
    }

    public function getIsNotVirtual() {
        return !$this->getIsVirtual();
    }

    public function getFullTaxInfo() {
        $rates = Mage::getModel('tax/sales_order_tax')->getCollection()->loadByOrder($this)->toArray();
        return Mage::getSingleton('tax/calculation')->reproduceProcess($rates['items']);
    }

    /**
     * Create new invoice with maximum qty for invoice for each item
     *
     * @return Mage_Sales_Model_Order_Invoice
     */
    public function prepareInvoice($qtys = array()) {
        $invoice = Mage::getModel('sales/service_order', $this)->prepareInvoice($qtys);
        return $invoice;
    }

    /**
     * Create new shipment with maximum qty for shipping for each item
     *
     * @return Mage_Sales_Model_Order_Shipment
     */
    public function prepareShipment($qtys = array()) {
        $shipment = Mage::getModel('sales/service_order', $this)->prepareShipment($qtys);
        return $shipment;
    }

    /**
     * Check whether order is canceled
     *
     * @return bool
     */
    public function isCanceled() {
        return ($this->getState() === self::STATE_CANCELED);
    }

    /**
     * Protect order delete from not admin scope
     * @return Mage_Sales_Model_Order
     */
    protected function _beforeDelete() {
        $this->_protectFromNonAdmin();
        return parent::_beforeDelete();
    }

}
