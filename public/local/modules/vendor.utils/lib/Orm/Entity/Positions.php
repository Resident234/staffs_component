<?php

namespace Vendor\Utils\Orm\Entity;

use Bitrix\Main\{ArgumentException, ObjectPropertyException, SystemException};
use NotaTools\Exception\Iblock\{IblockException, IblockNotFoundException};
use NotaTools\Exception\Iblock\ElementNotFoundException;
use Vendor\Utils\Interfaces\Orm\Entity\PositionsInterface;
use Vendor\Utils\Orm\Tables\EO_Positions;
use Vendor\Utils\Orm\Tables\PositionsTable;

/**
 * Class Positions
 * @package Vendor\Utils\Orm\Entity
 */
class Positions implements PositionsInterface
{
    /**
     * @var EO_Positions
     */
    protected $element;

    /**
     * Positions constructor.
     *
     * @param EO_Positions|$id int
     *
     * @throws ElementNotFoundException
     */
    public function __construct($id)
    {
        $this->createObject($id);
    }

    /**
     * @return array
     */
    public static function getDefaultFields(): array
    {
        return [
            'ID'          => null,
            'NAME'        => '',
            'DESCRIPTION' => ''
        ];
    }

    /**
     * @return array
     */
    public static function getSelect(): array
    {
        return ['ID', 'NAME'];
    }

    public static function getOrder(): array
    {
        return ['ID' => 'DESC'];
    }

    /**
     * @param $id
     *
     * @throws ElementNotFoundException
     */
    public function createObject($id): void
    {
        if ($id > 0 || $id instanceof EO_Positions) {
            if (is_numeric($id)) {
                try {
                    $this->element = PositionsTable::getById($id)->fetchObject();
                    if ($this->element === null) {
                        throw new ElementNotFoundException('не удалось установить элемент');
                    }
                } catch (ArgumentException|SystemException $e) {
                    throw new ElementNotFoundException($e->getMessage());
                }
            } else {
                $this->element = $id;
            }

        } else {
            $this->element = new EO_Positions();
        }
    }

    /**
     * @return EO_Positions|null
     */
    public function getElement(): ?EO_Positions
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
    public function getDescription(): string
    {
        return (string)$this->getElement()->getDescription();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getElement()->getName();
    }

    /**
     * @param string $name
     *
     * @return PositionsInterface
     */
    public function setName(string $name): PositionsInterface
    {
        $this->getElement()->setName($name);
        return $this;
    }

    /**
     * @param string $description
     *
     * @return PositionsInterface
     */
    public function setDescription(string $description): PositionsInterface
    {
        $this->getElement()->setDescription($description);
        return $this;
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
            'ID'          => $this->getId(),
            'NAME'        => $this->getName(),
            'DESCRIPTION' => $this->getDescription()
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
    public function toArrayFormattedFilterList(): array
    {
        return [
            'ID'          => $this->getId(),
            'NAME'        => $this->getName(),
            'VALUE'       => $this->getId(),
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
    public static function toArrayFormattedFilterListEmpty(): array
    {
        return [
            'ID'          => 0,
            'NAME'        => '',
            'VALUE'       => 0
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'ID'          => $this->getId(),
            'NAME'        => $this->getName(),
            'DESCRIPTION' => $this->getDescription()
        ];
    }
}
