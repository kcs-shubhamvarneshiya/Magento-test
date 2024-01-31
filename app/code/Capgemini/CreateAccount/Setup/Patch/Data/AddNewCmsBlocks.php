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

class AddNewCmsBlocks implements DataPatchInterface
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
            <strong>Please Note:</strong> To complete registration, you will need one of the following documents:
            <ul>
                <li>Current Business License</li>
                <li>W9, Federal ID Form, or EIN Form <a href="" download="W9_Blank_Form.pdf">Download Blank W9</a></li>
            </ul>
EOD;
            /** @var BlockInterface $block1 */
            $block1 = $this->blockFactory->create();
            $block1->setTitle('Registration Documents - Default')
                ->setIdentifier('registration-documents')
                ->setContent($blockContent1)
                ->setIsActive(1)
                ->setData('stores', [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block1);

            $blockContent2 = <<<EOD
            <strong>Please Note:</strong> To complete registration, you will need one of the following documents:
            <ul>
                <li>Business registration certificate</li>
                <li>VAT number</li>
                <li>Other proof of business such as an insurance certificate</li>
            </ul>
EOD;
            /** @var BlockInterface $block2 */
            $block2 = $this->blockFactory->create();
            $block2->setTitle('Registration Documents - UK')
                ->setIdentifier('registration-documents')
                ->setContent($blockContent2)
                ->setIsActive(1)
                ->setData('stores', 11);
            $this->blockRepository->save($block2);

            $blockContent3 = <<<EOD
            <span><p>For tax exempt purchases, download applicable Tax Exempt Certificates <a href="/tax-exempt/" target="_blank"><b>here</b></a> for each state you are a reseller.</p>
            <p>Please note: You will receive an email from Avalara Certcapture within 48 hours with a link to upload your tax exempt certificate.</p></span>
EOD;
            /** @var BlockInterface $block3 */
            $block3 = $this->blockFactory->create();
            $block3->setTitle('Registration Tax Exempt - Default')
                ->setIdentifier('registration-tax-exempt')
                ->setContent($blockContent3)
                ->setIsActive(1)
                ->setData('stores', [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block3);

            $blockContent4 = '';
            /** @var BlockInterface $block4 */
            $block4 = $this->blockFactory->create();
            $block4->setTitle('Registration Tax Exempt - UK')
                ->setIdentifier('registration-tax-exempt')
                ->setContent($blockContent4)
                ->setIsActive(1)
                ->setData('stores', 11);
            $this->blockRepository->save($block4);

        } catch (\Exception $e) {
            $this->logger->error("Apply Data Patch AddNewCmsBlocks error: " . $e->getMessage());
        }
        return $this;
    }
}
