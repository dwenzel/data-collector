<?php
declare(strict_types=1);

namespace DWenzel\DataCollector\Controller;

use DWenzel\DataCollector\Service\Http\ApiServiceInterface;
use DWenzel\DataCollector\Service\Persistence\StorageServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
 * Class CollectorController
 *
 * collects data using an ApiService and
 * pushes them to a storage
 */
class CollectorController extends AbstractController
{
    /**
     * @var ApiServiceInterface
     */
    private $apiService;

    /**
     * @var StorageServiceInterface
     */
    private $storageService;

    public function __construct(ApiServiceInterface $apiService, StorageServiceInterface $storageService)
    {
        $this->apiService = $apiService;
        $this->storageService = $storageService;
    }


}
