<?php

namespace Lyonscg\SalesPad\Console\Command;

use Lyonscg\SalesPad\Model\Api as BaseApi;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Lyonscg\SalesPad\Model\Api\CustomerAddr as CustomerAddrApi;
use Lyonscg\SalesPad\Model\Api\Session as SessionApi;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunApi extends Command
{
    const COMMAND_NAME = 'salespad:api:run';

    const API_UNIT = 'api_unit';

    const API_METHOD = 'method';

    const API_PARAMS = 'params';

    /**
     * @var State
     */
    private $state;

    /**
     * @var BaseApi
     */
    private $api;

    /**
     * @var CustomerApi
     */
    private $customerApi;

    /**
     * @var CustomerAddrApi
     */
    private $customerAddrApi;

    /**
     * @var SessionApi
     */
    private $sessionApi;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * RunApi constructor.
     * @param State $state
     * @param BaseApi $api
     * @param CustomerApi $customerApi
     * @param CustomerAddrApi $customerAddrApi
     */
    public function __construct(
        State $state,
        BaseApi $api,
        CustomerApi $customerApi,
        CustomerAddrApi $customerAddrApi,
        SessionApi $sessionApi,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct();
        $this->state = $state;
        $this->api = $api;
        $this->customerApi = $customerApi;
        $this->customerAddrApi = $customerAddrApi;
        $this->sessionApi = $sessionApi;
        $this->customerRepository = $customerRepository;
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDefinition([
                new InputArgument(
                    self::API_UNIT,
                    InputArgument::REQUIRED,
                    'Api Unit to call (e.g. customer)'
                ),
                new InputArgument(
                    self::API_METHOD,
                    InputArgument::REQUIRED,
                    'Api method to call (e.g. get, create)'
                ),
                new InputArgument(
                    self::API_PARAMS,
                    InputArgument::IS_ARRAY,
                    'Parameters passed to execute method',
                    []
                )
            ]);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $unit = $input->getArgument(self::API_UNIT);
        $method = $input->getArgument(self::API_METHOD);
        $params = $input->getArgument(self::API_PARAMS);

        switch ($unit) {
            case 'session':
                return $this->_executeSession($input, $output, $method, $params);
            case 'customer':
                return $this->_executeCustomer($input, $output, $method, $params);
            case 'customer_addr':
                return $this->_executeCustomerAddr($input, $output, $method, $params);
            default:
                $output->writeln('<error>Invalid api unit</error>');
                return;
        }

        $unitPart = $this->apiUnitMethodMap[$unit] ?? false;
        if (!$unitPart) {
            $output->writeln('<error>Invalid api unit</error>');
            return;
        }

        $apiClass = $unitPart[$method] ?? false;

        if (!$apiClass) {
            $output->writeln('<error>Invalid api method</error>');
        }
        $apiInstance = ObjectManager::getInstance()->create($apiClass, []);
        try {
            call_user_func_array([$apiInstance, 'execute'], $params);
        } catch (\Exception $e) {
            $output->writeln($e);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $method
     * @param $params
     */
    protected function _executeSession(InputInterface $input, OutputInterface $output, $method, $params)
    {
        try {
            switch ($method) {
                case 'get':
                    $sessionId = $this->api->getSessionId();
                    if ($sessionId !== false && $sessionId !== null) {
                        $output->writeln("<info>session::get -> $sessionId</info>");
                    } else {
                        $output->writeln("<error>session::get -> failure</error>");
                    }
                    break;
                case 'ping':
                    if ($this->sessionApi->ping()) {
                        $output->writeln('<info>session::ping -> success</info>');
                    } else {
                        $output->writeln('<error>session::ping -> failed</error>');
                    }
                break;
                case 'active_users':
                    $activeUsers = $this->sessionApi->activeUsers();
                    if ($activeUsers !== false) {
                        $output->writeln("<info>session::active_users -> $activeUsers</info>");
                    } else {
                        $output->writeln("<error>session::active_users -> failure</error>");
                    }
                    break;
                default:
                    $output->writeln('<error>Invalid method for session api unit</error>');
                    return;
            }
        } catch (\Exception $e) {
            $output->writeln("<error>Exception for session::$method: $e</error>");
            return;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $method
     * @param $params
     */
    protected function _executeCustomer(InputInterface $input, OutputInterface $output, $method, $params)
    {
        try {
            switch ($method) {
                case 'get':
                    if (count($params) !== 1) {
                        $output->writeln('<error>Need one parameter (customer number)</error>');
                        return;
                    }
                    $customerData = $this->customerApi->get($params[0]);
                    if ($customerData) {
                        $output->writeln(
                            '<info>customer::get -> ' . var_export($customerData, true) . '</info>'
                        );
                    } else {
                        $output->writeln("<error>customer::get -> failure</error>");
                    }
                    break;
                case 'update':
                    if (count($params) !== 1) {
                        $output->writeln('<error>Need one parameter (customer entity id)</error>');
                        return;
                    }
                    $customer = $this->customerRepository->getById($params[0]);
                    $customerNum = $this->customerApi->create($customer);
                    if ($customerNum) {
                        $output->writeln(
                            '<info>customer::update -> ' . $customerNum . '</info>'
                        );
                    } else {
                        $output->writeln("<error>customer::update -> failure</error>");
                    }
                    break;
                case 'create':
                    if (count($params) !== 1) {
                        $output->writeln('<error>Need one parameter (customer entity id)</error>');
                        return;
                    }
                    $customer = $this->customerRepository->getById($params[0]);
                    $customerNum = $this->customerApi->create($customer);
                    if ($customerNum) {
                        $output->writeln(
                            '<info>customer::create -> ' . $customerNum . '</info>'
                        );
                    } else {
                        $output->writeln("<error>customer::create -> failure</error>");
                    }
                    break;
                default:
                    $output->writeln('<error>Invalid method for customer api unit</error>');
                    return;
            }
        } catch (\Exception $e) {
            $output->writeln("<error>Exception for customer::$method: $e</error>");
            return;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $method
     * @param $params
     */
    protected function _executeCustomerAddr(InputInterface $input, OutputInterface $output, $method, $params)
    {
        try {
            switch ($method) {
                case 'create':
                    if (count($params) !== 4) {
                        $output->writeln(
                            '<error>Need one parameter (cust id, addr id, customer num, address code)</error>'
                        );
                        return;
                    }
                    list($customerId, $addressId, $customerNum, $addressCode) = $params;
                    $customer = $this->customerRepository->getById($customerId);
                    $address = $this->_getCustomerAddress($customer, $addressId);
                    if (!$address) {
                        $output->writeln("<error>address id $addressId not found</error>");
                        return;
                    }
                    $success = $this->customerAddrApi->create($customer, $address, $customerNum, $addressCode);
                    if ($success) {
                        $output->writeln(
                            '<info>customer_addr::create -> success</info>'
                        );
                    } else {
                        $output->writeln("<error>customer_addr::create -> failure</error>");
                    }
                    break;
                case 'update':
                    break;
                case 'get_codes':
                    if (count($params) !== 1) {
                        $output->writeln('<error>Need one parameter (customer number)</error>');
                        return;
                    }
                    $codes = $this->customerAddrApi->getAddressCodes($params[0]);
                    $output->writeln(
                        '<info>customer_addr::get_codes -> ' . var_export($codes, true) . '</info>'
                    );
                    break;
                default:
                    $output->writeln('<error>Invalid method for customer_addr api unit</error>');
                    return;
            }
        } catch (\Exception $e) {
            $output->writeln("<error>Exception for customer_addr::$method: $e</error>");
            return;
        }
    }

    /**
     * @param CustomerModel $customer
     * @param $addressId
     * @return bool|\Magento\Customer\Api\Data\AddressInterface|mixed
     */
    protected function _getCustomerAddress(CustomerModel $customer, $addressId)
    {
        return $this->customerApi->getCustomerAddressById($customer, $addressId);
    }
}
