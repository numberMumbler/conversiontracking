<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

/* Create tables */
$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'conversiontracking_service` (
    `id_conversiontracking_service` int(11) NOT NULL AUTO_INCREMENT,
    `service_name` varchar(24) NOT NULL,
    `service_description` varchar(128),
    PRIMARY KEY  (`id_conversiontracking_service`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'conversiontracking_field` (
	`id_conversiontracking_field` int(11) NOT NULL AUTO_INCREMENT,
	`id_conversiontracking_service` int(11) NOT NULL,
	`field_name` varchar(32) NOT NULL,
	`field_description` varchar(128),
	`validation_expression` varchar(64),
	`validation_message` varchar(64),
	PRIMARY KEY (`id_conversiontracking_field`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'conversiontracking` (
	`id_conversiontracking` int(11) NOT NULL AUTO_INCREMENT,
	`id_conversiontracking_service` int(11) NOT NULL,
	`id_group` int(11) NOT NULL DEFAULT 0,
	`id_shop` int(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id_conversiontracking`),
	INDEX idx_group_shop (`id_group`, `id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'conversiontracking_value` (
	`id_conversiontracking_value` int(11) NOT NULL AUTO_INCREMENT,
	`id_conversiontracking` int(11) NOT NULL,
	`id_conversiontracking_field` int(11) NOT NULL,
	`field_value` varchar(32),
	PRIMARY KEY (`id_conversiontracking_value`),
	INDEX idx_conversiontracking (`id_conversiontracking`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


/* Define service: Google AdWords */
$sql[] = "INSERT INTO `"._DB_PREFIX_."conversiontracking_service`
	(`service_name`, `service_description`)
VALUES
	('adwords', 'Google AdWords');";

$sql[] = "INSERT INTO `"._DB_PREFIX_."conversiontracking_field`
	(`id_conversiontracking_service`, `field_name`, `field_description`, `validation_expression`, `validation_message`)
SELECT
	`"._DB_PREFIX_."conversiontracking_service`.`id_conversiontracking_service`,
	`fields`.`field_name`,
	`fields`.`field_description`,
	`fields`.`validation_expression`,
	`fields`.`validation_message`
FROM `"._DB_PREFIX_."conversiontracking_service`
INNER JOIN (
	SELECT
		'ID' as `field_name`,
		'google_conversion_id' as `field_description`,
		'^[0-9]+$' as `validation_expression`,
		'ID can only contain numbers' as `validation_message`
	UNION ALL
	SELECT
		'Label' as `field_name`,
		'google_conversion_label' as `field_description`,
		'^[A-Za-z0-9]+$' as `validation_expression`,
		'Label can only contain letters and numbers' as `validation_message`
) `fields`
LEFT JOIN `"._DB_PREFIX_."conversiontracking_field`
	ON `"._DB_PREFIX_."conversiontracking_service`.`id_conversiontracking_service` = `"._DB_PREFIX_."conversiontracking_field`.`id_conversiontracking_service`
WHERE `"._DB_PREFIX_."conversiontracking_service`.`service_name` = 'adwords'
	AND `"._DB_PREFIX_."conversiontracking_field`.`id_conversiontracking_field` IS NULL;";

/* Define service: Facebook Conversion Pixel */
$sql[] = "INSERT INTO `"._DB_PREFIX_."conversiontracking_service`
	(`service_name`, `service_description`)
VALUES
	('fbPixel', 'Facebook Conversion Tracking Pixel');";

$sql[] = "INSERT INTO `"._DB_PREFIX_."conversiontracking_field`
	(`id_conversiontracking_service`, `field_name`, `field_description`, `validation_expression`, `validation_message`)
SELECT
	`"._DB_PREFIX_."conversiontracking_service`.`id_conversiontracking_service`,
	'ID' as `field_name`,
	'Checkout Pixel ID' as `field_description`,
	'^[0-9]+$' as `validation_expression`,
	'ID can only contain numbers' as `validation_message`
FROM `"._DB_PREFIX_."conversiontracking_service`
LEFT JOIN `"._DB_PREFIX_."conversiontracking_field`
	ON `"._DB_PREFIX_."conversiontracking_service`.`id_conversiontracking_service` = `"._DB_PREFIX_."conversiontracking_field`.`id_conversiontracking_service`
WHERE `"._DB_PREFIX_."conversiontracking_service`.`service_name` = 'fbPixel'
	AND `"._DB_PREFIX_."conversiontracking_field`.`id_conversiontracking_field` IS NULL;";


/* Execute */
foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;
