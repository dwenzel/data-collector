<?php

namespace DWenzel\DataCollector\Command\Instance;


use DWenzel\DataCollector\Command\ForgetInstanceCommand;
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
abstract class AbstractInstanceCommand extends Command
{
    public const OPTIONS = [

    ];
    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    /**
     * Returns a list of argument classes
     *
     * @return iterable
     */
    protected function getArguments(): iterable
    {
        return static::ARGUMENTS;
    }

    /**
     * Returns a list of option classes
     *
     * @return iterable
     */
    protected function getOptions(): iterable
    {
        return static::OPTIONS;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_HELP)
            ->registerArguments()
            ->registerOptions();
    }

    /**
     * @param InputInterface $input
     * @return \DWenzel\DataCollector\Entity\Dto\InstanceDemand
     */
    protected function createDemandFromInput(InputInterface $input): \DWenzel\DataCollector\Entity\Dto\InstanceDemand
    {
        $arguments = $input->getArguments() ? $input->getArguments() : [];
        $options = $input->getOptions() ? $input->getOptions() : [];

        $settings = array_merge($arguments, $options);
        $demand = InstanceDemandFactory::fromSettings($settings);
        return $demand;
    }
}