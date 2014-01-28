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

class Magecomp_Adminhtml_SupportController extends  Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $data = $this->getRequest()->getPost();
        $support = Mage::getModel('magecomp/support');
        $support
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setSubject($data['subject'])
            ->setReason(isset($data['other_reason'])?$data['other_reason']:$data['reason'])
            ->setMessage($data['message']);
        try {
            $support->send();
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
            $this->_ajaxResponse($result);
            return;
        }
        $result['message'] = $this->__('Message sent');
        $this->_ajaxResponse($result);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/magecomp/support');
    }

    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()->setBody(Zend_Json::encode($result));
        return;
    }

}