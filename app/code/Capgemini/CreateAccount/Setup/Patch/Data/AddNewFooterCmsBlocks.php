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

class AddNewFooterCmsBlocks implements DataPatchInterface
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
<div data-content-type="html" data-appearance="default" data-element="main">           &lt;div class="footer-links"&gt;
    &lt;div class="link-column"&gt;
        &lt;p class="footer-header"&gt;CUSTOMER SERVICE&lt;/p&gt;
        &lt;a href="{{store url=""}}contact" class="footer-link"&gt;Contact Us&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}company/account/create/" class="footer-link"&gt;Request a Trade Account&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}faq/" class="footer-link"&gt;FAQs&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Imap Policy&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Emrp Policy&lt;/a&gt;&lt;br /&gt;
    &lt;/div&gt;
    &lt;div class="link-column"&gt;
        &lt;p class="footer-header"&gt;WAY TO SHOP&lt;/p&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Deal Locator&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/catalogs/" class="footer-link"&gt;Catalogs &amp; Lookbooks&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/events/" class="footer-link"&gt;Markets &amp; Events&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Representation&lt;/a&gt;&lt;br /&gt;
    &lt;/div&gt;
    &lt;div class="link-column"&gt;
        &lt;p class="footer-header"&gt;RESOURCES&lt;/p&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Warranty&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Retuns &amp; Replacements&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Terms of Sale&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Title 24&lt;/a&gt;&lt;br /&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;div class="footer-links" style="margin-top: 22px;"&gt;
    &lt;div class="link-column"&gt;
        &lt;p class="footer-header"&gt;OUR COMPANY&lt;/p&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;About Us&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Our Brands&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Careers&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Contact &amp; Hospitality&lt;/a&gt;&lt;br /&gt;
    &lt;/div&gt;
    &lt;div class="link-column"&gt;
     &amp;nbsp;&amp;nbsp;
    &lt;/div&gt;
    &lt;div class="link-column"&gt;
        &lt;p class="footer-header"&gt;LEGAL&lt;/p&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Terms &amp; Conditions&lt;/a&gt;&lt;br /&gt;
        &lt;a href="{{store url=""}}/" class="footer-link"&gt;Privacy Policy&lt;/a&gt;&lt;br /&gt;
    &lt;/div&gt;
&lt;/div&gt;</div>
EOD;
            /** @var BlockInterface $block1 */
            $block1 = $this->blockFactory->create();
            $block1->setTitle('Visual Comfort Footer Links')
                ->setIdentifier('vc_footer_links')
                ->setContent($blockContent1)
                ->setIsActive(1)
                ->setData('stores', [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block1);

            $blockContent2 = <<<EOD
<div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="social-icons"&gt;
    &lt;a href="https://www.instagram.com/circalighting/"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-instagram.svg'}}"&gt;&lt;/a&gt;
    &lt;a href="https://www.pinterest.com/circalighting/"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-pinterest.svg'}}"&gt;&lt;/a&gt;
    &lt;a href="https://www.facebook.com/circalighting/"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-facebook.svg'}}"&gt;&lt;/a&gt;
    &lt;a href="https://twitter.com/circalighting"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-twitter.svg'}}"&gt;&lt;/a&gt;
&lt;/div&gt;</div>
EOD;
            /** @var BlockInterface $block2 */
            $block2 = $this->blockFactory->create();
            $block2->setTitle('Visual Comfort Footer Social Icons')
                ->setIdentifier('vc_footer_social')
                ->setContent($blockContent2)
                ->setIsActive(1)
                ->setData('stores',  [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block2);


        } catch (\Exception $e) {
            $this->logger->error("Apply Data Patch AddNewFooterCmsBlocks error: " . $e->getMessage());
        }
        return $this;
    }
}
