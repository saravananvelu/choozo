<?php
/**
 *  Importing the customer helper class
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
namespace Choozo\CustomerImport\Helper;

use Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Helper\Context;

/**
 *  Importing the customer helper class
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
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Load the customer based on his id
     *
     * @var Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepo;

    /**
     * Date time
     *
     * @var \\Magento\Framework\Stdlib\DateTime\TimezoneInterface Data time class
     */
    private $timezone;

    /**
     * Logger class
     *
     * @var \Psr\Log\LoggerInterface logger
     */
    protected $logger;

    /**
     * Initialization
     *
     * @param \Magento\Framework\App\Helper\Context                                                       $companyHelper              get company object
     * @param \Magento\Customer\Api\CustomerRepositoryInterface                                     $customerRepo               load by customer id
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface                                  $timezone                   Date Object
     * @param \Psr\Log\LoggerInterface $logger Logger class
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepo,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->customerRepo = $customerRepo;
        $this->timezone = $timezone;
        $this->logger = $logger;
        parent::__construct($context);
    }
}
