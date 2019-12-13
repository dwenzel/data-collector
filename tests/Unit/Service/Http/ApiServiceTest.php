<?php

namespace DWenzel\DataCollector\Tests\Unit\Service\Http;

use DWenzel\DataCollector\Service\Dto\ApiCallDemand;
use DWenzel\DataCollector\Service\Dto\ServiceDemandInterface;
use DWenzel\DataCollector\Service\Http\ApiService;
use DWenzel\DataCollector\Service\Http\ApiServiceInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

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
 * Class ApiServiceTest
 */
class ApiServiceTest extends TestCase
{
    /**
     * @var ApiService
     */
    protected $subject;

    /**
     * @var HttpClientInterface|MockObject
     */
    protected $httpClient;

    /**
     * @var ApiCallDemand|ServiceDemandInterface|MockObject
     */
    protected $demand;

    public function setUp(): void
    {
        $this->demand = $this->createMock(ApiCallDemand::class);

        $this->httpClient = $this->getMockBuilder(HttpClientInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->subject = new ApiService($this->httpClient);
    }

    public function testClassImplementsApiServiceInterface()
    {
        $this->assertInstanceOf(
            ApiServiceInterface::class,
            $this->subject
        );
    }

    public function testCallReturnsArrayRepresentationOfResponse()
    {

        $responseArray = ['goo'];

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('toArray')
            ->willReturn($responseArray);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $this->assertSame(
            $responseArray,
            $this->subject->call($this->demand)
        );
    }

    public function testCallReturnsArrayRepresentationOfException()
    {
        $code = 123;
        $message = 'foo';
        $exception = new Exception($message, $code);

        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $response->expects($this->once())
            ->method('toArray')
            ->willThrowException($exception);
        $responseArray = [
            'exception' => [
                'class' => Exception::class,
                'code' => $code,
                'message' => $message
            ]
        ];

        $this->assertSame(
            $responseArray,
            $this->subject->call($this->demand)
        );
    }
}