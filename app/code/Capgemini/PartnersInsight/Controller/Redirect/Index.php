<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Controller\Redirect;

use Capgemini\PartnersInsight\Model\Api\Client;
use Capgemini\PartnersInsight\Model\Config;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\UrlInterface;

/**
 * Redirect controller
 */
class Index implements HttpGetActionInterface
{
    /**
     * @var ResultFactory
     */
    protected $resultFactory;
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var Client
     */
    protected $api;
    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param ResultFactory $resultFactory
     * @param Config $config
     * @param UrlInterface $url
     * @param Client $api
     */
    public function __construct(
        ResultFactory $resultFactory,
        Config $config,
        UrlInterface $url,
        Client $api
    ){
        $this->resultFactory = $resultFactory;
        $this->url = $url;
        $this->api = $api;
        $this->config = $config;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->config->isAllowed()) {
            $defaultNoRouteUrl = $this->config->getConfigValue('web/default/no_route');
            $redirect->setUrl($this->url->getUrl($defaultNoRouteUrl));
        } else {
            $resourceUrl = $this->getResourceUrl();
            if ($resourceUrl) {
                $redirect->setUrl($resourceUrl);
            } else {
                $redirect->setUrl($this->url->getUrl('partners-insight/error'));
            }
        }

        return $redirect;
    }

    protected function getResourceUrl()
    {
        try {
            $result = $this->api->send();
        } catch (\Exception $e) {
            $result = null;
        }
        if ($result && isset($result['data']['resourceUrl'])) {
            return $result['data']['resourceUrl'];
        }
        return null;
    }

}
