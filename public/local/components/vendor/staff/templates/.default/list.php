<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\UI\Extension;

Extension::load("ui.buttons");
?>
<?php require_once '__add_button.php'; ?>
<?php require_once '__export_excel_button.php'; ?>
<div style="clear: both;"></div>
<?$APPLICATION->IncludeComponent(
    'vendor:staff.list',
    '',
    []
);?>
