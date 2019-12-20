<?php

namespace DWenzel\DataCollector\Tests\Unit\Configuration\Argument;

use PHPUnit\Framework\TestCase;
use DWenzel\DataCollector\Configuration\Option\ArgumentsOption;

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
 * Class ArgumentsOptionTest
 */
class ArgumentsOptionTest extends TestCase
{
    /**
     * @var ArgumentsOption
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new ArgumentsOption();
    }

    public function testConstructorSetsName()
    {
        $this->assertSame(
            ArgumentsOption::NAME,
            $this->subject->getName()
        );
    }

    public function testConstructorSetsMode()
    {
        $this->assertSame(
            ArgumentsOption::MODE,
            $this->subject->getMode()
        );
    }

    public function testConstructorSetsDescription()
    {
        $this->assertSame(
            ArgumentsOption::DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testDefaultIsInitiallyEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->getDefault()
        );
    }

}