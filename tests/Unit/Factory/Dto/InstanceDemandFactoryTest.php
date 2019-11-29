<?php

namespace DWenzel\DataCollector\Tests\Unit\Factory\Dto;

use DWenzel\DataCollector\Configuration\Argument\InstanceNameArgument;
use DWenzel\DataCollector\Configuration\Argument\Role;
use DWenzel\DataCollector\Configuration\Option\IdentifierOption;
use DWenzel\DataCollector\Factory\Dto\InstanceDemandFactory;
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
 * Class InstanceDemandFactoryTest
 */
class InstanceDemandFactoryTest extends TestCase
{

    public function setUp(): void
    {
    }

    public function testFromSettingsSetsName()
    {
        $value = 'bar';
        $settings = [
            InstanceNameArgument::NAME => $value,
        ];

        $demand = InstanceDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getName()
        );
    }

    public function testFromSettingsSetsIdentifier()
    {
        $value = 'bar';
        $settings = [
            IdentifierOption::NAME => $value,
        ];

        $demand = InstanceDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getIdentifier()
        );
    }

    public function testFromSettingsSetsRole()
    {
        $value = 'bar';
        $settings = [
            Role::NAME => $value,
        ];

        $demand = InstanceDemandFactory::fromSettings($settings);

        $this->assertSame(
            $value,
            $demand->getRole()
        );
    }
}
