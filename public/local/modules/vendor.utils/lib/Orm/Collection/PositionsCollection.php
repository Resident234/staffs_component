<?php

namespace Vendor\Utils\Orm\Collection;

use Vendor\Utils\Orm\Entity\Positions;
use Vendor\Utils\Orm\Tables\EO_Positions_Collection;

/**
 * Class PositionsCollection
 * @package Vendor\Utils\Orm\Collection
 */
class PositionsCollection extends EO_Positions_Collection
{
    protected const CLASS_NAME = Positions::class;

    /**
     * @return array
     */
    public function toArrayFormatted(): array
    {
        $resultList = [];
        /** @var Positions $positions */
        foreach ($this->getAll() as $item) {
            $className = static::CLASS_NAME;
            $positions = new $className($item);
            $resultList[] = $positions->toArrayFormatted();
        }
        return $resultList;
    }

    /**
     * @return array
     */
    public function toArrayFormattedFilterList(): array
    {
        $resultList = [];
        $className = static::CLASS_NAME;
        /** @var Positions $positions */
        $resultList[] = $className::toArrayFormattedFilterListEmpty();
        foreach ($this->getAll() as $item) {
            $positions = new $className($item);
            $resultList[$positions->getId()] = $positions->toArrayFormattedFilterList();
        }
        return $resultList;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $resultList = [];
        /** @var Positions $positions */
        foreach ($this->getAll() as $item) {
            $className = static::CLASS_NAME;
            $positions = new $className($item);
            $resultList[] = $positions->toArray();
        }
        return $resultList;
    }
}