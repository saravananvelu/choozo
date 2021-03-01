<?php
namespace Choozo\ProductAttributes\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

/**
 * Data provider for main panel of product page
 *
 * @api
 * @since 101.0.0
 */
class DeliveryInfo extends AbstractModifier
{
    /**
     * @var   LocatorInterface
     * @since 101.0.0
     */
    protected $locator;

    /**
     * @var   ArrayManager
     * @since 101.0.0
     */
    protected $arrayManager;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    private $localeCurrency;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param LocatorInterface                  $locator
     * @param ArrayManager                      $arrayManager
     * @param AttributeRepositoryInterface|null $attributeRepository
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        AttributeRepositoryInterface $attributeRepository = null
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->attributeRepository = $attributeRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(AttributeRepositoryInterface::class);
    }

    /**
     * Customize product form fields.
     *
     * @param  array $meta
     * @return array
     * @since  101.0.0
     */
    public function modifyMeta(array $meta)
    {;
        $meta = $this->customizeDeliveryInfoField($meta);
        return $meta;
    }

    /**
     * Customize number fields for advanced price and weight fields.
     *
     * @param  array $data
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @since  101.0.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    protected function customizeDeliveryInfoField(array $meta)
    {
        $fromField = 'color';
        $toField = 'manufacturer';

        $fromFieldPath = $this->arrayManager->findPath($fromField, $meta, null, 'children');
        $toFieldPath = $this->arrayManager->findPath($toField, $meta, null, 'children');

        if ($fromFieldPath && $toFieldPath) {
            $fromContainerPath = $this->arrayManager->slicePath($fromFieldPath, 0, -2);
            $toContainerPath = $this->arrayManager->slicePath($toFieldPath, 0, -2);

            $meta = $this->arrayManager->merge(
                $fromFieldPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'label' => __('Color'),
                    'additionalClasses' => 'admin__field-date',
                ]
            );
            $meta = $this->arrayManager->merge(
                $toFieldPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'label' => __('Hours/Days'),
                    'scopeLabel' => null,
                    'additionalClasses' => 'admin__field-date',
                ]
            );
            $meta = $this->arrayManager->merge(
                $fromContainerPath . self::META_CONFIG_PATH,
                $meta,
                [
                    'label' => false,
                    'required' => false,
                    'additionalClasses' => 'admin__control-grouped-date',
                    'breakLine' => false,
                    'component' => 'Magento_Ui/js/form/components/group',
                ]
            );
            $meta = $this->arrayManager->set(
                $fromContainerPath . '/children/' . $toField,
                $meta,
                $this->arrayManager->get($toFieldPath, $meta)
            );

            $meta = $this->arrayManager->remove($toContainerPath, $meta);
        }

        return $meta;
    }
}
