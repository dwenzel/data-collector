<?php

namespace DWenzel\DataCollector\ViewHelper;

use DWenzel\DataCollector\Renderable;
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

/**
 * Class TableViewHelper
 */
class TableViewHelper implements ViewHelperInterface, Renderable
{
    /**
     * @var Table
     */
    protected $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function render()
    {
        $this->table->render();
    }

    public function assign(array $variables): void
    {
        if (!empty($variables[SI::HEADERS_KEY])) {
            $this->table->setHeaders($variables[SI::HEADERS_KEY]);
        }
        if (!empty($variables[SI::ROWS_KEY]))
        {
            $this->table->setRows($variables[SI::ROWS_KEY]);
        }
    }
}
