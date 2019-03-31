<?php

declare(strict_types=1);

namespace Andrew\AttributeForCustomer\Setup;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Create new EAV attribute
 *
 * @package Andrew\AttributeForCustomer\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetupFactory;

    private $eavConfig;

    /**
     * InstallData constructor.
     *
     * @param EavSetup $eavSetup
     */
    public function __construct
    (
        EavSetupFactory $eavSetup,
        EavConfig $eavConfig
    )
    {
        $this->eavSetupFactory = $eavSetup;
        $this->eavConfig = $eavConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function install
    (
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        /** For version compatibility */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /** @var  $attributeCode */
        $attributeCode = 'interests';

        /**
         * Add attribute for customer, look in mysql table eav_attribute
         * entity_type_code customer
         * entity_model Magento\Customer\Model\ResourceModel\Customer
         */
        $eavSetup->addAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $attributeCode,
            [
                /** 'lable' - Name for attribute */
                'label'        => 'Interests',
                /** 'is_required' -  If required => true(1), then the attribute must have any value when you create a customer. */
                'required'     => 0,
                /** 'user_defined' - System attributes cannot be deleted, by default every added attribute is
                system but if you set the user_defined field to true (1) then the attribute
                will be user-defined and we will be able to remove it. */
                'user_defined' => 1,
                /** input filtering by value striptags */
                'input_filter' => 'striptags',
                /** record under input */
                'note'         =>'Separate multiple interests with a comma',
                /** system attribute or not */
                'system'       => 0,
                /** position attribute in admin panel */
                'position'     =>100,
            ]
        );

        /** Add attribute to group */
        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            $attributeCode
            );

        /** Form use area */
        $attribute = $this->eavConfig->getAttribute(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $attributeCode);
        $attribute->setData('used_in_forms',
            [
                /** Form visibility in */
                'adminhtml_customer',
                'customer_account_create',
                'customer_account_edit'
            ]
        );
        /** Create validate for input with max length 3 symbol and max length symbol 30 */
        $attribute->setData('validate_rules',
            [
                'min_text_length' => 3,
                'max_text_length' => 30,
            ]
        );
        $attribute->getResource()->save($attribute);
    }
}
