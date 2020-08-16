<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


use Vendor\Utils\Enum\StaffEnum;
use Vendor\Utils\Methods\Staff\StaffEdit;

class StaffEditComponent extends StaffComponent
{
    public function executeComponent()
    {
        if ($this->request->isPost() && check_bitrix_sessid()) {
            try {
                $this->processPost();
            } catch (Exception $e) {
                ShowError($e->getMessage());
            }
        } else {
            try {
                $this->arResult = StaffEdit::editForm(['ID' => $this->arParams['ID']]);
                $this->setMeta();
                $this->setChainItems();
                $this->includeComponentTemplate();
            } catch (Exception $e) {
                ShowError($e->getMessage());
            }
        }
    }

    protected function processPost()
    {
        $data = [
            'ID' => $this->arParams['ID'],
            'FIELDS' => [
                'FIO' => $this->request->getPost('FIO'),
                'HIRING_DATE' => $this->request->getPost('HIRING_DATE'),
                'POSITION_ID' => $this->request->getPost('POSITION_ID')
            ]
        ];
        $result = StaffEdit::edit($data);
        if ($result['status']) {
            LocalRedirect($result['redirect_url']);
        }
    }

    public function onPrepareComponentParams($params)
    {
        $params = parent::onPrepareComponentParams($params);
        $title = $params['ID'] ? StaffEnum::EDIT_NAME : StaffEnum::NULL_NAME;
        $params['CHAIN_ITEMS'] = array_merge($params['CHAIN_ITEMS'], ['NAME' => $title, 'LINK' => '']);
        $params['TITLE'] = $title;
        return $params;
    }
}
