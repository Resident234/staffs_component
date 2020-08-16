<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\SystemException;
use Vendor\Utils\Methods\Staff\StaffDetail;

class StaffDetailComponent extends StaffComponent
{
    public function executeComponent()
    {
        try {
            $this->arResult = StaffDetail::get(['ID' => $this->arParams['ID']]);
            $this->arParams['TITLE'] = $title = $this->arResult['FIO'];
            $this->arParams['CHAIN_ITEMS'] = array_merge($this->arParams['CHAIN_ITEMS'], ['NAME' => $title, 'LINK' => '']);
            $this->setMeta();
            $this->setChainItems();
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

}

