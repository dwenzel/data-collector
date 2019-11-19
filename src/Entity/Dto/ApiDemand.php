<?php

namespace DWenzel\DataCollector\Entity\Dto;

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
 * Class ApiDemand
 */
class ApiDemand implements DemandInterface
{
    /**
     * Uuids to search for
     *
     * @var string Comma separated list of uuids
     */
    private $uuids = '';

    /**
     * @var string single uuid
     */
    private $identifier = '';

    /**
     * @var string Name
     */

    private $name = '';

    /**
     * @var string
     */
    private $vendor = '';

    /**
     * @var bool $active
     */
    private $active;

    /**
     * @var string Comma separated list of roles
     */
    private $version = '';

    /**
     * @return string
     */
    public function getUuids(): string
    {
        return $this->uuids;
    }

    /**
     * @param string $uuids
     * @return ApiDemand
     */
    public function setUuids(string $uuids): self
    {
        $this->uuids = $uuids;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ApiDemand
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return ApiDemand
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return ApiDemand
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier A single uuid
     * @return ApiDemand
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     * @return ApiDemand
     */
    public function setVendor(string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }
}
