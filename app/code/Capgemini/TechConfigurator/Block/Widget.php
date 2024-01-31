<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Block;

use Capgemini\TechConfigurator\Model\ConfigRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

/**
 * Tech Configurator widget
 */
class Widget extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Capgemini_TechConfigurator::widget.phtml';

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    public function __construct(
        Context $context,
        ConfigRepository $configRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configRepository = $configRepository;
    }

    /**
     * Get configurator
     *
     * @return \Capgemini\TechConfigurator\Api\Data\ConfigInterface|null
     */
    public function getConfigurator()
    {
        if ($this->getConfigName()) {
            $config = $this->configRepository->getByName($this->getConfigName());
            if ($config->getId()){
                return $config;
            }
        }
        return null;
    }
}
