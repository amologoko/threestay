<?php
/**
 * @name         :  Apptha Airhotels
 * @version      :  1.7
 * @since        :  Magento 1.6
 * @author       :  Apptha - http://www.apptha.com
 * @copyright    :  Copyright (C) 2013 Powered by Apptha
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  June 20 2011
 * @Modified By  :  Bala G
 * @Modified Date:  September 2 2013
 * 
 * */ 
?>
<?php
class Apptha_Airhotels_Helper_Data extends Mage_Core_Helper_Abstract
{

	/**
	 * Retrieve property form post url
	 *
	 * @return string
	 */
	public function getformurl()
	{
		return $this->_getUrl('booking/property/form');
	}
        /**
	 * Retrieve calendar
	 *
	 * @return string
	 */
        public function getblockurl(){
            
          return $this->_getUrl('booking/property/blockdate');
        }

	public function getyourlisturl()
	{
		return $this->_getUrl('booking/property/show') ;

	}
	public function getinboxurl()
	{
		return $this->_getUrl('booking/property/inbox') ;

	}
	/**
	 * List property regarding to current,upcoming and previous trip using yourtrip post url
	 *
	 * @return string
	 */

	public function getyourtripurl()
	{
		return $this->_getUrl('booking/property/yourtrip') ;

	}
	/**
	 * Retrieve property edit post url
	 *
	 * @return string
	 */
	public function getediturl()
	{
		return $this->_getUrl('booking/property/edit') ;

	}
	/**
	 * Upload property images gallery uisng image post url
	 *
	 * @return string
	 */

	public function getimageurl()
	{
		return $this->_getUrl('booking/property/image');

	}
	/**
	 * After updating property album it redirects to property list page
	 *
	 * @return string
	 */

	public function getshowlisturl()
	{
		return $this->_getUrl('booking/property/show') ;

	}
    /**
	 * After updating property album it redirects to property list page
	 *
	 * @return string
	 */

	public function getcalendarurl()
	{
		return $this->_getUrl('booking/property/calender') ;

	}
	
	
	/**
     * Retrieve attribute id for accomodates
     *
     * @return (int)accomodatesId
	 */
	public  function getaccomodates()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'accomodates');


	}
	/**
     * Retrieve attribute id for secret_key
     *
     * @return (int)secretkeyId
	 */
	public  function getsecretkey()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'secret_key');


	}
	/**
     * Retrieve attribute id for amenity
     *
     * @return (int)amenityId
	 */
	public  function getamenity()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'amenity');


	}
	/**
     * Retrieve attribute id for description
     *
     * @return (int)descriptionId
	 */
	public  function getdescription()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'description');

	}
	/**
     * Retrieve attribute id for short_description
     *
     * @return (int)short_descriptionId
	 */
	public  function getshortdescription()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'short_description');

	}
	/**
     * Retrieve attribute id for hostemail
     *
     * @return (int)hostemailId
	 */
	public  function gethostemail()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'hostemail');
			
	}
	/**
     * Retrieve attribute id for propertytype
     *
     * @return (int)propertytypeId
	 */
	public  function getpropertytype()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'propertytype');
			

	}
	/**
     * Retrieve attribute id for name
     *
     * @return (int)nameId
	 */
	public  function getname()
	{
	 return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'name');

	}
	/**
     * Retrieve attribute id for price
     *
     * @return (int)priceId
	 */
	public  function getprice()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'price');

	}
	/**
     * Retrieve attribute id for propertyadd
     *
     * @return (int)propertyaddId
	 */
	public  function getaddress()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'propertyadd');
			
	}
	/**
     * Retrieve attribute id for totalrooms
     *
     * @return (int)totalroomsId
	 */
	public  function getroom()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'totalrooms');
			
	}
	
	public  function getprivacy()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'privacy');
			
	}
	
	public  function getbaseimage()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'image');
			
	}
	
	public  function getsmallimage()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'small_image');
			
	}
	
	public  function getthumbimage()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'thumbnail');
			
	}
	
	public  function getmediagallery()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'media_gallery');
			
	}
	
	public  function getstate()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'state');
			
	}
	
	public  function getcity()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'city');
			
	}
	
	public  function getcountry()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'country');
			
	}
	
	public  function getcancelpolicy()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'cancelpolicy');
			
	}
	
	public  function getpets()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'pets');
			
	}
	
	public  function getmap()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'maplocation');
			
	}
   
	public  function getbedtype()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'bedtype');
			
	}
    
	public  function getaccomodatesType()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','accomodates');
			
        }
   
	public  function getrooms()
	{
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','totalrooms');
			
	}


     public function getprofilepage()
     {
         return $this->_getUrl('booking/index/profile');
     }
        

	public function getWRAdapter()
	{

		return Mage::getSingleton('core/resource')->getConnection('core_write');

	}

	public function getRDAdapter()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');

	}
        
 
        
       public  function phpSlashes($string,$type='add'){
        if ($type == 'add')
        {
            if (get_magic_quotes_gpc())
            {
                return $string;
            }
            else
            {
                if (function_exists('addslashes'))
                {
                    return addslashes($string);
                }
                else
                {
                    return mysql_real_escape_string($string);
                }
            }
        }
        else if ($type == 'strip')
        {
            return stripslashes($string);
        }
        else
        {
            die('error in PHP_slashes (mixed,add | strip)');
        }
    }
    

     public function getpopularpage()
     {
         return $this->_getUrl('booking/property/popular');
     }
     

     public function getwishlistpage()
     {
         return $this->_getUrl('booking/property/wishlist');
     }
     
     #license key 
     
     public function AirhotelsKey() 
	{
                $code = $this->genenrateAirhotelsdomain(); 
		$domainKey = substr($code, 0, 25) . "CONTUS";
                $apikey = Mage::getStoreConfig('airhotels/custom_group/airhotels_license');
		if($domainKey != $apikey )
                {
                    $keyerror = base64_decode('PGgyIHN0eWxlPSJmbG9hdDpsZWZ0OyBjb2xvcjpyZWQ7Zm9udC1zaXplOiAxOHB4OyBmb250LXNpemU6IDE4cHg7IHRleHQtYWxpZ246Y2VudGVyO21hcmdpbjogMjUwcHggMCAwIDU1MHB4OyI+PGEgc3R5bGU9ImZsb2F0OmxlZnQ7IGNvbG9yOnJlZDsiIGhyZWY9Imh0dHA6Ly93d3cuYXBwdGhhLmNvbS9zaG9wL2NoZWNrb3V0L2NhcnQvYWRkL3Byb2R1Y3QvNzkvcXR5LzEvIiB0YXJnZXQ9Il9ibGFuayI+SW52YWxpZCBMaWNlbnNlIEtleSAtIEJ1eSBub3c8L2E+PC9oMj4='); 
                    die($keyerror);
                }    

	}

	public function domainKey($tkey) {

		$message = "EM-AIRHOTELMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

		for ($i = 0; $i < strlen($tkey); $i++) {
			$key_array[] = $tkey[$i];
		}
		$enc_message = "";
		$kPos = 0;
		$chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		for ($i = 0; $i < strlen($chars_str); $i++) {
			$chars_array[] = $chars_str[$i];
		}
		for ($i = 0; $i < strlen($message); $i++) {
			$char = substr($message, $i, 1);

			$offset = $this->getOffset($key_array[$kPos], $char);
			$enc_message .= $chars_array[$offset];
			$kPos++;
			if ($kPos >= count($key_array)) {
				$kPos = 0;
			}
		}

		return $enc_message;
	}
	
	public function getOffset($start, $end) {

		$chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		for ($i = 0; $i < strlen($chars_str); $i++) {
			$chars_array[] = $chars_str[$i];
		}

		for ($i = count($chars_array) - 1; $i >= 0; $i--) {
			$lookupObj[ord($chars_array[$i])] = $i;
		}

		$sNum = $lookupObj[ord($start)];
		$eNum = $lookupObj[ord($end)];

		$offset = $eNum - $sNum;

		if ($offset < 0) {
			$offset = count($chars_array) + ($offset);
		}

		return $offset;
	}

	public function genenrateAirhotelsdomain() {
		 
		$strDomainName = $_SERVER['SERVER_NAME'];
		preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
                preg_match("/^(https:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
		preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
		if (isset($matches['domain'])) {
			$customerurl = $matches['domain'];
		} else {
			$customerurl = "";
		}
		$customerurl = str_replace("www.", "", $customerurl);
		$customerurl = str_replace(".", "D", $customerurl);
		$customerurl = strtoupper($customerurl);
		if (isset($matches['domain'])) {
			$response = $this->domainKey($customerurl);
		} else {
			$response = "";
		}
		return $response;
	}

    function getUnreadMsgCount(){
        $model = Mage::getModel('airhotels/airhotels');

        return $model->getUnreadInboxCount();
    }

}
