<?php

namespace DWenzel\DataCollector\Tests\Unit\Service\Dto;

use DWenzel\DataCollector\Service\Dto\ApiCallDemand;
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
 * Class ApiCallDemandTest
 */
class ApiCallDemandTest extends TestCase
{

    /**
     * @var ApiCallDemand
     */
    protected $subject;

    private const URL = 'foo://bar.baz';

    public function setUp(): void
    {
        $this->subject = new ApiCallDemand(self::URL);
    }

    public function testInstanceImplementsServiceDemandInterface()
    {
        $this->assertInstanceOf(
            ServiceDemandInterface::class,
            $this->subject
        );
    }

    public function testConstructorSetsUrl()
    {
        $this->assertSame(
            self::URL,
            $this->subject->getUrl()
        );
    }

    public function testConstructorSetsInitialMethod()
    {
        $this->assertSame(
            ApiCallDemand::METHOD_GET,
            $this->subject->getMethod()
        );
    }

    public function testGetOptionsInitiallyReturnsEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->getOptions()
        );
    }

    public function testConstructorSetsOptions()
    {
        $options = [
            'foo' => 'bar'
        ];

        $demand = new ApiCallDemand(self::URL, $options);

        $this->assertSame(
            $options,
            $demand->getOptions()
        );
    }

    public function testConstructorSetsMethod()
    {
        $method = 'boom';
        $demand = new ApiCallDemand(self::URL, [], $method);
        $this->assertSame(
            $method,
            $demand->getMethod()
        );
    }
}