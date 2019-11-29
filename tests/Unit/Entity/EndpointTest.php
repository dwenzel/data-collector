<?php

namespace DWenzel\DataCollector\Tests\Unit\Entity;

use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Endpoint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
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
class EndpointTest extends TestCase
{
    /**
     * @var Endpoint|MockObject
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Endpoint();
    }

    public function testIdIsInitiallyNull(): void
    {
        $this->assertNull(
            $this->subject->getId()
        );
    }

    public function testNameIsInitiallyEmpty(): void
    {
        $this->assertSame(
            '',
            $this->subject->getName()
        );
    }

    public function testDescriptionIsInitiallyEmpty(): void
    {
        $this->assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    public function testApiIsInitiallyNull()
    {
        $this->assertNull(
            $this->subject->getApi()
        );
    }

    public function testNameCanBeSet(): void
    {
        $name = 'bar';
        $this->subject->setName($name);

        $this->assertSame($name, $this->subject->getName());
    }

    public function testDescriptionCanBeSet(): void
    {
        $description = 'bas';
        $this->subject->setDescription($description);

        $this->assertSame(
            $description,
            $this->subject->getDescription()
        );
    }

    public function testApiCanBeSet(): void
    {
        $api = $this->createMock(Api::class);
        $this->subject->setApi($api);

        $this->assertSame(
            $api,
            $this->subject->getApi()
        );
    }
}
