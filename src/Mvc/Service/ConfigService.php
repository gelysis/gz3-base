<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Service
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Service;

use Gz3Base\Mvc\Exception\PropertyNotSetException;


class ConfigService extends AbstractService
{
    /** @var array|null $this->config */
    protected $config = [];


    /**
     * @param array $config
     * @return ConfigService $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return array $configDetails
     */
    public function getConfig() : array
    {
        if (is_null($this->config)) {
            throw new PropertyNotSetException('Config array not set.');
            $configDetails = [];
        }else{
            $configDetails = $this->config;
        }

        return $configDetails;
    }

    /**
     * @param string $area
     * @return array $configArea
     */
    public function getConfigArea(string $area) : array
    {
        $config = $this->getConfig();

        if (array_key_exists($area, $config)) {
            $area = $config[$area];
        }else{
            $area = [];
        }

        return $area;
    }

    public function isServiceShared(string $service) : bool
    {
        return $isShared;
    }

}
