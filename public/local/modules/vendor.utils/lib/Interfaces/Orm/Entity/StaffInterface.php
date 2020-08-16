<?php

namespace Vendor\Utils\Interfaces\Orm\Entity;

interface StaffInterface
{
    public const FORMATTED_TYPE = [
        'ACTUAL'         => 0,
        'REAL'           => 1,
        'FORMATTED'      => 2,
        'FORMATTED_EDIT' => 3,
        'FORMATTED_LIST' => 4,
    ];

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getFIO(): string;

    /**
     * @return string
     */
    public function getHiringDate(): string;/** @todo date */

    /**
     * @return int
     */
    public function getPositionId(): int;

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param string $name
     *
     * @return static|StaffInterface
     */
    public function setFIO(string $fio): StaffInterface;

    /**
     * @param string $date
     *
     * @return static|StaffInterface
     */
    public function setHiringDate(string $date): StaffInterface;

    /**
     * @param int $id
     * @return StaffInterface
     */
    public function setPositionId(int $id): StaffInterface;

    /**
     * @return array
     */
    public function toArrayFormatted(): array;

    /**
     * @return array
     */
    public function toArrayFormattedEdit(): array;
}
