<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Auth\Session;
use Kcs\Pacjson\Model\Pacjson;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_adminSession;

    /**
     * @var \Kcs\Pacjson\Model\PacjsonFactory
     */
    protected $pacjsonFactory;

    /**
     * @param Action\Context                      $context
     * @param \Magento\Backend\Model\Auth\Session              $adminSession
     * @param \Kcs\Pacjson\Model\PacjsonFactory          $pacjsonFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Kcs\Pacjson\Model\PacjsonFactory $pacjsonFactory
    ) {
        parent::__construct($context);
        $this->_adminSession = $adminSession;
        $this->pacjsonFactory = $pacjsonFactory;
    }

    /**
     * Save pacjson record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $postObj = $this->getRequest()->getPostValue();
        $pid = $postObj["pid"];
        $attribute_combination = $postObj["attribute_combination"];
        $option_combination = $postObj["option_combination"];
        $option_combination_json = $postObj["option_combination_json"];
        $status = $postObj["status"];
        $date = date("Y-m-d");
        //$username = $this->_pacjson->getFirstname();
        
        $userDetail = ["pid" => $pid, "attribute_combination" => $attribute_combination, "option_combination" => $option_combination, "option_combination_json" => $option_combination_json, "created_at" => $date];
        $data = array_merge($postObj, $userDetail);

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->pacjsonFactory->create();
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('pacjson/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
