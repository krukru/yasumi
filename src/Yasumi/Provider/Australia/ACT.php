<?php declare(strict_types=1);
/**
 * This file is part of the Yasumi package.
 *
 * Copyright (c) 2015 - 2019 AzuyaLabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Sacha Telgenhof <me@sachatelgenhof.com>
 */

namespace Yasumi\Provider\Australia;

use DateInterval;
use DateTime;
use DateTimeZone;
use Yasumi\Exception\UnknownLocaleException;
use Yasumi\Holiday;
use Yasumi\Provider\Australia;

/**
 * Provider for all holidays in Australian Capital Territory (Australia).
 *
 */
class ACT extends Australia
{
    /**
     * Code to identify this Holiday Provider. Typically this is the ISO3166 code corresponding to the respective
     * country or sub-region.
     */
    public const ID = 'AU-ACT';

    public $timezone = 'Australia/ACT';

    /**
     * Initialize holidays for Australian Capital Territory (Australia).
     *
     * @throws \InvalidArgumentException
     * @throws UnknownLocaleException
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->addHoliday($this->easterSunday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->easterSaturday($this->year, $this->timezone, $this->locale));
        $this->calculateQueensBirthday();
        $this->calculateLabourDay();
        $this->calculateCanberraDay();
        $this->calculateReconciliationDay();
    }

    /**
     * Easter Sunday.
     *
     * Easter is a festival and holiday celebrating the resurrection of Jesus Christ from the dead. Easter is celebrated
     * on a date based on a certain number of days after March 21st. The date of Easter Day was defined by the Council
     * of Nicaea in AD325 as the Sunday after the first full moon which falls on or after the Spring Equinox.
     *
     * @link https://en.wikipedia.org/wiki/Easter
     *
     * @param int $year the year for which Easter Saturday need to be created
     * @param string $timezone the timezone in which Easter Saturday is celebrated
     * @param string $locale the locale for which Easter Saturday need to be displayed in.
     * @param string $type The type of holiday. Use the following constants: TYPE_OFFICIAL, TYPE_OBSERVANCE,
     *                         TYPE_SEASON, TYPE_BANK or TYPE_OTHER. By default an official holiday is considered.
     *
     * @return Holiday
     *
     * @throws UnknownLocaleException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    private function easterSunday(
        int $year,
        string $timezone,
        string $locale,
        ?string $type = null
    ): Holiday {
        return new Holiday(
            'easter',
            ['en' => 'Easter Sunday'],
            $this->calculateEaster($year, $timezone),
            $locale,
            $type ?? Holiday::TYPE_OFFICIAL
        );
    }

    /**
     * Easter Saturday.
     *
     * Easter is a festival and holiday celebrating the resurrection of Jesus Christ from the dead. Easter is celebrated
     * on a date based on a certain number of days after March 21st. The date of Easter Day was defined by the Council
     * of Nicaea in AD325 as the Sunday after the first full moon which falls on or after the Spring Equinox.
     *
     * @link https://en.wikipedia.org/wiki/Easter
     *
     * @param int $year the year for which Easter Saturday need to be created
     * @param string $timezone the timezone in which Easter Saturday is celebrated
     * @param string $locale the locale for which Easter Saturday need to be displayed in.
     * @param string $type The type of holiday. Use the following constants: TYPE_OFFICIAL, TYPE_OBSERVANCE,
     *                         TYPE_SEASON, TYPE_BANK or TYPE_OTHER. By default an official holiday is considered.
     *
     * @return Holiday
     *
     * @throws UnknownLocaleException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    private function easterSaturday(
        int $year,
        string $timezone,
        string $locale,
        ?string $type = null
    ): Holiday {
        return new Holiday(
            'easterSaturday',
            ['en' => 'Easter Saturday'],
            $this->calculateEaster($year, $timezone)->sub(new DateInterval('P1D')),
            $locale,
            $type ?? Holiday::TYPE_OFFICIAL
        );
    }

    /**
     * Queens Birthday.
     *
     * The Queen's Birthday is an Australian public holiday but the date varies across
     * states and territories. Australia celebrates this holiday because it is a constitutional
     * monarchy, with the English monarch as head of state.
     *
     * Her actual birthday is on April 21, but it's celebrated as a public holiday on the second Monday of June.
     *  (Except QLD & WA)
     *
     * @link https://www.timeanddate.com/holidays/australia/queens-birthday
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    private function calculateQueensBirthday(): void
    {
        $this->calculateHoliday(
            'queensBirthday',
            new DateTime('second monday of june ' . $this->year, new DateTimeZone($this->timezone)),
            ['en' => "Queen's Birthday"],
            false,
            false
        );
    }

    /**
     * Labour Day
     *
     * @throws \Exception
     */
    private function calculateLabourDay(): void
    {
        $date = new DateTime("first monday of october $this->year", new DateTimeZone($this->timezone));

        $this->addHoliday(new Holiday('labourDay', [], $date, $this->locale));
    }

    /**
     * Canberra Day
     *
     * @throws \Exception
     */
    private function calculateCanberraDay(): void
    {
        $datePattern = $this->year < 2007 ? "third monday of march $this->year" : "second monday of march $this->year";

        $this->addHoliday(
            new Holiday(
                'canberraDay',
                ['en' => 'Canberra Day'],
                new DateTime($datePattern, new DateTimeZone($this->timezone)),
                $this->locale
            )
        );
    }

    /**
     * Reconciliation Day
     *
     * @throws \Exception
     */
    private function calculateReconciliationDay(): void
    {
        if ($this->year < 2018) {
            return;
        }

        $date = new DateTime($this->year . '-05-27', new DateTimeZone($this->timezone));
        $day = (int)$date->format('w');
        if (1 !== $day) {
            $date = $date->add(0 === $day ? new DateInterval('P1D') : new DateInterval('P' . (8 - $day) . 'D'));
        }
        $this->addHoliday(new Holiday('reconciliationDay', ['en' => 'Reconciliation Day'], $date, $this->locale));
    }
}
