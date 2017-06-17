<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Entity
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Entity;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Exception\ClassNotFoundException;
use Gz3Base\Mvc\Model\ModelInterface;
use Gz3Base\Mvc\Manager\AbstractManager;
use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Mvc\Exception\ClassNotFoundException;
use Gz3Base\Mvc\Exception\PropertyNotSetException;


abstract class AbstractEntity extends AbstractService
    implements ModelInterface
{

    /** @var AbstractActionController self::$controller */
    /** @var string self::$routeParameters */

    /** @var AbstractManager $this->manager */
    protected $manager = null;
    /** @var array $this->attributes */
    protected $attributes = [];
    /** @var array $this->modifiedAttributes() */
    protected $modifiedAttributes = [];


    /**
     * @return string $entityType
     */
    public function getEntityType() : string
    {
        return strtolower($this->getShortClassname());
    }

    /**
     * @return string $serviceKey
     */
    public function getServiceKey() : string
    {
        return strtolower($this->getReflectionClass->getShortName());
    }

    /**
     * @return AbstractManager $this->manager
     */
    protected function getManager() : AbstractManager
    {
        if (is_null($this->manager)) {
            $namespace = str_replace('\\Model\\', '\\Manager\\', $this->getNamespace());
            $entityType = ucfirst($this->getEntityType());
            $managerClass = $namespace.$entityType.'Manager';

            if (class_exists($managerClass)) {
                $priority = RecordService::DEBUG;
                $message = $entityType.' manager exists.';
            }else {
                $managerClass = $namespace.'Manager';
                $priority = RecordService::CRIT;
                $message = $entityType.' manager does not exist. Used fallback.';
            }

            try {
                $manager = new $managerClass();
                $manager->initialise(self::$controller);
            }catch (\Exception $exception) {
                $manager = null;
                $priority = RecordService::ALERT;
                $message .= ' Manager initialisation failed.';
            }

            if ($manager instanceof Manager) {
                $this->manager = $manager;
            }else {
                $this->record('gma_err', $priority, $message);
            }
        }

        return $this->manager;
    }

    /**
     * Derived method to catch calls to undefined methods.
     * @param string $methodName
     * @param array $parameters
     * @return mixed $returnValue
     */
    public function __call(string $methodName, array $parameters)
    {
        if (strpos($methodName, 'set') === 0 && is_array($parameters) && count($parameters) == 1) {
            $value = current($parameters);
            $returnValue = $this->set($this->getAttributeCodeFromMethodName($methodName), $value);

        }elseif (strpos($methodName, 'get') === 0 && count($parameters) == 0) {
            $returnValue = $this->get($this->getAttributeCodeFromMethodName($methodName));

        }elseif (strpos($methodName, 'has') === 0 && count($parameters) == 0) {
            $returnValue = $this->has($this->getAttributeCodeFromMethodName($methodName));

        }else {
            throw new \BadMethodCallException(sprintf('Called undefined method: %s.', $methodName));
            $returnValue = null;
        }

        return $returnValue;
    }

    /**
     * @param string $attributeCode
     * @param mixed $attributeValue
     * @return AbstractEntity $this
     */
    protected function setAttribute(string $code, $value) : AbstractEntity
    {
        $method = $this->getSetter($code);

        if (method_exists($this, $method)) {
            $this->$method($value);

        }elseif (property_exists($this, $code)) {
            $this->$code = $value;
            $message = 'Attribute '.$code.' exists as property but has no getter on entity '.$this->getEntityType().'.';
            $this->record('get_nmtd', RecordService::NOTICE, $message);

        }else{
            $this->attributes[$code] = $value;
            $message = 'Attribute '.$code.' has no setter on entity '.$this->getEntityType().'.';
            $this->record('set_nodf', RecordService::WARN, $message);
        }


        return ($value == $this->getAttribute($code));
    }

    /**
     * @param string $attributeCode
     * @return mixed $attributeValue
     */
    protected function getAttribute(string $code)
    {
        if (property_exists($this, $code)) {
            $value = $this->$code;

        }elseif (array_key_exists($code, $this->attributes)) {
            $value = $this->attributes[$code];
            $message = 'Attribute '.$code.' exists in attributes but has no property set but exists in attributes.';
            $this->record('get_npty', RecordService::NOTICE, $message);

        }elseif (array_key_exists($code, $this->attributesStatus)) {
            $value = null;
            $message = 'Attribute '.$code.' exists only in attributeStatus.';
            $this->record('get_nval', RecordService::WARN, $message);

        }else{
            $message = 'Attribute '.$code.' does not exist on entity '.$this->getEntityType().'.';
            throw new EntityException($message, static::RECORD_ID_PREFIX.'get_nodf');
            $value = null;
        }

        return $value;
    }

    /**
     * @param array $attributesDataArray
     * @return AbstractEntity $this
     * @throws \AttributeNotFoundException
     */
    public function setAttributes(array $attributes) : AbstractEntity
    {
        $id = $this->getAbbreviatedMethodName();

        $attributesToSet = count($attributes);
        $attributesSet = 0;
        $attributesNotSet = [];

        if ($attributesToSet > 0) {
            foreach ($attributes as $attributeCode=>$value) {
                $isSet = $this->setAttribute($attributeCode, $value);
                if ($isSet) {
                    ++$attributesSet;
                }else {
                    $attributesNotSet[$attributeCode] = $value;
                }
            }

            $message = $attributesToSet.' new attributes have been passed ';
            if ($attributesSet == 0) {
                $id .= '_non';
                $priority = RecordService::ERROR;
                $message .= 'but none have been set.';
                $logData = ['attributes'=>$attributes];
            }elseif ($attributesSet < $attributesToSet) {
                $id .= '_par';
                $priority = RecordService::ERROR;
                $message .= 'but only '.($attributesToSet - $attributesSet).' have been set.';
                $logData = ['attributes'=>$attributes, 'attributesNotSet'=>$attributesNotSet];
            }else {
                $priority = RecordService::INFO;
                $message .= 'and set.';
                $logData = [];
            }
        }else {
            $id .= 'npa';
            $priority = RecordService::DEBUG;
            $message = 'No new attributes have been passed.';
            $logData = [];
        }
        $this->record($id, $priority, $message, $logData);

        return $this;
    }

    /**
     * @return array $this->attributes
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param bool|true $returnTimestamp
     * @return string|int $createdAt
     */
    public function getCreatedAt(bool $returnDate = true)
    {
        return $this->returnDateTimeOrTimestamp($this->attributes['created_at'], $returnDate);
    }

    /**
     * @param array $attributeData
     * @return AbstractEntity $modelCreated
     */
    public function create(array $data) : AbstractEntity
    {
        /** @todo: Check if necessary as it is done already on the database */
        $data['created_at'] = time();
        $this->setAttributes($data);

        return $this;
    }

    /**
     * @param int $id  Excepted values > 0
     * @return AbstractEntity $modelRead
     */
    public function read(int $id = 0) : AbstractEntity
    {
        $id = (($id > 0) ? $id : $this->get('id'));

        if (is_int($id) && $id > 0) {
            $this->setAttributes($this->getManager()
                ->readBy('id', $id));
        }elseif (count($this->getAttributes()) > 0) {
            $this->record('rd_fai', RecordService::ERR, 'Tried to read an entity wthout id.', ['entity'=>$this]);
        }else {
            throw new ClassNotFoundException('Tried to read empty entity without id.');
        }

        return $this;
    }

    /**
     * @return bool $success
     */
    public function update() : bool
    {
        return $this->getManager()->update();
    }

    /**
     * @return bool $isDeleted
     */
    public function delete() : bool
    {
        return $this->deactivate();
    }

    /**
     * @return bool $isDeactivated
     */
    public function deactivate() : bool
    {
        /** @var Entity $entity */
        $entity = $this->setActive(0);

        return $entity->isActive();
    }

    /**
     * @return bool $isDeactivated
     */
    public function activate() : bool
    {
        if ($this->reactivateEntitiesEnabled()) {
            /** @var AbstractEntity $entity */
            $entity = $this->setActive(1);
        }else {
            $entity = $this;
        }

        return $entity->isActive();
    }

    /**
     * @param int|time() $timestamp
     * @return string $dateTime
     */
    protected function getDateTime($timestamp = time())
    {
        return strftime('%F %T', $timestamp);
    }

    /**
     * @return bool $isModified
     */
    public function isModified() : bool
    {
        return (count($this->modifiedAttributes) > 0);
    }

    /**
     * @param string $attributeCode
     * @param mixed $attributeValue
     * @return AbstractEntity $this
     */
    public function set(string $attributeCode, $value) : AbstractEntity
    {
        $method = $this->getSetter($attributeCode);

        if (is_null($value)) {
            $entity = $this;
            $message = 'Passed forbitten value '.var_export($value, true).' on "'.$attributeCode.'".';
            $this->record('get_ivd', RecordService::NOTICE, $message);

        }elseif (method_exists($this, $method)) {
            $entity = $this->$method($value);
        }elseif (property_exists($this, $code)) {
            $this->$code = $value;
            $entity = $this;
            $message = 'Attribute '.$code.' exists as property but has no setter on entity '.$this->getEntityType().'.';
            $this->record('set_pro', RecordService::NOTICE, $message);
        }else {
            $success = $this->setAttribute($attributeCode, $value);
            $entity = $this;
            $message = 'Attribute '.$attributeCode.' has no setter on entity '.$this->getEntityType().'.';
            $this->record('set_att', RecordService::WARN, $message);
        }

        return $entity;
    }

    /**
     * @param string $attributeCode
     * @return AbstractEntity $this
     */
    public function unset(string $attributeCode) : AbstractEntity
    {
        $this->setAttribute($attributeCode, null);

        return $entity;
    }

    /**
     * @param string $attributeCode
     * @return mixed $attributeValue
     */
    public function get(string $code)
    {
        $method = $this->getGetter($code);

        if (method_exists($this, $method)) {
            $value = $this->$method();
        }elseif (property_exists($this, $code)) {
            $value = $this->$code;
            $message = 'Attribute '.$code.' exists as property but has no getter on entity '.$this->getEntityType().'.';
            $this->record('get_pro', RecordService::NOTICE, $message);
        }elseif (array_key_exists($code, $this->attributes)) {
            $value = $this->$code;
            $message = 'Attribute '.$code.' exists in attributes but has no getter on entity '.$this->getEntityType().'.';
            $this->record('get_att', RecordService::NOTICE, $message);
        }elseif (array_key_exists($code, $this->attributesStatus)) {
            $value = null;
            $message = 'Attribute '.$code.' exists in attributeStatus but has no neither a value in attributes nor'.' a getter on entity '.$this->getEntityType().'.';
            $this->record('get_nvl', RecordService::WARN, $message);
        }else {
            $message = 'Attribute '.$code.' does not exist on entity '.$this->getEntityType().'.';
            throw new PropertyNotSetException($message, $this->getRecordIdPrefix().'get_nex');
            $value = null;
        }

        return $value;
    }

    /**
     * @param string $attributeCode
     * @return bool $hasAttribute
     */
    public function has(string $code)
    {
        try {
            $attributeValue = $this->get($code);
        }catch (PropertyNotSetException $exception) {
            $this->record($exception->getCode(), RecordService::DEBUG, $exception->getMessage());
        }
        $hasAttribute = isset($attributeValue);

        return $hasAttribute;
    }

    /**
     * @return bool $isActive
     */
    public function isActive() : bool
    {
        return (bool) $this->getActive();
    }

    /**
     * @param string $dateTime
     * @param bool $returnDateTime
     * @return unknown
     */
    public function returnDateTimeOrTimestamp(string $dateTime = null, bool $returnDateTime = true)
    {
        if (is_null($dateTime)) {
            $timestamp = time();
        }else{
            $timestamp = strtotime($dateTime);
        }

        if ($returnDateTime) {
            $dateTimeOrTimestamp = $this->getDateTime($timestamp);
        }else{
            $dateTimeOrTimestamp = $timestamp;
        }

        return $dateTimeOrTimestamp;
    }

    /**
     * @param int $isActive
     * @return AbstractEntity $this
     */
    protected function setActive(int $isActive) : AbstractActionController
    {
        if ($this->attributes['active'] != $isActive) {
            $this->attributes['active'] = $isActive;
            $this->modifiedAttributes['active'] = true;
        }

        return $this;
    }

    /**
     * @return int $isActive
     */
    protected function getActive() : int
    {
        return $this->attributes['active'];
    }

    /**
     * @param string $attributeCode
     * @return string $setterMethod
     */
    protected function getSetter(string $attributeCode) : string
    {
        return $this->getMethodFromAttributeCode('set', $attributeCode);
    }

    /**
     * @param string $attributeCode
     * @return string $getterMethod
     */
    protected function getGetter(string $attributeCode) : string
    {
        return $this->getMethodFromAttributeCode('get', $attributeCode);
    }

    /**
     * @param string $methodName
     * @return bool $isSetterOrGetter
     */
    protected function isSetterOrGetter(string $methodName)
    {
        $isSetterOrGetter = (substr($methodName, 0, 3) === 'set' || substr($methodName, 0, 3) === 'get');

        return $isSetterOrGetter;
    }

    /**
     * @param string $methodName
     * @return string $attributeCode
     */
    protected function getAttributeCodeFromMethodName(string $methodName) : string
    {
        if ($this->isSetterOrGetter($methodName)) {
            $attributeCode = strtolower(trim(preg_replace('#([A-Z])#', '_$1', substr($methodName, 3)), '_'));
        }else{
            $attributeCode = '';
        }

        return $attributeCode;
    }

    /**
     * @param string $methodType
     * @param string $attributeCode
     * @return string $method
     */
    protected function getMethodFromAttributeCode(string $methodType, string $attributeCode) : string
    {
        if ($this->isSetterOrGetter($methodType)) {
            $method = $methodType.str_replace(' ', '', ucwords(str_replace('_', ' ', $attributeCode)));
        }else {
            $method = '';
        }

        return $method;
    }

}
