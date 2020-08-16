<?php namespace Vendor\Utils\Interfaces\Path;

use NotaTools\Interfaces\Path\TemplateBaseInterface;

/**
 * Class MainTemplate
 *
 * Класс для основного шаблона
 *
 * @package Vendor\Utils\Path
 */
interface TemplateInterface extends TemplateBaseInterface
{

    public const FRONTEND_PATH = '/local/frontend';

    /**
     * @param string $string
     *
     * @return string
     */
    public static function getFrontendPath(string $string): string;

    /**
     * @return bool
     */
    public function isPersonal(): bool;

    /**
     * @return bool
     */
    public function isPersonalEdit(): bool;

    /**
     * @return bool
     */
    public function isPersonalView(): bool;

    /**
     * @return bool
     */
    public function isPersonalPage(): bool;

    /**
     * @return bool
     */
    public function hasProfilePage(): bool;
}
