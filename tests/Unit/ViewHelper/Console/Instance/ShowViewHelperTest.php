<?php

namespace DWenzel\DataCollector\Tests\Unit\ViewHelper\Console\Instance;

use DWenzel\DataCollector\Entity\Api;
use DWenzel\DataCollector\Entity\Instance;
use DWenzel\DataCollector\ViewHelper\Console\Instance\ShowViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Output\OutputInterface;

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
 * Class ShowViewHelperTest
 */
class ShowViewHelperTest extends TestCase
{
    /**
     * @var ShowViewHelper
     */
    protected $subject;

    /**
     * @var OutputInterface|MockObject
     */
    protected $output;

    public function setUp(): void
    {
        $this->output = $this->getMockForAbstractClass(OutputInterface::class);
        $this->subject = new ShowViewHelper($this->output);
    }


    public function testRenderDoesNothingForInvalidObject()
    {
        $this->output->expects($this->never())
            ->method('writeln');
        $this->subject->render();
    }

    public function testRenderWritesToOutput(){
        $uuid = Uuid::uuid4()->toString();
        $instanceName = 'foo';
        $instanceRole = 'bar';

        $instance = new Instance();
        $instance->setUuid($uuid)
            ->setName($instanceName)
            ->setRole($instanceRole);

        $apiVendor = 'baz';
        $apiName = 'apiName';
        $apiVersion = '1.0';
        $apiIdentifier = 'ooooId';
        $api = new Api();
        $api->setVendor($apiVendor)
            ->setName($apiName)
            ->setVersion($apiVersion)
            ->setIdentifier($apiIdentifier);
        $instance->addApi($api);
        $this->subject->setInstance($instance);

        $expectedLines = [];
        $expectedLines[] = sprintf(
            ShowViewHelper::INSTANCE_SHOW_MESSAGE,
            $uuid,
            null,
            $instanceName,
            $instanceRole
        );
        $expectedLines[] = ShowViewHelper::API_HEADER;
        $expectedLines[] = sprintf(
            ShowViewHelper::API_SINGLE_FORMAT,
            $apiVendor,
            $apiName,
            $apiVersion,
            $apiIdentifier
        );

        $this->output->expects($this->once())
            ->method('writeln')
            ->with($expectedLines);

        $this->subject->render();
    }
}