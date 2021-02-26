<?php
namespace Choozo\CustomerImport\Plugin\Customer;

/**
* Class BeforeAuthenticate
*
* @package Choozo\CustomerImport\Plugin\Customer
*/
class BeforeAuthenticate
{
    /**
    * Customer Repository.
    *
    * @var \Magento\Customer\Api\CustomerRepositoryInterface
    */
    protected $_customerRepository;

    /**
     * Customer Model.
     *
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customerModel;

    /**
    * Encryptor.
    *
    * @var \Magento\Framework\Encryption\Encryptor
    */
    protected $_encryptor;

    /**
    * Customer registry.
    *
    * @var \Magento\Customer\Model\CustomerRegistry
    */
    protected $_customerRegistry;

    /**
    * AccountManagementPlugin constructor.
    *
    * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    * @param \Magento\Framework\Encryption\Encryptor           $encryptor
    * @param \Magento\Customer\Model\CustomerRegistry          $customerRegistry
    * @param \Magento\Customer\Model\Customer                  $customerModel
    */
    public function __construct(
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
    \Magento\Framework\Encryption\Encryptor $encryptor,
    \Magento\Customer\Model\Customer $customerModel,
    \Magento\Customer\Model\CustomerRegistry $customerRegistry
    ) {
        $this->_customerRepository = $customerRepository;
        $this->_encryptor          = $encryptor;
        $this->_customerRegistry   = $customerRegistry;
        $this->_customerModel      = $customerModel;
    }

    /**
    * Authenticate Customer by Hash from Old site and update info in DB.
    *
    * @param \Magento\Customer\Model\AccountManagement $subject
    * @param array                                     $args
    *
    * @return array
    */
    public function beforeAuthenticate(\Magento\Customer\Model\AccountManagement $subject, ...$args)
    {
        $email = $args[0];
        $password = $args[1];

        if (!empty($email) && !empty($password)) {
            try {
                $customerRepo = $this->_customerRepository->get($email);
                $customerId = $customerRepo->getId();
                $customerModel = $this->_customerModel->load($customerId);
                $magePasswordHash = $customerModel->getPasswordHash();

                /* Logic for validation hash from old website here */
                //$password = "Sara1234$";
                $len = strlen($password);
                $half = ceil($len / 2);
                $oldPasswordHash = sha1(strrev(substr($password, $half, $len)) . strrev(md5(substr($password, 0, $half))));

                if($oldPasswordHash == $magePasswordHash){
                    $passwordHash = $this->_encryptor->getHash($args[1], true);
                    $customerSecure = $this->_customerRegistry->retrieveSecureData($customerId);
                    $customerSecure->setRpToken(null);
                    $customerSecure->setRpTokenCreatedAt(null);
                    $customerSecure->setPasswordHash($passwordHash);
                    $this->_customerRepository->save($customerRepo, $passwordHash);
                    $this->_customerRegistry->remove($customerId);
                }
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return $args;
            }
        }

        return $args;
    }
}
