<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Service
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Service;

use Gz3Base\Mvc\Exception\BaseException;
use Gz3Base\Mvc\Exception\PropertyNotSetException;
use Gz3Base\Mvc\Exception\WrongTypeException;


class ConfigService extends AbstractService
{

    /** @var array|null $this->configuration */
    protected $configuration = [];


    /**
     * @param array $configuration
     * @return ConfigService $this
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @param array|null $configuration
     * @return array $configurationDetails
     */
    public function getConfiguration(string $area = null) : array
    {
        if (is_null($this->configuration)) {
            throw new PropertyNotSetException('Configuration array not set.');
            $configurationDetails = [];
        }elseif (is_null($area)) {
            $configurationDetails = $this->configuration;
        }elseif (array_key_exists($area, $this->configuration)) {
            $configurationDetails = $this->configuration[$area];
        }else {
            $this->record('wrg_area', RecordService::ERROR, 'Configuration area '.$area.' is not existing.', [
                'config keys'=>array_keys($this->configuration)
            ]);
            $configurationDetails = [];
        }

        return $configurationDetails;
    }

    /**
     * @param string $package
     * @throws WrongTypeException
     * @throws PropertyNotSetException
     * @throws BaseException
     * @return array $dbDetails
     */
    public function getDbDetails(string $database) : array
    {
        $recordId = 'gdb';

        $fallback = false;
        $dbDetails = null;
        $dbConfiguration = $this->getConfiguration('db');

        $hasSpecificDatabaseDetails = isset($dbConfiguration[$database]) && is_array($dbConfiguration[$database]);

        if (strlen($database) > 0) {
            if (isset($dbConfiguration[$database]) && is_array($dbConfiguration[$database])) {
                $dbDetails = $dbConfiguration[$database];
            }else {
                $fallback = true;
            }
        }

        $hasDefaultDatabaseDetails = array_key_exists('default', $dbConfiguration);

        if (is_null($dbDetails) && $hasDefaultDatabaseDetails) {
            $defaultDatabase = $dbConfiguration['default'];
            if (! is_string($defaultDatabase)) {
                throw new WrongTypeException('Database default connection is not a string.');
            }elseif (! array_key_exists($defaultDatabase, $dbConfiguration)) {
                $message = 'Database configuration has no valid default ('.$defaultDatabase.') database defined.';
                throw new PropertyNotSetException($message);
            }elseif (is_array($dbConfiguration[$defaultDatabase])) {
                $dbDetails = $dbConfiguration[$defaultDatabase];
            }
        }

        if (is_null($dbDetails)) {
            if (count($this->configuration) == 0) {
                $message = 'Configuration has not been set.';
            }elseif ($fallback) {
                $message = 'Neither default nor database key `'.$database.'` exists.';
            }else {
                $message = 'Default database key does not exst.';
            }
            $message .= ' Exception thrown in '.get_called_class().'.';
            throw new BaseException($message);
            $dbDetails = [];
        }elseif ($fallback) {
            $recordId .= '_fbk';
            $priority = RecordService::ERROR;
            $message = 'Used default key due to specific database details not existing.';
            $data = [
                'default'=>$defaultDatabase,
                'database'=>$database
            ];
        }else {
            $priority = RecordService::DEBUG;
            $message = 'Retrieved database details.';
            $data = [
                'database'=>$database
            ];
        }
        $this->record($recordId, $priority, $message, $data);

        return $dbDetails;
    }

    /**
     * @return array $configurationArea
     */
    protected function getConfigArea(string $area) : array
    {
        if (array_key_exists($area, $this->configuration)) {
            $area = $this->configuration[$area];
        }else {
            $area = [];
        }

        return $area;
    }

}
