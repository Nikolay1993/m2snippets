<?php

declare(strict_types=1);

namespace Andrew\AttributeForCategory\Setup;

use Magento\Catalog\Api\Data\CategoryAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Create new EAV attribute
 *
 * @package Andrew\AttributeForCategory\Setup
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
    )
    {
        /** For version compatibility */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /** @var  $attributeCode */
        $attributeCode = 'external_id';

        /**
         * For attribute property look at \Magento\Eav\Model\Entity\Setup\PropertyMapperInterface
         *
         * 1. Eav Property Mapper - \Magento\Eav\Model\Entity\Setup\PropertyMapper - main
         * 2. Catalog Property Mapper - \Magento\Catalog\Model\ResourceModel\Setup\PropertyMapper
         */

        /**
         * Add attribute for category, look in mysql table eav_attribute
         * entity_type_code catalog_category
         * entity_model Magento\Catalog\Model\ResourceModel\Category
         */
        $eavSetup->addAttribute(
            CategoryAttributeInterface::ENTITY_TYPE_CODE,
            $attributeCode,
            [
                /** 'lable' - Name for attribute 'label' => 'AttributeName' */
                'label'        => 'External ID',
                /** 'user_defined' - System attributes cannot be deleted, by default every added attribute is
                system but if you set the user_defined field to true (1) then the attribute
                will be user-defined and we will be able to remove it. */
                'user_defined' => 1,
                /** 'unique' - Specifies the ability to add an attribute with the same name (lable) if 'unique' => true (1),
                then a new attribute with the same name cannot be created */
                'unique'       => 1,
            ]
        );

        /**
         * Add attribute_code external_id in default attribute set and default attribute group
         *
         * If there is a store in your properties then use  $eavSetup->addAttributeGroup for add custom attribute in group
         * If this default attribute then use $eavSetup->addAttributeToGroup for add attribute in group
         */
        $setId = $eavSetup->getDefaultAttributeSetId(CategoryAttributeInterface::ENTITY_TYPE_CODE);
        $groupId = $eavSetup->getDefaultAttributeGroupId(CategoryAttributeInterface::ENTITY_TYPE_CODE, $setId);
        $eavSetup->addAttributeToSet(CategoryAttributeInterface::ENTITY_TYPE_CODE, $setId, $groupId, $attributeCode);

    }
}
