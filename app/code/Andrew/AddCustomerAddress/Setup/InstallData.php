<?php

declare(strict_types=1);

namespace Andrew\AddCustomerAddress\Setup;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Create new EAV attribute
 *
 * @package Andrew\AddCustomerAddress\Setup
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
        $attributeCode = 'is_home_address';

        /**
         * Add attribute for customer, look in mysql table eav_attribute
         * entity_type_code customer_address
         * entity_model Magento\Customer\Model\ResourceModel\Address
         */
        $eavSetup->addAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            $attributeCode,
            [
                /** save type table eav_attribute in fields backend_type  */
                'type'         => 'int',
                /** type inpute for admin panel*/
                'input'        => 'boolean',
                /** 'lable' - Name for attribute */
                'label'        => 'Is home address?',
                /** 'is_required' -  If required => true(1), then the attribute must have any value when you create a customer address. */
                'required'     => 0,
                /** 'user_defined' - System attributes cannot be deleted, by default every added attribute is
                system but if you set the user_defined field to true (1) then the attribute
                will be user-defined and we will be able to remove it. */
                'user_defined' => 1,
                /** 'default' - With this, you can set a default value. */
                'default'      => 0,
                /** system attribute or not */
                'system'       => 0,
                /** position attribute in admin panel */
                'position'     => 50,
            ]
        );

        /** Add attribute to group */
        $eavSetup->addAttributeToSet(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            AddressMetadataInterface::ATTRIBUTE_SET_ID_ADDRESS,
            null,
            $attributeCode
        );

        /** Form use area */
        $attribute = $this->eavConfig->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, $attributeCode);
        $attribute->setData('used_in_forms',
            [
                /** Form visibility in */
                'adminhtml_customer_address',
                'customer_address_edit',
                'customer_register_address',
            ]
        );

        $attribute->getResource()->save($attribute);

        $setup->endSetup();
    }

}
