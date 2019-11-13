<?php

namespace DWenzel\DataCollector\Tests\Unit\Traits;

use DWenzel\DataCollector\Traits\Mode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputArgument;

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
class ModeTest extends TestCase
{
    /**
     * @var Mode|MockObject
     */
    protected $subject;


    public function setUp(): void
    {
        $this->subject = $this->getMockBuilder(Mode::class)
            ->getMockForTrait();
    }

    public function testGetModeReturnsDefaultMode()
    {
        $this->assertSame(
            InputArgument::REQUIRED,
            $this->subject->getMode()
        );
    }
}
