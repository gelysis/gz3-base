<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\config
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

$serviceConfig = [
    'service_manager'=>[
        'initializers'=>[
            'Gz3Base\ServiceManager\Initialiser'
        ],
        'invokables'=>[
            'Service\Config'=>'Gz3Base\Mvc\Service\ConfigService',
            'Service\Record'=>'Gz3Base\Record\Service\RecordService',
            'Manager\Entity'=>'Gz3Base\Mvc\Manager\EntityManager'
        ],
        'shared'=>[
            'Service\Config'=>true,
            'Service\Record'=>true,
            'Manager\Entity'=>true
        ]
    ]
];

return $serviceConfig;
