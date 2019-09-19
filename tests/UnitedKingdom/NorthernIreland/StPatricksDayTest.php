<?php
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

namespace Yasumi\tests\UnitedKingdom\NorthernIreland;

use DateTime;
use DateTimeZone;
use Yasumi\Holiday;
use Yasumi\tests\YasumiTestCaseInterface;

/**
 * Class for testing St. Patrick's Day in Northern Ireland.
 */
class StPatricksDayTest extends NorthernIrelandBaseTestCase implements YasumiTestCaseInterface
{
    /**
     * The name of the holiday to be tested
     */
    public const HOLIDAY = 'stPatricksDay';

    /**
     * The year in which the holiday was first established
     */
    public const ESTABLISHMENT_YEAR = 1971;

    /**
     * Tests the holiday defined in this test.
     *
     * @dataProvider HolidayDataProvider
     *
     * @param int       $year     the year for which the holiday defined in this test needs to be tested
     * @param \DateTime $expected the expected date
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testHoliday($year, $expected)
    {
        $date = new DateTime($expected, new DateTimeZone(self::TIMEZONE));
        $this->assertHoliday(self::REGION, self::HOLIDAY, $year, $date);

        if (\in_array((int)$date->format('w'), [0, 6], true)) {
            $date->modify('next monday');
            $this->assertHoliday(self::REGION, 'substituteHoliday:' . self::HOLIDAY, $year, $date);
        }
    }

    /**
     * Tests the holiday defined in this test before establishment.
     * @throws \ReflectionException
     */
    public function testHolidayBeforeEstablishment()
    {
        $this->assertNotHoliday(
            self::REGION,
            self::HOLIDAY,
            $this->generateRandomYear(1000, self::ESTABLISHMENT_YEAR - 1)
        );
    }

    /**
     * Returns a list of random test dates used for assertion of the holiday defined in this test
     *
     * @return array list of test dates for the holiday defined in this test
     * @throws \Exception
     */
    public function HolidayDataProvider(): array
    {
        $data = [];

        for ($y = 0; $y < self::TEST_ITERATIONS; $y++) {
            $year   = $this->generateRandomYear(self::ESTABLISHMENT_YEAR);
            $date   = new DateTime("$year-3-17", new DateTimeZone(self::TIMEZONE));
            $data[] = [$year, $date->format('Y-m-d')];
        }

        return $data;
    }

    /**
     * Tests the translated name of the holiday defined in this test.
     *
     * @throws \ReflectionException
     */
    public function testTranslation(): void
    {
        $this->assertTranslatedHolidayName(
            self::REGION,
            self::HOLIDAY,
            $this->generateRandomYear(self::ESTABLISHMENT_YEAR),
            [self::LOCALE => 'St. Patrick\'s Day']
        );
    }

    /**
     * Tests type of the holiday defined in this test.
     * @throws \ReflectionException
     */
    public function testHolidayType(): void
    {
        $this->assertHolidayType(
            self::REGION,
            self::HOLIDAY,
            $this->generateRandomYear(self::ESTABLISHMENT_YEAR),
            Holiday::TYPE_BANK
        );
    }
}
