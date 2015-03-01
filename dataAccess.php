<?php

function fetchScriptData($groupId, $shopId) {
	$scriptData = array();

	$sql = 'SELECT
		`'._DB_PREFIX_.'conversiontracking`.`id_conversiontracking`,
		`'._DB_PREFIX_.'conversiontracking_service`.`template_service_id`,
		`'._DB_PREFIX_.'conversiontracking_field`.`template_field_id`,
		`'._DB_PREFIX_.'conversiontracking_value`.`field_value`
	FROM `'._DB_PREFIX_.'conversiontracking`
	INNER JOIN `'._DB_PREFIX_.'conversiontracking_service`
		ON `'._DB_PREFIX_.'conversiontracking`.`id_conversiontracking_service` = `'._DB_PREFIX_.'conversiontracking_service`.`id_conversiontracking_service`
	INNER JOIN `'._DB_PREFIX_.'conversiontracking_value`
		ON `'._DB_PREFIX_.'conversiontracking`.`id_conversiontracking` = `'._DB_PREFIX_.'conversiontracking_value`.`id_conversiontracking`
	INNER JOIN `'._DB_PREFIX_.'conversiontracking_field`
		ON `'._DB_PREFIX_.'conversiontracking_value`.`id_conversiontracking_field` = `'._DB_PREFIX_.'conversiontracking_field`.`id_conversiontracking_field`
	WHERE `'._DB_PREFIX_.'conversiontracking`.`id_group` in (0, '. (int)$groupId .')
		AND `'._DB_PREFIX_.'conversiontracking`.`id_shop` in (0, '. (int)$shopId .')
	ORDER BY 
		`'._DB_PREFIX_.'conversiontracking`.`id_shop` DESC,
		`'._DB_PREFIX_.'conversiontracking`.`id_group` DESC,
		`'._DB_PREFIX_.'conversiontracking_field`.`id_conversiontracking_field` ASC;';

	return Db::getInstance()->ExecuteS($sql);
}

function fetchScriptTemplateData($groupId, $shopId) {
	$scriptTemplateData = array();
	$scriptData = fetchScriptData($groupId, $shopId);

	if($scriptData) {
		// flatten into key-value pairs grouped by service
		foreach($scriptData as $row) {
			$serviceId = $row['template_service_id'];
			$recordId = $row['id_conversiontracking'];
			$templateKey = $row['template_field_id'];
			$templateValue = $row['field_value'];

			if(!array_key_exists($serviceId, $scriptTemplateData)) {
				$scriptTemplateData[$serviceId] = array();
			}
			if(!array_key_exists($recordId, $scriptTemplateData[$serviceId])) {
				$scriptTemplateData[$serviceId][$recordId] = array();
			}

			// add key, value
			$scriptTemplateData[$serviceId][$recordId][$templateKey] = $templateValue;
		}
	}

	return $scriptTemplateData;
}

function fetchActiveServices($groupId, $shopId) {
	$serviceIds = array();

	$sql = 'SELECT DISTINCT `'._DB_PREFIX_.'conversiontracking_service`.`template_service_id`
	FROM `'._DB_PREFIX_.'conversiontracking`
	INNER JOIN `'._DB_PREFIX_.'conversiontracking_service`
		ON `'._DB_PREFIX_.'conversiontracking`.`id_conversiontracking_service` = `'._DB_PREFIX_.'conversiontracking_service`.`id_conversiontracking_service`
	WHERE `'._DB_PREFIX_.'conversiontracking`.`id_group` in (0, '. (int)$groupId .')
	AND `'._DB_PREFIX_.'conversiontracking`.`id_shop` in (0, '. (int)$shopId .');';

	if($results = Db::getInstance()->ExecuteS($sql)) {
		foreach($results as $row) {
			if($serviceId = $row['template_service_id']) {
				$serviceIds[] = $serviceId;
			}
		}
	}
	return $serviceIds;
}
