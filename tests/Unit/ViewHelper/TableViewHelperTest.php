<?php

namespace DWenzel\DataCollector\Tests\Unit\ViewHelper;

use DWenzel\DataCollector\ViewHelper\TableViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\Table;
use DWenzel\DataCollector\SettingsInterface as SI;

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
class TableViewHelperTest extends TestCase
{
    /**
     * @var TableViewHelper
     */
    protected $subject;

    /**
     * @var Table|MockObject
     */
    protected $table;

    public function setUp(): void
    {
        $this->table = $this->createMock(Table::class);

        $this->subject =  new TableViewHelper($this->table);
    }

    public function testAssignSetsHeaders(): void
    {
        $headers = ['foo', 'bar'];
        $variables = [
            SI::HEADERS_KEY => $headers
        ];

        $this->table->expects($this->atLeastOnce())
            ->method('setHeaders')
            ->with($headers);

        $this->subject->assign($variables);
    }

    public function testAssignSetsRows(): void
    {
        $rows = ['foo', 'bar'];
        $variables = [
            SI::ROWS_KEY => $rows
        ];

        $this->table->expects($this->atLeastOnce())
            ->method('setRows')
            ->with($rows);

        $this->subject->assign($variables);
    }

    public function testRenderRendersTable()
    {
        $this->table->expects($this->once())
            ->method('render');

        $this->subject->render();
    }
}
