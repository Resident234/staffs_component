<?php

namespace Vendor\Utils\Helpers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Type\DateTime;
use DateInterval;
use DatePeriod;
use DateTime as NormalDateTime;
use Exception;
use NotaTools\Helpers\DateHelper as NotaDateHelper;
use NotaTools\Helpers\DateHelper as ToolsDateHelper;
use NotaTools\Helpers\WordHelper;
use Vendor\Utils\Enum\PulseEnum;
use Vendor\Utils\Enum\StatisticEnum;
use Vendor\Utils\Service\ActionService;

/**
 * Class FileHelper
 * @package Vendor\Utils\Helpers
 */
class DateHelper extends ToolsDateHelper
{
    /**
     * @param DateTime $timestamp
     *
     * @return string
     * @throws Exception
     */
    public static function getDateFormatedForEvents(DateTime $timestamp): string
    {
        $formattedDate = '';
        $currentDate = self::convertToDateTime(new DateTime());
        $date = self::convertToDateTime($timestamp);
        $diff = $currentDate->diff($date);
        switch ($diff->d) {
            case 1:
                $formattedDate = 'Вчера';
                break;
            case 0:
                if ($diff->h > 0) {
                    $formattedDate = $diff->h . ' ' . WordHelper::declension($diff->h, ['час', 'часа', 'часов']) . ' назад';
                } else {
                    $formattedDate = 'Только что';
                }
                break;
        }
        return $formattedDate;
    }

    /**
     * @param                     $period
     * @param NormalDateTime|null $startDate
     * @param NormalDateTime|null $endDate
     *
     * @return mixed
     * @throws ArgumentException
     */
    public static function getLabelsForChart($period, NormalDateTime $startDate = null, NormalDateTime $endDate = null)
    {
        ActionService::checkActionCode($period);
        if ($startDate === null) {
            $startDate = self::getStartNormalDateTime($period);
        }
        if ($endDate === null) {
            $endDate = new NormalDateTime();
        }
        if ($period === 'day') {
            $endDate->add(new DateInterval('PT1H'));
        } else {
            $endDate->setTime($endDate->format('h'), 59, 59);
        }
        $methodName = 'getLabelsFor' . ucfirst($period);
        return self::$methodName($startDate, $endDate);
    }

    /**
     * @param string $period
     *
     * @return NormalDateTime
     * @throws ArgumentException
     */
    public static function getStartNormalDateTime(string $period): NormalDateTime
    {
        $period = strtolower($period);
        ActionService::checkActionCode($period);
        $startDate = new NormalDateTime;
        $startDate->add(DateInterval::createFromDateString('-1 ' . $period));
        switch ($period) {
            case 'day':
                break;
            case 'month':
                $startDate->modify('midnight');
                break;
            case 'year':
                $startDate->modify('midnight');
                $startDate->modify('first day of this month');
                break;
            case 'all':
            default:
                $startDate = new NormalDateTime(StatisticEnum::DATE_PERIOD_ALL);
                break;
        }
        return $startDate;
    }

    /**
     * @param NormalDateTime $startDate
     * @param NormalDateTime $endDate
     *
     * @return array
     * @throws Exception
     */
    public static function getLabelsForAll(NormalDateTime $startDate, NormalDateTime $endDate): array
    {
        $monthsLabels = [];
        $periods = new DatePeriod($startDate, new DateInterval('P1M'), $endDate);
        foreach ($periods as $date) {
            $monthsLabels[] = [
                'text' => $date->format(PulseEnum::CHART_LABELS_FORMAT['all']),
                'date' => $date->format(str_replace('%', '', PulseEnum::CHART_MYSQL_FORMAT['all'])),
            ];
        }
        return $monthsLabels;
    }

    /**
     * @param NormalDateTime $startDate
     * @param NormalDateTime $endDate
     *
     * @return array
     * @throws Exception
     */
    public static function getLabelsForYear(NormalDateTime $startDate, NormalDateTime $endDate): array
    {
        $monthsLabels = [];
        $periods = new DatePeriod($startDate, new DateInterval('P1M'), $endDate);
        foreach ($periods as $date) {
            $monthsLabels[] = [
                'text' => NotaDateHelper::replaceRuMonth($date->format('#n#'), 'ShortNominative', true),
                'date' => $date->format(str_replace('%', '', PulseEnum::CHART_MYSQL_FORMAT['year'])),
            ];
        }
        return $monthsLabels;
    }

    /**
     * @param NormalDateTime $startDate
     * @param NormalDateTime $endDate
     *
     * @return array
     * @throws Exception
     */
    public static function getLabelsForMonth(NormalDateTime $startDate, NormalDateTime $endDate): array
    {
        $monthsLabels = [];
        $periods = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        foreach ($periods as $date) {
            $monthsLabels[] = [
                'text' => $date->format(PulseEnum::CHART_LABELS_FORMAT['month']),
                'date' => $date->format(str_replace('%', '', PulseEnum::CHART_MYSQL_FORMAT['month'])),
            ];
        }
        return $monthsLabels;
    }

    /**
     * @param NormalDateTime $startDate
     * @param NormalDateTime $endDate
     *
     * @return array
     * @throws Exception
     */
    public static function getLabelsForDay(NormalDateTime $startDate, NormalDateTime $endDate): array
    {
        $monthsLabels = [];
        $periods = new DatePeriod($startDate, new DateInterval('PT1H'), $endDate);
        foreach ($periods as $date) {
            $monthsLabels[] = [
                'text' => $date->format(PulseEnum::CHART_LABELS_FORMAT['day']),
                'date' => $date->format(str_replace('%', '', PulseEnum::CHART_MYSQL_FORMAT['day'])),
            ];
        }
        return $monthsLabels;
    }

    /**
     * @param $date
     *
     * @return int
     */
    public static function getDaysInMonth($date): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $date->format('m'), $date->format('d'));
    }
}