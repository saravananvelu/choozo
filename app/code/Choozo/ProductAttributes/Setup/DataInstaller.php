<?php
namespace Choozo\ProductAttributes\Setup;

class DataInstaller
{
    /**
     * Eav config
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * Inital method of class
     *
     * @param \Magento\Eav\Model\Config $eavConfig
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
    }

    public function convertAttributeToSwatches($attributeCode) {
        $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
        $attributeData['option'] = [];
        $attributeData['frontend_input'] = 'select';
        $attributeData['swatch_input_type'] = 'text';
        $attributeData['update_product_preview_image'] = 1;
        $attributeData['use_product_image_for_swatch'] = 0;
        $attributeData['optiontext'] = '';
        $attributeData['defaulttext'] = '';
        $attributeData['swatchtext'] = '';
        $attribute->addData($attributeData);
        $attribute->save();
    }
}
