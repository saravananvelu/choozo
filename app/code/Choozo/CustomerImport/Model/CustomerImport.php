<?php
/**
 *  Importing the customer model class
 *
 * PHP version 7.4
 *
 * @category  Magento
 * @package   Choozo\CustomerImport
 * @author    Ramki <ramki.r@brtechnologies.net>
 * @copyright 2020 BR Technologies (I) Pvt Ltd
 * @license   https://www.choozo.com/ Unilever
 * @link      http://www.choo.com
 */
namespace Choozo\CustomerImport\Model;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\App\ObjectManager;

/**
 *  Importing the customer model class
 *
 * PHP version 7.4
 *
 * @category  Magento
 * @package   Choozo\CustomerImport
 * @author    Ramki <ramki.r@brtechnologies.net>
 * @copyright 2020 BR Technologies (I) Pvt Ltd
 * @license   https://www.choozo.com/ Unilever
 * @link      http://www.choo.com
 */
use Magento\Store\Model\StoreManagerInterface;

class CustomerImport
{

    const CUSTOMER_TABLE = 'clientes';

    /**
     * Resource model class
     * @var ResourceConnection Resource model
     */
    private $resourceConnection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CustomerInterfaceFactory
     */
    protected $customerFactory;

    /**
     * @var AddressInterfaceFactory
     */
    protected $dataAddressFactory;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Inital method of class
     * @param ResourceConnection $resourceConnection Resource model class
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerInterfaceFactory $customerFactory
     * @param AddressInterfaceFactory $dataAddressFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param EncryptorInterface $encryptor
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $data
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        StoreManagerInterface $storeManager,
        CustomerInterfaceFactory $customerFactory,
        AddressInterfaceFactory $dataAddressFactory,
        AddressRepositoryInterface $addressRepository,
        EncryptorInterface $encryptor,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->dataAddressFactory = $dataAddressFactory;
        $this->addressRepository = $addressRepository;
        $this->encryptor = $encryptor;
        $this->customerRepository = $customerRepository;
    }

    /**
     * fetchRow Sql Query
     *
     * @return string[]
     */
    public function ImportCustomer()
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/customer_import.log');
        $logFiles = new \Zend\Log\Logger();
        $logFiles->addWriter($writer);
        $logFiles->info("----start log for --------- customer-------import request------" . date("Y-m-d H:i:s") . "----------");

        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName(self::CUSTOMER_TABLE);
        $query = $connection->select()
            ->from($tableName, ['codigo', 'email', 'password', 'nome', 'pais', 'sexo', 'morada1', 'morada2', 'codigopostal1',
                'localidade', 'telefone_prefixo', 'telefone', 'telemovel_prefixo', 'telemovel','pais_estado']);
        $logFiles->info("----SQL Query for fetching-----" . $connection->select()->__toString() . "----------");

        $choozoCustomers = $connection->fetchAll($query);

        $logFiles->info("----SQL Query for fetching-----" . $connection->select()->__toString() . "----------");

        $logFiles->info("----SQL Query for fetching-----" . $connection->select()->__toString() . "----------");

        $store = $this->storeManager->getStore();
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        //1 male 2 female 3 not specified
        foreach ($choozoCustomers as $choozoCustomer) {
            $logFiles->info("====================--------------=====================");
            $logFiles->info("====================--------------=====================");
            $logFiles->info("----Choozo Customer id-----" . $choozoCustomer['codigo'] . "----------");
            $logFiles->info("----Choozo Customer Email-----" . $choozoCustomer['email'] . "----------");
            /**Check the customer email already exists */
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customer = $objectManager->create(\Magento\Customer\Model\Customer::class);
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($choozoCustomer['email']); // load customer by email to check if customer is available or not
            if (!$customer->getId()) {
                if ($choozoCustomer['email'] != '' && $choozoCustomer['nome'] != '' && $choozoCustomer['password'] != '') {
                    $mobile = $choozoCustomer['telemovel_prefixo'] . '-' . $choozoCustomer['telemovel'];
                    $telephone = $choozoCustomer['telefone_prefixo'] . '-' . $choozoCustomer['telefone'];
                    $customer->setWebsiteId($websiteId)
                        ->setStore($store)
                        ->setFirstname($choozoCustomer['nome'])
                        ->setLastname($choozoCustomer['nome'])
                        ->setChoozoCustomerid($choozoCustomer['codigo'])
                        ->setMobileNo($mobile)
                        ->setTelephone($telephone)
                        ->setPassword($choozoCustomer['password'])
                        ->setEmail($choozoCustomer['email']);
                        $logFiles->info("----password -".$customer->getPassword()."---------");

                    try {
                        $newCustomer = $customer->save();
                        $logFiles->info("----customer saved successfully----------");
                        $logFiles->info("----customer address save started----------");
                        $this->saveCustomerAddress($newCustomer,$choozoCustomer);


                    } catch (\Exception $e) {
                        return __('Error while saving customer.');
                    }
                } else {
                    $logFiles->info("----Some required fields are missing----------");

                }

            } else {
                $logFiles->info("----Choozo Customer Email-----" . $choozoCustomer['email'] . "----------already exists in magento");

            }

            $logFiles->info("====================--------------=====================");
            $logFiles->info("====================--------------=====================");
        }
        $logFiles->info("----End log for --------- customer-------import request------" . date("Y-m-d H:i:s") . "----------");
    }
    /**
     * Save customer address
     * @param $customerAddressObj customer address object
     * @param $customer Customer object
     *
     * @return array $customerAddress Customer address object
     */
    public function saveCustomerAddress($customer, $customerAddressObj)
    {
        /* save address of customer */
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/customer_import.log');
        $logFiles = new \Zend\Log\Logger();
        $logFiles->addWriter($writer);

        if ($customerAddressObj['morada1'] != '' && $customerAddressObj['pais'] != '' && $customerAddressObj['pais'] != '0') {
            $address = $this->dataAddressFactory->create();
            $address->setFirstname($customerAddressObj['nome']);
            $address->setLastname($customerAddressObj['nome']);
            $telePhone = $customerAddressObj['telemovel_prefixo'] . '-' . $customerAddressObj['telemovel'];
            $address->setTelephone($telePhone);

            $street[] = $customerAddressObj['morada1']; //Street 1
            $street[] = $customerAddressObj['morada2']; //Street 2
            $address->setStreet($street);

            $address->setCity($customerAddressObj['localidade']);
            $address->setCountryId($customerAddressObj['pais']);
            $address->setRegionId($this->getRegionId($customerAddressObj['pais_estado']));
            $address->setPostcode($customerAddressObj['codigopostal1']);
            $address->setIsDefaultShipping(1);
            $address->setIsDefaultBilling(1);
            $address->setCustomerId($customer->getId());
            try {
                $this->addressRepository->save($address);

            } catch (\Exception $e) {
              $logFiles->info("----catch exception ---------".$e->getMessage()."-------import request------" . date("Y-m-d H:i:s") . "----------");
            }
        }
    }

    /**
     * Get Region Id's
     * @param $regionId region name
     * 
     * @return int region id's
     */
    public function getRegionId($regionId)
    {
        $stateName = [
            '1' => '859', //Abu Dhabi
            '2' => '863', //Dubai
            '3' => '862', //Sharja
            '4' => '865', //Umm Al Quwain
            '5' => '861', //Fujairah
            '6' => '860', //Ajman
            '7' => '864', //Ras Al Khaimah
        ];
        if (array_key_exists($regionId, $stateName)) {
            return $stateName[$regionId];
        } else {
            return;
        }
    }

}
