<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


use Bitrix\Main\UI\Filter\Options;
use Vendor\Utils\Helpers\LoggerHelper;
use Vendor\Utils\Methods\Staff\StaffList;

class StaffExcelComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $filter = [];
        global $APPLICATION;
        try {
            $this->arResult = StaffList::list(['filter' => $filter]);
        } catch (Exception $e) {
            LoggerHelper::logEvents($e->getMessage(), __METHOD__);
        }
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: filename=list_staff.xls');
        $this->IncludeComponentTemplate();
        $r = $APPLICATION->EndBufferContentMan();
        echo $r;
        include($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
        die();
    }

}
