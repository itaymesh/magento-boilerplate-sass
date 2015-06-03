<?php
/**
 * Created by PhpStorm.
 * User: itaymesh
 * Date: 5/2/15
 * Time: 10:43 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/*$this->removeAttribute('catalog_product', 'hover_image');*/

$this->addAttribute(
	Mage_Catalog_Model_Product::ENTITY,
	'hover_image',
	array (
		'group'             => 'Images',
		'type'              => 'varchar',
		'frontend'          => 'catalog/product_attribute_frontend_image',
		'label'             => 'Hover Image',
		'input'             => 'media_image',
		'class'             => '',
		'source'            => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => false,
		'default'           => '',
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'visible_on_front'  => false,
		'unique'            => false,
		'used_in_product_listing' => true
	)
);

$installer->endSetup();