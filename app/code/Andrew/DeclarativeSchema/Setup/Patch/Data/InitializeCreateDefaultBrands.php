<?php

namespace Andrew\DeclarativeSchema\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;


class InitializeCreateDefaultBrands implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * CreateDefaultBrands constructor.
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * example of implementation:
     *
     * [
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch1::class,
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch2::class
     * ]
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [\Magento\Store\Setup\Patch\Schema\InitializeStoresAndWebsites::class];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [\Andrew\DeclarativeSchema\Setup\Patch\Data\CreateDefaultBrands::class];
    }

    /**
     * Run code inside patch
     * If code fails, patch must be reverted, in case when we are speaking about schema - than under revert
     * means run PatchInterface::revert()
     *
     * If we speak about data, under revert means: $transaction->rollback()
     *
     * @return $this
     */
    public function apply()
    {
        $brands = [
            ['brand_name' => 'Nike', 'description' => 'NIKE COOL brand'],
            ['brand_name' => 'TOTAL', 'description' => 'TOTALL COOL brand'],
            ['brand_name' => 'Adidas', 'description' => 'Addidas COOL brand'],
        ];

        $records = array_map(
            function ($brand) {
                return array_merge($brand, ['is_enable'=>1, 'website_id'=>1]);
            },
            $brands
        );

        $this->moduleDataSetup->getConnection()->insertMultiple('andrew_example', $records);
        return $this;
    }
}
