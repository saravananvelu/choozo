<?php
/**
 *  Patch script class for creating brand attribute
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
 *  Patch script class for creating brand attribute
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
class AddBrand implements DataPatchInterface
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
        $attributeSet = 'Default';
        $attributeGroupName = 'Choozo Attributes';
        $eavSetup->addAttributeGroup(
            \Magento\Catalog\Model\Product::ENTITY,
            $attributeSet,
            $attributeGroupName,
            100
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand', [
                'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Brand',
                    'input' => 'select',
                    'class' => '',
                    'source' =>'',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
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
                    'group' => 'Choozo Attributes',
                    'option' => [
                        'values' => [
                            "",
                            "Dermosoie",
                            "Centrum"]
                        ],
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
