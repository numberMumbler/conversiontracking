<?php
/*
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
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license	http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class conversiontracking extends Module {
	public function __construct() {
		$this->name = 'conversiontracking';
		$this->tab = 'checkout';
		$this->version = '1.0.0';
		$this->ps_versions_compliancy = array('min' => '1.5');
		$this->author = 'David Janke';

		parent::__construct();

		$this->displayName = $this->l('Conversion Tracking');
		$this->description = $this->l('Track customer convertions from ad services');
	}

	public function install() {
		if (Shop::isFeatureActive()) {
			Shop::setContext(Shop::CONTEXT_ALL);
		}
		return parent::install() &&
			$this->registerHook('displayOrderConfirmation');
	}

	public function uninstall() {
		return parent::uninstall();
	}

	// Admin screen
	public function getContent() {
		// TODO: implement me
		return '<center>Under Construction</center>';
	}

	// Hooks
	public function hookDisplayOrderConfirmation($params) {
		$fbTrackers = array(array('id' => '6018368151287'),
							   array('id' => '6015469448538'));
		$adwordsTrackers = array(array('id' => '988298912', 'label' => 'QOXQCODEhVcQoP2g1wM'));

		$orderTotal = $params['total_to_pay'];

		$this->smarty->assign(array(
			'fbTrackers' => $fbTrackers,
			'adwordsTrackers' => $adwordsTrackers,
			'orderTotal' => $orderTotal
		));

		return $this->display(__FILE__, 'displayOrderConfirmation.tpl');
	}
}
