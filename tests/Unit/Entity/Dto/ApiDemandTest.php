<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity\Dto;

use DWenzel\DataCollector\Entity\Dto\ApiDemand;
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
 * Class ApiDemandTest
 */
class ApiDemandTest extends TestCase
{
    /**
     * @var ApiDemand
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new ApiDemand();
    }

    public function testGetUuidsInitiallyReturnsEmptyString()
    {
        $this->assertEquals(
            '',
            $this->subject->getUuids()
        );
    }

    public function testUuidsCanBeSet()
    {
        $uuids = 'foo,bar';
        $this->subject->setUuids($uuids);
        $this->assertSame(
            $uuids,
            $this->subject->getUuids()
        );
    }

    public function testGetNameInitiallyReturnsEmptyString()
    {
        $this->assertEquals(
            '',
            $this->subject->getName()
        );
    }

    public function testNameCanBeSet()
    {
        $name = 'foobar';
        $this->subject->setName($name);
        $this->assertSame(
            $name,
            $this->subject->getName()
        );
    }

    public function testGetActiveInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getActive()
        );
    }

    public function testActiveCanBeSet()
    {
        $active = true;
        $this->subject->setActive($active);
        $this->assertSame(
            $active,
            $this->subject->getActive()
        );
    }

    public function testSetUiidsReturnsApi()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setUuids('foo')
        );
    }

    public function testSetNameReturnsApi()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setName('bar')
        );
    }

    public function testSetActiveReturnsApi()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setActive(false)
        );
    }

    public function testGetIdentifierInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getIdentifier()
        );
    }

    public function testSetIdentifierReturnsApi()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setIdentifier('foo')
        );
    }
}
