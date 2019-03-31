<?php

declare(strict_types=1);

namespace Andrew\Attribute\Setup;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Create new EAV attribute
 *
 * @package Andrew\Attribute\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     *
     * @param EavSetup $eavSetup
     */
    public function __construct(EavSetupFactory $eavSetup)
    {
        $this->eavSetupFactory = $eavSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /** For version compatibility */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $attributeCode = 'legasy_sku';

        /**
         * For attribute property look at \Magento\Eav\Model\Entity\Setup\PropertyMapperInterface
         *
         * 1. Eav Property Mapper - \Magento\Eav\Model\Entity\Setup\PropertyMapper - main
         * 2. Catalog Property Mapper - \Magento\Catalog\Model\ResourceModel\Setup\PropertyMapper
         * 3. Configurable Product Property Mapper -
         *      \Magento\ConfigurableProduct\Model\ResourceModel\Setup\PropertyMapper - this not used in magento 2
         */

        /**
         * 'used_in_product_listing'
         * 'used_for_promo_rules'   -     adds an attribute to a flat table
         * 'used_for_sort_by'
         */

        /**
         * 'default' - With this, you can set a default value.
         *  When you create a product which has this attribute, it will give a default value for that product.
         * 'apply_to - Determine the applicability for attribute products
         * 'comparable' - This option allows you to use this product attribute to make comparisons between different products.
         * 'filterable' - Define filtering for this attribute
         * 'visible_on_front' - Determine the ability of the attribute to be visible on the frontend
         */


        /** add attribute to group if not add store in property global for attribute */
        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;
        /** get id default Attribute Set */
        $setId      = $eavSetup->getDefaultAttributeSetId($entityType);
        /** get default group id */
        $groupId    = $eavSetup->getDefaultAttributeGroupId($entityType, $setId);
        /** `attribute_group_name` - it's row from table `eav_attribute_group` */
        $groupName = $eavSetup->getAttributeGroup($entityType, $setId, $groupId, 'attribute_group_name');

        /**
         * Add attribute for product, look in mysql table eav_attribute
         * entity_type_code catalog_product
         * entity_model Magento\Catalog\Model\ResourceModel\Product
         */
        $eavSetup->addAttribute(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeCode,
            [
                /** 'lable' - Name for attribute 'label' => 'AttributeName' */
                'label'                      => 'Legasy sku',
                /** 'is_required' -  If required => true(1), then the attribute must have any value when you create a product. */
                'is_required'                => 0,
                /** 'user_defined' - System attributes cannot be deleted, by default every added attribute is
                system but if you set the user_defined field to true (1) then the attribute
                will be user-defined and we will be able to remove it. */
                'user_defined'               => 1,
                /** 'unique' - Specifies the ability to add an attribute with the same name (lable) if 'unique' => true (1),
                then a new attribute with the same name cannot be created */
                'unique'                     => 1,
                /** 'visible' - This field determines whether the attribute will be visible in the admin panel or not.
                If the attribute values ​​are set to 'visible' => true (1), then the attribute will be displayed;
                if false (0) it will not be displayed. */
                'visible'                    => 1,
                /** 'searchable' - We define the ability to search on frontende by the value of this attribute.
                If the attribute values ​​are true, then the search capability will be enabled for this attribute. */
                'searchable'                 => 1,
                /** 'visible_in_advanced_search' - If this attribute can be used on the Advanced Search page. */
                'visible_in_advanced_search' => 1,
                /** Add to attribute Group */
                'group'                      => $groupName,
                /** 'sort_order' - We define attribute sorting */
                'sort_order'                 => 30,
            ]
        );
    }
}
