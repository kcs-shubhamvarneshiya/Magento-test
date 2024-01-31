<?php

namespace Unirgy\RapidFlow\Controller\Adminhtml\Profile;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Helper\Data as HelperData;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem\Directory\Write;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Unirgy\RapidFlow\Model\Profile;
use Unirgy\RapidFlow\Model\ResourceModel\Profile as ProfileResource;
use Unirgy\RapidFlow\Model\Io\File as IoFile;

class Upload extends AbstractProfile
{
    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * @var Write
     */
    protected $_directoryWrite;
    /**
     * @var IoFile
     */
    protected $ioFile;

    public function __construct(
        Context $context,
        Profile $profile,
        HelperData $catalogHelper,
        ProfileResource $resource,
        DirectoryList $directoryList,
        WriteFactory $writeFactory,
        IoFile $ioFile
    ) {
        $this->_directoryList = $directoryList;
        $this->_directoryWrite = $writeFactory;

        parent::__construct($context, $profile, $catalogHelper, $resource);
        $this->ioFile = $ioFile;
    }

    public function execute()
    {
        try {
            $uploader = new Uploader('file');
            $uploader->setAllowedExtensions(['csv', 'txt', '*']);
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            $id = $this->getRequest()->getParam('id');
            $profile = $this->_profile;
            $target = $profile->getFileBaseDir();
            if ($id) {
                /** @var \Unirgy\RapidFlow\Model\Profile $profile */
                $profile->load($id);
                if ($profile->getId() && $profile->getFileBaseDir()) {
                    $target = $profile->getFileBaseDir();
                }
            }
            $this->_directoryWrite->create($target)->create();
            $result = $uploader->save($target);

            $result['cookie'] = [
                'name' => session_name(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain()
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        $this->getResponse()->representJson(@json_encode($result));
    }
}
