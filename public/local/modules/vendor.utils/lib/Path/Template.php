<?php namespace Vendor\Utils\Path;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use CBitrixComponentTemplate;
use NotaTools\Path\TemplateBase;
use Vendor\Utils\Interfaces\Path\TemplateInterface;

/**
 * Class MainTemplate
 *
 * Класс для основного шаблона
 *
 * @package Vendor\Utils\Path
 */
class Template extends TemplateBase implements TemplateInterface
{

    /**
     * @param string $scriptName
     */
    public static function loadScripts(string $scriptName, bool $isBeforeCore = false)
    {
        $asset = Asset::getInstance();
        $asset->addCss(static::getFrontendPath('/css/' . $scriptName . '.css'));
        if (!$isBeforeCore) {
            $asset->addJs(static::getFrontendPath('/js/' . $scriptName . '.js'));
        } else {
            $asset->addString('<script type="text/javascript" src="' . static::getFrontendPath("/js/" . $scriptName . ".js") . '" data-skip-moving="true"></script>', AssetLocation::BEFORE_CSS);
        }
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param string $scriptName
     */
    public static function loadScriptsComponent(CBitrixComponentTemplate $template, string $scriptName, bool $isBeforeCore = false)
    {
        $template->addExternalCss(static::getFrontendPath('/css/' . $scriptName . '.css'));
        if (!$isBeforeCore) {
            $template->addExternalJs(static::getFrontendPath('/js/' . $scriptName . '.js'));
        } else {
            $asset = Asset::getInstance();
            $asset->addString('<script type="text/javascript" src="' . static::getFrontendPath("/js/" . $scriptName . ".js") . '" data-skip-moving="true"></script>', AssetLocation::BEFORE_CSS);
        }
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function getFrontendPath(string $string): string
    {
        return static::FRONTEND_PATH . $string;
    }

    /**
     * @return bool
     */
    public function isPersonal(): bool
    {
        return $this->isDir('/personal');
    }

    /**
     * @return bool
     */
    public function isPersonalEdit(): bool
    {
        return $this->isDir('/personal/edit');
    }

    /**
     * @return bool
     */
    public function isPersonalView(): bool
    {
        return preg_match(sprintf('~^%s/\d+~', '/personal'), $this->getDir()) > 0;
    }

    /**
     * @return bool
     */
    public function isPersonalPage(): bool
    {
        return $this->isPartitionDir('/personal');
    }

    /**
     * @return bool
     */
    public function hasProfilePage(): bool
    {
        return ($this->isPersonal() || ($this->isPersonalPage() && ($this->isPersonalEdit() || $this->isPersonalView())));
    }

    /**
     * @return bool
     */
    public function isScopes(): bool
    {
        return $this->isDir('/scopes');
    }

    /**
     * @return bool
     */
    public function isGroups(): bool
    {
        return $this->isPartitionDir('/groups');
    }

    /**
     * @return bool
     */
    public function isGroupsList(): bool
    {
        return $this->isDir('/groups');
    }

    /**
     * @return bool
     */
    public function isGroupsEdit(): bool
    {
        return $this->isDir('/groups/0/edit');
    }

    /**
     * @return bool
     */
    public function isGroupsCreate(): bool
    {
        return $this->isDir('/groups/0/edit');
    }

    /**
     * @return bool
     */
    public function isGroupEdit(): bool
    {
        return $this->isDirByRule('/groups/.*/edit/*$');
    }

    /**
     * @return bool
     */
    public function isGroupDetail(): bool
    {
        return $this->isDirByRule('/groups/[^/]*$');
    }

    /**
     * @return bool
     */
    public function isPublicationsList(): bool
    {
        return $this->isDir('/publications');
    }

    /**
     * @return bool
     */
    public function isUserDetail(): bool
    {
        return $this->isPartitionDir('/users') && !$this->isUserList();
    }

    /**
     * @return bool
     */
    public function isUserList(): bool
    {
        return $this->isDir('/users');
    }

    /**
     * @return bool
     */
    public function isAdvertList(): bool
    {
        return $this->isDir('/publications');
    }

    /**
     * @return bool
     */
    public function isAdvertPage(): bool
    {
        return $this->isPartitionDir('/publications');
    }

    /**
     * @return bool
     */
    public function hasAdvertPage(): bool
    {
        return ($this->isAdvertPage() || $this->isAdvertList());
    }

    /**
     * @return bool
     */
    public function isVoteList(): bool
    {
        return $this->isDir('/personal/vote');
    }

    /**
     * @return bool
     */
    public function isActionsList(): bool
    {
        return $this->isDir('/personal/actions');
    }

    /**
     * @return bool
     */
    public function isAppealsList(): bool
    {
        return $this->isDir('/personal/appeals');
    }

    /**
     * @return bool
     */
    public function isOffersList(): bool
    {
        return $this->isDir('/personal/offers');
    }

    /**
     * @return bool
     */
    public function isHelpPage(): bool
    {
        return $this->isPartitionDir('/help');
    }

    /**
     * @return bool
     */
    public function isHelpSection(): bool
    {
        $isSection = $this->isDirByRule('/help/[^/]*$');
        return $isSection && !$this->isHelpDetail();
    }

    /**
     * @return bool
     */
    public function isHelpDetail(): bool
    {
        return $this->isDirByRule('/help/.*/[^/]*$');
    }

    /**
     * @return bool
     */
    public function isHelpMain(): bool
    {
        return $this->isDir('/help');
    }

    /**
     * @return bool
     */
    public function isServicePage(): bool
    {
        return $this->isPartitionDir('/services') || $this->isDir('/services');
    }

    public function isSearchPage(): bool
    {
        return $this->isDir('/search');
    }

    /**
     * @return bool
     */
    public function isServicesList(): bool
    {
        return $this->isDir('/services');
    }

    /**
     * @return bool
     */
    public function hasHelpPage(): bool
    {
        return ($this->isHelpMain() || $this->isHelpPage());
    }

    /**
     * @return bool
     */
    public function useRightMenu(): bool
    {
        return $this->usePersonalRightMenu() || $this->useNewsRightMenu();
    }

    /**
     * @return bool
     */
    public function usePersonalRightMenu(): bool
    {
        return !$this->isIndex() && (($this->isPersonalPage() || $this->isPersonal() || $this->hasHelpPage() || $this->hasAdvertPage() || $this->isServicePage() || $this->isSearchPage()) || ($this->isGroups() && !$this->isGroupsList() && !$this->isGroupsEdit())
                || $this->useHCSMenu());
    }

    /**
     * @return bool
     */
    public function useNewsMenu(): bool
    {
        return !$this->isIndex() && (($this->isNewsList() || $this->isNewsScopeList()));
    }

    /**
     * @return bool
     */
    public function useHCSMenu(): bool
    {
        return $this->isDirByRule('/hcs*');
    }

    /**
     * @return bool
     */
    public function useMenu(): bool
    {
        return $this->useRightMenu() || $this->useNewsMenu();
    }

    /**
     * @return bool
     */
    public function useBreadcrumb(): bool
    {
        return !$this->isIndex() && !$this->hasProfilePage() && !$this->isUserDetail() && !$this->isHelpMain() && !$this->isHelpSection();
    }

    /**
     * @return bool
     */
    public function useTitle(): bool
    {
        return !$this->isIndex() && !$this->hasProfilePage() && !$this->isUserDetail() && !$this->isHelpMain() && !$this->isHelpSection();
    }

    /**
     * @return bool
     */
    public function useNewsRightMenu(): bool
    {
        return $this->isNewsList() || $this->isNewsScopeList();
    }

    /**
     * @return bool
     */
    public function isNews(): bool
    {
        return $this->isPartitionDir('/news');
    }

    /**
     * @return bool
     */
    public function isNewsList(): bool
    {
        return $this->isDir('/news');
    }

    /**
     * @return bool
     */
    public function isNewsScopeList(): bool
    {
        return $this->isDirByRule('/news/scope/[^/]*$');
    }

    /**
     * @return string
     */
    public function getRightMenuCode(): string
    {
        $result = 'left';
        if ($this->useNewsRightMenu()) {
            $result = 'news';
        }
        if ($this->usePersonalRightMenu()) {
            $result = 'left';
        }
        if ($this->hasHelpPage()) {
            $result = 'help';
        }
        if ($this->useHCSMenu()) {
            $result = 'hcs';
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getRightMenuEntity(): string
    {
        $result = 'personal';
        if ($this->useNewsRightMenu()) {
            $result = 'news';
        } elseif($this->useHCSMenu()) {
            $result = 'hcs';
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->isVoteList() || $this->isActionsList() || $this->isAppealsList() || $this->isOffersList() || $this->isServicesList() || $this->isPublicationsList();
    }


}
