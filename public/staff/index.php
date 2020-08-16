<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?php
\Bitrix\Main\Diag\Debug::writeToFile('vendor:staff', '', 'test.log');
?>
<?$APPLICATION->IncludeComponent(
	'vendor:staff',
	'',
	[
		'SEF_URL_TEMPLATES' => [
			'list' => 'list/',
			'edit' => '#ID#/edit/',
			'detail' => '#ID#/',
			'excel' => 'excel/',
		],
		'SEF_FOLDER' => '/staff/',
	]
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
