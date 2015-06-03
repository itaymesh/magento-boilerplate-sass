<?php
$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);


//$installer->removeAttribute('catalog_category', 'category_ibanners_group');


$installer->addAttribute('catalog_category', 'category_menu_cms_block',  array(
    'type'                       => 'int',
    'label'                      => 'Category Menu CSM Block',
    'input'                      => 'select',
    'source'                     => 'catalog/category_attribute_source_page',
    'required'                   => false,
    'sort_order'                 => 22,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'                      => 'Display Settings',

));



$installer->addAttributeToGroup(
    $entityTypeId,
    3,
    5, //Display Settings
    'category_menu_cms_block',
    '22'                    //last Magento's attribute position in General tab is 10
);
$attributeId = $installer->getAttributeId($entityTypeId, 'category_menu_cms_block');

/*
$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_int')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, '1'
        FROM `{$installer->getTable('catalog_category_entity')}`;
");
*/

//this will set data of your custom attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();
$installer->endSetup();




?>