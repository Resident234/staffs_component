<?php

namespace Vendor\Utils\Interfaces\Orm\Entity;

interface PositionsInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return static|PositionsInterface
     */
    public function setName(string $name): PositionsInterface;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     *
     * @return static|PositionsInterface
     */
    public function setDescription(string $description): PositionsInterface;

    /**
     * @return array
     */
    public function toArrayFormatted(): array;

    /**
     * @return array
     */
    public function toArray(): array;
}
