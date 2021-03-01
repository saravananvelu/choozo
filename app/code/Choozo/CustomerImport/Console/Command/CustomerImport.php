<?php

/**
 *  Importing customer import using console command
 *
 * PHP version 7.4
 *
 * @category  Magento
 * @package   Choozo\CustomerImport
 * @author    Ramki <ramki.r@brtechnologies.net>
 * @copyright 2021 BR Technologies (I) Pvt Ltd
 * @license   https://www.choozo.com/ Unilever
 * @link      http://www.choo.com
 */

namespace Choozo\CustomerImport\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\ObjectManager;

/**
 *  Importing customer import using console command
 *
 * PHP version 7.4
 *
 * @category  Magento
 * @package   Choozo\CustomerImport
 * @author    Ramki <ramki.r@brtechnologies.net>
 * @copyright 2021 BR Technologies (I) Pvt Ltd
 * @license   https://www.choozo.com/ Unilever
 * @link      http://www.choo.com
 */
class CustomerImport extends Command
{

    /**
     * Importing customer
     *
     * @var \Choozo\CustomerImport\Model\CustomerImport Importing Customer
     */
    protected $customerImport;

    
    /**
     * Initial method
     *
     * @param \Choozo\CustomerImport\Model\CustomerImport $customerImport Importing customer
     */
    public function __construct(
        \Choozo\CustomerImport\Model\CustomerImport $customerImport
    ) {
        $this->customerImport = $customerImport;
        parent::__construct();
    }

    /**
     * Execute Method
     *
     * @param InputInterface  $input  input
     * @param OutputInterface $output output
     * 
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln('<info>Started old customer inprogress</info>');
            $this->customerImport->ImportCustomer();            
        } catch (\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }

   
    /**
     * Configure command line options 
     * 
     * @return string
     */
    protected function configure()
    {
        $this->setName('choozo:customer:import');
        $this->setDescription('PImporting old customers into magento');
        parent::configure();
    }
}
