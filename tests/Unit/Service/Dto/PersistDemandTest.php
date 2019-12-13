<?php

namespace DWenzel\DataCollector\Tests\Unit\Service\Dto;

use DWenzel\DataCollector\Service\Dto\PersistDemand;
use DWenzel\DataCollector\Service\Dto\ServiceDemandInterface;
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
 * Class PersistDemandTest
 */
class PersistDemandTest extends TestCase
{
    /**
     * @var PersistDemand
     */
    protected $subject;

    private const PARAMETER = [
        'foo' => 'bar'
    ];
    private const PAYLOAD = [
        'baz' => 'boom'
    ];

    public function setUp(): void
    {
        $this->subject = new PersistDemand(self::PAYLOAD, self::PARAMETER);
    }

    public function testClassImplementsServiceDemandInterface()
    {
        $this->assertInstanceOf(
            ServiceDemandInterface::class,
            $this->subject
        );
    }

    public function testConstructorSetsPayload()
    {
        $this->assertSame(
            self::PAYLOAD,
            $this->subject->getPayload()
        );
    }

    public function testConstructorSetsParameter()
    {
        $this->assertSame(
            self::PARAMETER,
            $this->subject->getParameter()
        );
    }
}