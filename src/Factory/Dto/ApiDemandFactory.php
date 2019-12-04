<?php

namespace DWenzel\DataCollector\Factory\Dto;

use DWenzel\DataCollector\Configuration\Argument\ApiIdentifierArgument;
use DWenzel\DataCollector\Configuration\Argument\ApiNameArgument;
use DWenzel\DataCollector\Configuration\Argument\VendorArgument;
use DWenzel\DataCollector\Configuration\Argument\VersionArgument;
use DWenzel\DataCollector\Configuration\Option\IdentifierOption;
use DWenzel\DataCollector\Entity\Dto\ApiDemand;
use DWenzel\DataCollector\SettingsInterface as SI;
use InvalidArgumentException;

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
 * Class ApiDemandFactory
 */
class ApiDemandFactory
{
    public const EXCEPTION_MESSAGE_DUPLICATE_SETTING = 'Invalid settings. The keys "%s" and "%s" cannot be used together since they set the same value "%s"';

    /**
     * @param array $settings
     * @return ApiDemand
     * @throws InvalidArgumentException
     */
    public static function fromSettings(array $settings): ApiDemand
    {
        // we cannot not use those two keys together
        if (!empty($settings[ApiIdentifierArgument::NAME])
            && isset($settings[IdentifierOption::NAME])) {
            throw new InvalidArgumentException(
                sprintf(
                    static::EXCEPTION_MESSAGE_DUPLICATE_SETTING,
                    ApiIdentifierArgument::NAME,
                    IdentifierOption::NAME,
                    SI::IDENTIFIER_KEY
                ),
                1575364190
            );
        }

        $demand = new ApiDemand();
        if (!empty($settings[ApiNameArgument::NAME])) {
            $demand->setName($settings[ApiNameArgument::NAME]);
        }
        if (!empty($settings[IdentifierOption::NAME])) {
            $demand->setIdentifier($settings[IdentifierOption::NAME]);
        }
        if (!empty($settings[ApiIdentifierArgument::NAME])) {
            $demand->setIdentifier($settings[ApiIdentifierArgument::NAME]);
        }
        if (!empty($settings[VersionArgument::NAME])) {
            $demand->setVersion($settings[VersionArgument::NAME]);
        }
        if (!empty($settings[VendorArgument::NAME])) {
            $demand->setVendor($settings[VendorArgument::NAME]);
        }

        return $demand;
    }
}
