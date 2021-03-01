<?php
/**
 *  Patch script class for creating product_size attribute
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


use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
/**
 *  Patch script class for creating product_size attribute
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
class AddProductSize implements DataPatchInterface
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
     * Inital method of class
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup Module setup
     * @param \Magento\Eav\Setup\EavSetupFactory                $eavSetupFactory Eav factory class
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
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
            'product_size', [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Product Size',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Choozo Attributes'
            ]
        );
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
