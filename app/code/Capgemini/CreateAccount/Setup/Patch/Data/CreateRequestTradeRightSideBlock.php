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

class CreateRequestTradeRightSideBlock implements DataPatchInterface
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
<style>#html-body [data-pb-style=MIE4Q2N]{justify-content:center;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=IH5EW35]{border-style:none}#html-body [data-pb-style=C52SWL0],#html-body [data-pb-style=PHY799A]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=IH5EW35]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
   <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MIE4Q2N">
      <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="IH5EW35"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM_2.png}}" alt="" title="" data-element="desktop_image" data-pb-style="C52SWL0"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM_2.png}}" alt="" title="" data-element="mobile_image" data-pb-style="PHY799A"></figure>
   </div>
</div>
EOD;
            /** @var BlockInterface $block1 */
            $block1 = $this->blockFactory->create();
            $block1->setContent($blockContent1)->setIdentifier('vc_create_company_request_right_block')
                ->setTitle('Visual Comfort Company Request Right Block')
                ->setIsActive(1)
                ->setData('stores', [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block1);



        } catch (\Exception $e) {
            $this->logger->error("Apply Data Patch vc_create_company_request_right_block error: " . $e->getMessage());
        }
        return $this;
    }
}
