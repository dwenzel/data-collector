<?php

namespace DWenzel\DataCollector\Tests\Unit\Configuration\Argument;

use PHPUnit\Framework\TestCase;
use DWenzel\DataCollector\Configuration\Option\DisabledOption;

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
 * Class DisabledOptionTest
 */
class DisabledOptionTest extends TestCase
{
    /**
     * @var DisabledOption
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new DisabledOption();
    }

    public function testConstructorSetsName()
    {
        $this->assertSame(
            DisabledOption::NAME,
            $this->subject->getName()
        );
    }

    public function testConstructorSetsMode()
    {
        $this->assertSame(
            DisabledOption::MODE,
            $this->subject->getMode()
        );
    }

    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            DisabledOption::DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testDefaultIsInitiallyFalse()
    {
        $this->assertFalse(
            $this->subject->getDefault()
        );
    }

}