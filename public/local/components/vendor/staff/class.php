<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use NotaTools\Exception\Iblock\ElementNotFoundException;
use Vendor\Utils\Enum\StaffEnum;
use Vendor\Utils\Orm\Entity\Staff;
use Vendor\Utils\Orm\Tables\StaffTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Class StaffComponent
 */
class StaffComponent extends CBitrixComponent
{
    public $page = 'list';

    /**
     * @inheritDoc
     */
    public function onPrepareComponentParams($params)
    {
        $defaultChainItems[] = [
            'NAME' => 'Главная страница',
            'LINK' => '/',
        ];
        $defaultChainItems[] = [
            'NAME' => StaffEnum::MULTIPLE_NAME,
            'LINK' => StaffEnum::LIST_URL,
        ];
        $params['CHAIN_ITEMS'] = $defaultChainItems;
        if (empty($params['FILE_404'])) {
            $params['FILE_404'] = PATH_TO_404;
        }
        $params['TITLE'] = StaffEnum::MULTIPLE_NAME;
        return parent::onPrepareComponentParams($params);
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        $this->initPage();
        $this->setMeta();
        $this->setChainItems();
        $this->includeComponentTemplate($this->page);
        return $this->arResult;
    }


    protected function initPage(): void
    {
        $arUrlTemplates = $this->arParams['SEF_URL_TEMPLATES'];

        $arVariableAliases = [];
        $arComponentVariables = [];
        $arVariables = [];

        $componentPage = CComponentEngine::parseComponentPath(
            $this->arParams['SEF_FOLDER'],
            $arUrlTemplates,
            $arVariables
        );

        CComponentEngine::initComponentVariables($componentPage,
            $arComponentVariables,
            $arVariableAliases,
            $arVariables
        );
        $this->page = $componentPage ?: $this->page;
        $this->arResult['VARIABLES'] = $arVariables;
    }

    protected function setMeta(): void
    {
        global $APPLICATION;
        $APPLICATION->SetTitle($this->arParams['TITLE']);
        $APPLICATION->SetPageProperty('title', $this->arParams['TITLE']);
    }

    protected function setChainItems(): void
    {
        global $APPLICATION;
        foreach ($this->arParams['CHAIN_ITEMS'] as $item) {
            $APPLICATION->AddChainItem($item['NAME'], $item['LINK']);
        }
    }

}