<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Entity
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Entity;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Exception\ClassNotFoundException;
use Gz3Base\Mvc\Model\ModelInterface;
use Gz3Base\Mvc\Manager\AbstractManager;
use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Record\Service\RecordService;


abstract class AbstractEntity extends AbstractService implements ModelInterface
{

    /** @var AbstractActionController self::$controller */
    /** @var string self::$routeParameters */

    /** @var AbstractManager $this->manager */
    protected $manager = null;
    /** @var array $this->attributes */
    protected $attributes = [];


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
        return strtolower($this->getReflectionClass()->getShortName());
    }

    /**
     * @param BaseManager $manager
     * @return AbstractEntity $this
     */
    protected function getManager() : AbstractEntity
    {
        if (is_null($this->manager)) {
            $namespace = str_replace('\\Model\\', '\\Manager\\', $this->getNamespace());
            $entityType = ucfirst($this->getEntityType());
            $managerClass = $namespace.$entityType.'Manager';

            if (class_exists($managerClass)) {
                $priority = RecordService::DEBUG;
                $message = $entityType.' manager exists.';

            }else{
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

            }else{
                $this->record('gma_err', $priority, $message);
            }
        }

        return $this->manager;
    }

    /**
     * Derived method to catches calls to undefined methods.
     * @param string $methodName
     * @param array $parameters
     * @return mixed $returnValue
     */
    public function __call(string $methodName, array $parameters)
    {
        if (strpos($methodName, 'get') === 0 && count($parameters) == 0) {
            $returnValue = $this->get($this->getAttributeCodeFromMethodName($methodName));

        }elseif (strpos($methodName, 'set') === 0 && count($parameters) == 1) {
            $value = current($parameters);
            $returnValue = $this->set($this->getAttributeCodeFromMethodName($methodName), $value);

        }

        if (!isset($returnValue) || strlen($returnValue) == 0) {
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
    protected function setAttribute(string $attributeCode, $value) : AbstractEntity
    {
        $method = $this->getSetter($attributeCode);

        if (method_exists($this, $method)) {
            $entity = $this->$method($value);

        }elseif (property_exists($this, $code)) {
            $value = $this->$code;
            $message = 'Attribute '.$code.' exists as property but has no getter on entity '.$this->getEntityType().'.';
            $this->record('get_nmtd', RecordService::NOTICE, $message);

        }else{
            $this->$attributeCode = $value;
            $entity = $this;
            $message = 'Attribute '.$attributeCode.' has no setter on entity '.$this->getEntityType().'.';
            $this->record('set_nodf', RecordService::WARN, $message);
        }

        return $entity;
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
     * @return array $attributes
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param bool $returnTimestamp
     * @return string|int $createdAt
     */
    public function getCreatedAt(bool $returnDate = true)
    {
        return $this->returnDateTimeOrTimestamp($this->attributes['created_at'], $returnDate);
    }

    /**
     * @param array $attributeData
     * @return AbstractEntity $createModel
     */
    public function create(array $data) : AbstractEntity
    {
        $data['created_at'] = time(); // Done on the database - necessary here?
        $this->setAttributes($data);

        return $this;
    }

    /**
     * @param int $id
     * @return AbstractEntity $readModel
     */
    public function read(int $id = null) : AbstractEntity
    {
        $id = isset($id) ?? $this->get('id');

        if (! is_null($id)) {
            $this->setAttributes($this->getManager()
                ->readBy('id', $id));

        }elseif (count($this->getAttributes()) > 0) {
            $this->record('rd_fai', RecordService::ERR, 'Tried to read an entity wthout id.', [
                'entity'=>$this
            ]);

        }else{
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
        if (is_null($value)) {
            $message = 'Passed forbitten value '.var_export($value, true).' on "'.$attributeCode.'".';
            $this->record('get_nmtd', RecordService::NOTICE, $message);

        }else{
            $this->setAttribute($attributeCode, $value);
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
     * @param array $attributesDataArray
     * @return AbstractEntity $this
     * @throws \AttributeNotFoundException
     */
    public function setAttributes(array $data) : AbstractEntity
    {
        $attributes = $data;
        if (count($attributes) > 0) {
            foreach ($attributes as $code=>$value) {
                $this->set($code, $value);
            }

        }else{
            $priority = RecordService::DEBUG;
            $message = 'No new attributes have been passed.';
            $this->record('set_nnew', RecordService::NOTICE, $message);
        }

        return $this;
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

        }else{
            $message = 'Attribute '.$code.' has no getter on '.$this->getEntityType().' entity.';
            $this->record('get_nmth', RecordService::NOTICE, $message);
            $value = $this->getAttribute($code, true);
        }

        return $value;
    }

    /**
     * @return bool $isActive
     */
    public function isActive() : bool
    {
        return true;
    }

    /**
     * @param string $dateTime
     * @param bool $returnDateTime
     * @return string|int $dateOrTimestamp
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

        }else{
            $method = '';
        }

        return $method;
    }

}
