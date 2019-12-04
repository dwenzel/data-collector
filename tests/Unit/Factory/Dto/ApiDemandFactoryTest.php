<?php

namespace DWenzel\DataCollector\Tests\Unit\Factory\Dto;

use DWenzel\DataCollector\Configuration\Argument\ApiIdentifierArgument;
use DWenzel\DataCollector\Configuration\Argument\ApiNameArgument;
use DWenzel\DataCollector\Configuration\Argument\VendorArgument;
use DWenzel\DataCollector\Configuration\Argument\VersionArgument;
use DWenzel\DataCollector\Configuration\Option\IdentifierOption;
use DWenzel\DataCollector\Factory\Dto\ApiDemandFactory;
use DWenzel\DataCollector\SettingsInterface as SI;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class ApiDemandFactoryTest
 */
class ApiDemandFactoryTest extends TestCase
{

    public function testFromSettingsSetsName(): void
    {
        $value = 'bar';
        $settings = [
            ApiNameArgument::NAME => $value,
        ];

        $demand = ApiDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getName()
        );
    }

    public function testFromSettingsSetsIdentifierFromIdentifierOption(): void
    {
        $value = 'bar';
        $settings = [
            IdentifierOption::NAME => $value,
        ];

        $demand = ApiDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getIdentifier()
        );
    }

    public function testFromSettingsSetsIdentifierFromApiIdentifierArgument(): void
    {
        $value = 'baz';
        $settings = [
            ApiIdentifierArgument::NAME => $value,
        ];

        $demand = ApiDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getIdentifier()
        );
    }

    public function testFromSettingsThrowsExceptionForDuplicateIdentifier(): void
    {
        $identifierOption = 'foo';
        $identifierArgument = 'bar';

        $settings = [
            ApiIdentifierArgument::NAME => $identifierArgument,
            IdentifierOption::NAME => $identifierOption
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1575364190);
        $expectedMessage = sprintf(ApiDemandFactory::EXCEPTION_MESSAGE_DUPLICATE_SETTING,
            ApiIdentifierArgument::NAME,
            IdentifierOption::NAME,
            SI::IDENTIFIER_KEY
        );
        $this->expectErrorMessage($expectedMessage);

        ApiDemandFactory::fromSettings($settings);
    }

    public function testFromSettingsSetsVersion(): void
    {
        $value = 'bar';
        $settings = [
            VersionArgument::NAME => $value,
        ];

        $demand = ApiDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getVersion()
        );
    }

    public function testFromSettingsSetsVendor(): void
    {
        $value = 'bar';
        $settings = [
            VendorArgument::NAME => $value,
        ];

        $demand = ApiDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getVendor()
        );
    }
}
