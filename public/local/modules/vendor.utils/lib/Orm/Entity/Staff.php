<?php

namespace Vendor\Utils\Orm\Entity;

use Bitrix\Main\{ArgumentException, ObjectPropertyException, SystemException, Result};
use Vendor\Utils\Enum\StaffEnum;
use Vendor\Utils\Interfaces\Orm\Entity\StaffInterface;
use Vendor\Utils\Orm\Tables\EO_Staff;
use Vendor\Utils\Orm\Tables\StaffTable;
use Vendor\Utils\Orm\Tables\EO_Positions;
use Vendor\Utils\Orm\Tables\PositionsTable;

/**
 * Class Staff
 * @package Vendor\Utils\Orm\Entity
 */
class Staff implements StaffInterface
{
    /**
     * @var EO_Staff
     */
    protected $element;

    /**
     * Positions constructor.
     *
     * @param EO_Staff|$id int
     *
     * @throws ElementNotFoundException
     */
    public function __construct($id = 0)
    {
        $this->createObject($id);
    }


    /**
     * @return array
     */
    public static function getSelect(): array
    {
        return ['ID', 'FIO', 'HIRING_DATE', 'POSITION.NAME'];
    }

    /**
     * @return array
     */
    public static function getFilterFields(): array
    {
        return ['FIO', 'HIRING_DATE', 'POSITION.NAME', 'POSITION.DESCRIPTION'];
    }

    /**
     * @param $id
     *
     * @throws ElementNotFoundException
     */
    public function createObject($id): void
    {
        if (is_numeric($id) && $id > 0) {
            try {
                $this->element = StaffTable::getById($id)->fetchObject();
                if ($this->element === null) {
                    throw new ElementNotFoundException('не удалось установить элемент');
                }
            } catch (ArgumentException|SystemException $e) {
                throw new ElementNotFoundException($e->getMessage());
            }
        } else if ($id instanceof EO_Staff) {
            $this->element = $id;
        } else {
            $this->element = new EO_Staff();
        }
    }

    /**
     * @return EO_Staff|null
     */
    public function getElement(): ?EO_Staff
    {
        return $this->element;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getElement()->getId();
    }

    /**
     * @return string
     */
    public function getFio(): string
    {
        return (string)$this->getElement()->getFio();
    }

    /**
     * @return string
     */
    public function getHiringDate(): string
    {
        return (string)$this->getElement()->getHiringDate();
    }

    /**
     * @return int
     */
    public function getPositionId(): int
    {
        $position = $this->getElement()->getPosition();
        return ($position !== null) ? (int)$position->getId() : 0;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->getElement()->getPosition();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->getElement()->getFio();
    }

    /**
     * @param string $name
     *
     * @return StaffInterface
     */
    public function setFio(string $name): StaffInterface
    {
        $this->getElement()->setFio($name);
        return $this;
    }

    /**
     * @param string $description
     *
     * @return StaffInterface
     */
    public function setHiringDate(string $date): StaffInterface
    {
        $this->getElement()->setHiringDate($date);
        return $this;
    }

    /**
     * @param int $id
     *
     * @return Staff
     */
    public function setPositionId(int $id): StaffInterface
    {
        $this->getElement()->setPositionId($id);
        return $this;
    }

    /**
     * @param array $fields
     *
     * @return Staff
     */
    public function setData(array $fields): self
    {
        if (!empty($fields['FIO'])) {
            $this->setFio((string)$fields['FIO']);
        }
        if (!empty($fields['HIRING_DATE'])) {
            $this->setHiringDate($fields['HIRING_DATE']);
        }
        if ((int)$fields['POSITION_ID'] > 0) {
            $this->setPositionId((int)$fields['POSITION_ID']);
        }
        return $this;
    }

    /**
     * @return Result
     */
    public function save(): Result
    {
        $result = new Result();
        $element = $this->getElement();
        $res = $element->save();
        if ($res->isSuccess()) {
            $result->setData([
                'ID' => $element->getId(),
            ]);
        } else {
            $result->addErrors($res->getErrors());
        }
        return $result;
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ElementNotFoundException
     * @throws IblockException
     * @throws IblockNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function toArrayFormatted(): array
    {
        return [
            'ID'            => $this->getId(),
            'FIO'           => $this->getFio(),
            'HIRING_DATE'   => $this->getHiringDate(),
        ];
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ElementNotFoundException
     * @throws IblockException
     * @throws IblockNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function toArrayFormattedList(): array
    {
        return [
            'ID'            => $this->getId(),
            'FIO'           => $this->getFio(),
            'HIRING_DATE'   => $this->getHiringDate(),
            'POSITION'      => $this->getPosition()->getName()
        ];
    }

    /**
     * @return array
     */
    public function toArrayFormattedEdit(): array
    {
        $positionsOptionsFormatted = [];
        $positionsValueFormatted = [];
        $positionsCollection = PositionsTable::query()->setSelect(['ID', 'NAME'])->fetchCollection();
        /** @var EO_Positions $position */
        foreach ($positionsCollection as $position) {
            $positionsOptionsFormatted[] = $currentPosition = [
                'ID' => $position->getId(),
                'NAME' => $position->getName(),
                'VALUE' => $position->getId(),
            ];
            if ((int)$position->getId() === (int)$this->getPositionId()) {
                $positionsValueFormatted = $currentPosition;
            }
        }
        return [
            'ID'            => $this->getId(),
            'FIO'           => $this->getFio(),
            'HIRING_DATE'   => $this->getHiringDate(),
            'POSITION'   => [
                'OPTIONS' => $positionsOptionsFormatted,
                'VALUE'   => $positionsValueFormatted,
            ]
        ];
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ElementNotFoundException
     * @throws IblockException
     * @throws IblockNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function toArrayFormattedDetail(): array
    {
        return [
            'ID'            => $this->getId(),
            'FIO'           => $this->getFio(),
            'HIRING_DATE'   => $this->getHiringDate(),
            'POSITION'      => $this->getPosition()->getName()
        ];
    }

    /**
     * @return string
     */
    public function getListUrl(): string
    {
        return StaffEnum::LIST_URL;
    }


}
