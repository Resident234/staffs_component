<?php

namespace Vendor\Utils\Helpers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\SystemException;
use NotaTools\Exception\Iblock\IblockException;
use Vendor\Utils\Orm\Tables\EventListTable;
use Vendor\Utils\Orm\Tables\Iblock\ActionTable;
use Vendor\Utils\Orm\Tables\Iblock\GroupsTable;
use Vendor\Utils\Orm\Tables\Iblock\HelpTable;
use Vendor\Utils\Orm\Tables\Iblock\NewsTable;
use Vendor\Utils\Orm\Tables\Iblock\PraiseTable;
use Vendor\Utils\Orm\Tables\Iblock\PublicationTable;
use Vendor\Utils\Orm\Tables\Iblock\ScopeTable;
use Vendor\Utils\Orm\Tables\Iblock\ServiceTable;
use Vendor\Utils\Orm\Entity\Action\ActionBase;
use Vendor\Utils\Orm\Entity\Action\Action;
use Vendor\Utils\Orm\Entity\Group;
use Vendor\Utils\Orm\Entity\Help;
use Vendor\Utils\Orm\Entity\News;
use Vendor\Utils\Orm\Entity\Publication;
use Vendor\Utils\Orm\Entity\Scope;
use Vendor\Utils\Orm\Entity\Service;
use Vendor\Utils\Orm\Tables\Iblock\EO_Action;
use Vendor\Utils\Methods\Events\Comment;

/**
 * Class OrmHelper
 * @package Vendor\Utils\Helpers
 */
class OrmHelper
{

    public static function getEntityById($entityId): ?object
    {
        $className = '';
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, PublicationTable::class)) ? Publication::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, GroupsTable::class)) ? Group::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, ScopeTable::class)) ? Scope::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, ActionTable::class)) ? ActionBase::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, NewsTable::class)) ? News::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, ServiceTable::class)) ? Service::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, PraiseTable::class)) ? Praise::class : '';
        }
        if (!$className) {
            $className = (static::isElementOfClassEntityExist($entityId, EventListTable::class)) ? Comment::class : '';
        }
        if ($className) {
            return $className::getObject($entityId);
        }
        return null;
    }

    /**
     *
     * @param string $entityId
     * @param $classTable
     * @return bool
     * @throws ArgumentException
     * @throws IblockException
     */
    public static function isElementOfClassEntityExist(string $entityId, $classTable): bool
    {
        if (!(int)$entityId) {
            throw new ArgumentException('entity id must be specified');
        }
        try {
            return 1 === $classTable::query()->setSelect(['ID'])->setFilter(['=ID' => (int)$entityId])->setLimit(1)->exec()->getSelectedRowsCount();
        } catch (ArgumentException|SystemException $e) {
            throw new IblockException($e->getMessage());
        }
    }
}