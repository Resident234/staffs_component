<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


use Bitrix\Main\UI\Filter\Options;
use Vendor\Utils\Enum\StaffEnum;
use Vendor\Utils\Methods\Staff\Staff;
use Vendor\Utils\Methods\Staff\StaffList;

class StaffListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $filter = [];
        if (
            $this->request->isPost() &&
            $this->request->get('apply_filter') === 'Y'
        )
        {
            $filterOptions = new Options(StaffEnum::FILTER_NAME);
            $filter = $filterOptions->getFilter();
        }

        try {
            $this->arResult = StaffList::list(['filter' => $filter]);
            $this->arResult['FILTER'] = StaffList::listFilter($filter);
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

}
