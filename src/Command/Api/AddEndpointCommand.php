<?php

namespace DWenzel\DataCollector\Command\Api;

use Doctrine\ORM\EntityNotFoundException;
use DWenzel\DataCollector\Command\AbstractCommand;
use DWenzel\DataCollector\Configuration\Argument\EndPointArgument;
use DWenzel\DataCollector\Configuration\Argument\IdentifierArgument;
use DWenzel\DataCollector\Entity\Dto\ApiDemand;
use DWenzel\DataCollector\Entity\Endpoint;
use DWenzel\DataCollector\Factory\Dto\ApiDemandFactory;
use DWenzel\DataCollector\Repository\ApiRepository;
use DWenzel\DataCollector\Service\ApiManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
class AddEndpointCommand extends AbstractCommand
{
    const COMMAND_DESCRIPTION = 'Adds an endpoint to an API.';
    const COMMAND_HELP = 'Provide API identifier and name of the endpoint. The API must be registered beforehand.';
    const DEFAULT_COMMAND_NAME = 'data-collector:api:add-endpoint';

    /**
     * @var ApiManagerInterface
     */
    protected $apiManager;

    /**
     * @var ApiRepository
     */
    protected $apiRepository;

    /**
     * Command Arguments
     */
    const ARGUMENTS = [
        EndPointArgument::class,
        IdentifierArgument::class
    ];

    const ERROR_TEMPLATE = <<<EOT
 <error>%s</error>
EOT;

    const ENDPOINT_ADDED_MESSAGE = <<<IRM

<info>Endpoint %s has been added to API %s.</info>.

IRM;


    protected static $defaultName = self::DEFAULT_COMMAND_NAME;

    public function __construct(
        ApiManagerInterface $apiManager,
        ApiRepository $apiRepository
    )
    {
        parent::__construct();
        $this->apiManager = $apiManager;
        $this->apiRepository = $apiRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = [];
        try {
            $identifier = $input->getArgument(IdentifierArgument::NAME);
            $endpointName = $input->getArgument(EndPointArgument::NAME);

            // get API from repository
            $api = $this->apiRepository->findOneBy(
                ['identifier' => $identifier]
            );
            if ($api === null) {
                throw new EntityNotFoundException(
                    sprintf('Could not find API by identifier %s!', $identifier)
                );
            }
            // add endpoint
            $endpoint = new Endpoint();
            $endpoint->setName($endpointName);
            $api->addEndpoint($endpoint);

            $this->apiRepository->update($api);

            // update API in repository
            $messages[] = sprintf(
                static::ENDPOINT_ADDED_MESSAGE,
                $endpointName, $identifier
            );
        } catch (\Exception $exception) {
            $messages[] = sprintf(static::ERROR_TEMPLATE, $exception->getMessage());
        }

        $output->writeln($messages);
    }

    /**
     * @param InputInterface $input
     * @return ApiDemand
     */
    protected function createDemandFromInput(InputInterface $input): ApiDemand
    {
        $settings = $this->getSettingsFromInput($input);

        return ApiDemandFactory::fromSettings($settings);
    }
}
