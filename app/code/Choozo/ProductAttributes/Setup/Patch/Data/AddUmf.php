<?php
/**
 *  Patch script class for creating Flavorssize attribute
 *
 * PHP version 7.4
 *
 * @category  Magento
 * @package   Choozo\ProductAttributes
 * @author    Ramki <ramki.r@brtechnologies.net>
 * @copyright 2020 BR Technologies (I) Pvt Ltd
 * @license   https://www.choozo.com/ Unilever
 * @link      http://www.choo.com
 */
namespace Choozo\ProductAttributes\Setup\Patch\Data;

use Choozo\ProductAttributes\Setup\DataInstallerFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 *  Patch script class for creating Flavors attribute
 *
 * PHP version 7.4
 *
 * @category  Magento
 * @package   Choozo\ProductAttributes
 * @author    Ramki <ramki.r@brtechnologies.net>
 * @copyright 2020 Embitel Technologies (I) Pvt Ltd
 * @license   https://www.choozo.com/ Unilever
 * @link      http://www.choo.com
 */
class AddUmf implements DataPatchInterface
{
    /**
     * Moddule Setup inteface
     *
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * Eav factory class
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Eav config
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * Data installer
     *
     * @var DataInstallerFactory
     */
    protected $dataInstallerFactory;

    /**
     * Inital method of class
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup Module setup
     * @param \Magento\Eav\Setup\EavSetupFactory                $eavSetupFactory Eav factory class
     * @param \Magento\Eav\Model\Config                         $eavConfig
     * @param DataInstallerFactory                              $dataInstallerFactory
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Model\Config $eavConfig,
        EavSetupFactory $eavSetupFactory,
        DataInstallerFactory $dataInstallerFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavConfig = $eavConfig;
        $this->dataInstallerFactory = $dataInstallerFactory;
    }
    /**
     * {@inheritdoc}
     *
     * @return Patch apply
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'umf', [
                'group' => 'Choozo Attributes',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'UMF',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => 0,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false
            ]
        );

        $this->dataInstallerFactory->create()->convertAttributeToSwatches('umf');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     *
     * @return Dependencies
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @return Aliases
     */
    public function getAliases()
    {
        return [];
    }
}
