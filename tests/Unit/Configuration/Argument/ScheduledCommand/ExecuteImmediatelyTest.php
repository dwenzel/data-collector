<?php

namespace DWenzel\DataCollector\Tests\Unit\Configuration\Argument;

use DWenzel\DataCollector\Configuration\Argument\ScheduledCommand\ExecuteImmediately;
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
 * Class ExecuteImmediatelyTest
 */
class ExecuteImmediatelyTest extends TestCase
{
    /**
     * @var ExecuteImmediately
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new ExecuteImmediately();
    }

    public function testConstructorSetsName()
    {
        $this->assertSame(
            ExecuteImmediately::NAME,
            $this->subject->getName()
        );
    }

    public function testConstructorSetsMode()
    {
        $this->assertSame(
            ExecuteImmediately::MODE,
            $this->subject->getMode()
        );
    }

    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            ExecuteImmediately::DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testDefaultIsInitiallyZerok()
    {
        $this->assertFalse(
            $this->subject->getDefault()
        );
    }

}