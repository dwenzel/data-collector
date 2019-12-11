<?php

namespace DWenzel\DataCollector\Tests\Unit\Configuration\Argument;

use DWenzel\DataCollector\Configuration\Argument\ArgumentInterface;
use DWenzel\DataCollector\Configuration\Argument\ApiIdentifierArgument;
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
class ApiIdentifierArgumentTest extends TestCase
{
    /**
     * @var ApiIdentifierArgument
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new ApiIdentifierArgument();
    }

    public function testSubjectImplementsArgumentInterface()
    {
        $this->assertInstanceOf(
            ArgumentInterface::class,
            $this->subject
        );
    }

    public function testGetNameReturnsClassConstant()
    {
        $this->assertSame(
            ApiIdentifierArgument::NAME,
            $this->subject->getName()
        );
    }

    public function testGetDescriptionReturnsClassConstant()
    {
        $this->assertSame(
            ApiIdentifierArgument::DESCRIPTION,
            $this->subject->getDescription()
        );
    }

    public function testGetModeReturnsClassConstant()
    {
        $this->assertSame(
            ApiIdentifierArgument::MODE,
            $this->subject->getMode()
        );
    }

    public function testConstructorSetsDefault()
    {
        $default = 'foo';
        $this->subject = new ApiIdentifierArgument(
            ApiIdentifierArgument::NAME,
            ApiIdentifierArgument::OPTIONAL,
            ApiIdentifierArgument::DESCRIPTION,
            $default
        );

        $this->assertSame(
            $default,
            $this->subject->getDefault()
        );
    }
}
