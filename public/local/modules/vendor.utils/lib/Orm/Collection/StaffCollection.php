<?php

namespace Vendor\Utils\Orm\Collection;

use Vendor\Utils\Orm\Entity\Staff;
use Vendor\Utils\Orm\Tables\EO_Staff_Collection;

/**
 * Class StaffCollection
 * @package Vendor\Utils\Orm\Collection
 */
class StaffCollection extends EO_Staff_Collection
{
    protected const CLASS_NAME = Staff::class;

    /**
     * @return array
     */
    public function toArrayFormatted(): array
    {
        $resultList = [];
        /** @var Staff $staff */
        foreach ($this->getAll() as $item) {
            $className = static::CLASS_NAME;
            $staff = new $className($item);
            $resultList[] = $staff->toArrayFormattedList();
        }
        return $resultList;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $resultList = [];
        /** @var Staff $staff */
        foreach ($this->getAll() as $item) {
            $className = static::CLASS_NAME;
            $staff = new $className($item);
            $resultList[] = $staff->toArray();
        }
        return $resultList;
    }

    /** @noinspection PhpDocRedundantThrowsInspection */
    /**
     * @param EO_Staff_Collection|null $collection
     *
     * @return static
     * @throws ElementNotFoundException
     * @throws ElementNotFoundException
     * @throws IblockNotFoundException
     * @throws IblockPropertyNotFoundException
     * @throws PropertyEnumNotFoundException
     * @throws UserNotFoundException
     */
    public static function createFromElementCollection(?EO_Staff_Collection $collection): StaffCollection
    {
        $currentCollection = new self();
        if (($collection !== null) && $collection->count() > 0) {
            foreach ($collection->getAll() as $item) {
                $className = static::CLASS_NAME;
                $action = new $className($item);
                $currentCollection->add($action);
            }
        }
        return $currentCollection;
    }


    /**
     * @return array
     */
    public function getOutput(): array
    {
        if ($this->count() === 0) {
            return [];
        }
        $res = [];
        /** @var Staff $item */
        foreach ($this as $item) {
            $res[] = $item->toArray(Staff::FORMATTED_TYPE['FORMATTED_LIST']);
        }
        return $res;
    }


}