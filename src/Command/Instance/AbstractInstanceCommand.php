<?php

namespace DWenzel\DataCollector\Command\Instance;


use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Command\Instance\ForgetCommand;
use DWenzel\DataCollector\Command\RegisterArgumentsTrait;
use DWenzel\DataCollector\Command\RegisterOptionsTrait;
use DWenzel\DataCollector\Factory\Dto\InstanceDemandFactory;
use DWenzel\DataCollector\Service\InstanceManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

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
 * Class AbstractInstanceCommand
 */
abstract class AbstractInstanceCommand extends AbstractCommand
{
    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    /**
     * @param InputInterface $input
     * @return \DWenzel\DataCollector\Entity\Dto\InstanceDemand
     */
    protected function createDemandFromInput(InputInterface $input): \DWenzel\DataCollector\Entity\Dto\InstanceDemand
    {
        $settings = $this->getSettingsFromInput($input);

        return InstanceDemandFactory::fromSettings($settings);
    }

}
