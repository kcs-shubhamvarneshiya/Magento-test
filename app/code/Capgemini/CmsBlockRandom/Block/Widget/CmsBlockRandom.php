<?php
/**
 * Capgemini_CmsBlockRandom
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CmsBlockRandom\Block\Widget;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\Math\Random;

/**
 * Widget CMS block random
 */
class CmsBlockRandom extends Template implements BlockInterface
{
    /**
     * @var Random
     */
    protected $random;
    /**
     * @var Json
     */
    protected $json;

    /**
     * @param Template\Context $context
     * @param Random $random
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Random $random,
        Json $json,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->random = $random;
        $this->json = $json;
    }

    protected $_template = "Capgemini_CmsBlockRandom::widget/cms_block_random.phtml";

    /**
     * Generate unique ID
     *
     * @return string
     */
    public function generateId()
    {
        return $this->random->getUniqueHash('cms-block-random-');
    }

    /**
     * Get CMS block identifiers as array
     */
    public function getIdentifiers()
    {
        $identifiers = $this->getData('identity_ids');
        if ($identifiers) {
            $identifiers = explode(',', $identifiers);
            foreach ($identifiers as $key => $identifier) {
                $identifiers[$key] = trim($identifier);
            }
        } else {
            $identifiers = [];
        }
        return $identifiers;
    }

    /**
     * Get widget config in JSON format
     *
     * @return bool|string
     */
    public function getConfigJson()
    {
        $config = [
            'identityIds' => $this->getIdentifiers()
        ];
        return $this->json->serialize($config);
    }
}
