<?php
declare(strict_types=1);

namespace Capgemini\CreateAccount\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\Store;

class UpdateFooterLinkBlockCms implements DataPatchInterface
{
    const MODULE_CODE = 'Capgemini_CreateAccount';

    /** @var ModuleDataSetupInterface */
    protected $moduleDataSetup;

    /** @var ResourceConnection */
    protected $resource;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockFactory;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PopulateInitialQuestions constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ResourceConnection $resource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockFactory,
        ResourceConnection $resource,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
        $this->resource = $resource;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return $this
     */
    public function apply(): self
    {
        try {
            $blockContent1 = <<<EOD
<div class="footer-links" id="vc-footer-links">
    <div class="link-column" data-role="trigger">
        <div data-role="collapsible">
            <p class="footer-header" >CUSTOMER SERVICE</p>
        </div>
        <div data-role="content">
            <a href="{{store url=""}}contact" class="footer-link">Contact Us</a><br />
            <a href="{{store url=""}}company/account/create/" class="footer-link">Request a Trade Account</a><br />
            <a href="{{store url=""}}faq/" class="footer-link">FAQs</a><br />
            <a href="{{store url=""}}/" class="footer-link">Imap Policy</a><br />
            <a href="{{store url=""}}/" class="footer-link">Emrp Policy</a><br />
        </div>
    </div>
    <div class="link-column" data-role="trigger">
        <div data-role="collapsible">
            <p class="footer-header" >WAY TO SHOP</p>
        </div>
        <div data-role="content">
            <a href="{{store url=""}}/" class="footer-link">Deal Locator</a><br />
            <a href="{{store url=""}}/catalogs/" class="footer-link">Catalogs & Lookbooks</a><br />
            <a href="{{store url=""}}/events/" class="footer-link">Markets & Events</a><br />
            <a href="{{store url=""}}/" class="footer-link">Representation</a><br />
        </div>
    </div>
    <div class="link-column" data-role="trigger">
        <div data-role="collapsible">
            <p class="footer-header" >RESOURCES</p>
        </div>
        <div data-role="content">
            <a href="{{store url=""}}/" class="footer-link">Warranty</a><br />
            <a href="{{store url=""}}/" class="footer-link">Retuns & Replacements</a><br />
            <a href="{{store url=""}}/" class="footer-link">Terms of Sale</a><br />
            <a href="{{store url=""}}/" class="footer-link">Title 24</a><br />
        </div>
    </div>
</div>
<div class="footer-links second-footer-link">
    <div class="link-column" data-role="trigger">
        <div data-role="collapsible">
            <p class="footer-header">OUR COMPANY</p>
        </div>
        <div data-role="content">
            <a href="{{store url=""}}/" class="footer-link">About Us</a><br />
            <a href="{{store url=""}}/" class="footer-link">Our Brands</a><br />
            <a href="{{store url=""}}/" class="footer-link">Careers</a><br />
            <a href="{{store url=""}}/" class="footer-link">Contact & Hospitality</a><br />
        </div>
    </div>
    <div class="link-column hide-link-mobile">
     &nbsp;&nbsp;
    </div>
    <div class="link-column" data-role="trigger">
        <div data-role="collapsible">
            <p class="footer-header">LEGAL</p>
        </div>
        <div data-role="content">
            <a href="{{store url=""}}/" class="footer-link">Terms & Conditions</a><br />
            <a href="{{store url=""}}/" class="footer-link">Privacy Policy</a><br />
        </div>
    </div>
</div>
EOD;
            /** @var BlockInterface $block1 */
            $block1 = $this->blockFactory->create()->load('vc_footer_links','identifier');
                $block1->setContent($blockContent1)
                ->setIsActive(1)
                ->setData('stores', [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block1);



        } catch (\Exception $e) {
            $this->logger->error("Apply Data Patch UpdateFooterLinkBlockCms error: " . $e->getMessage());
        }
        return $this;
    }
}
