GZ3BASE
=======

# CHANGELOG

### 0.9.3 (2016-12-18)
* Few bug fixes
* Added \Gz3Base\Record\Service\NoopRecordService

# CHANGELOG

### 0.9.2 (2016-12-10)

Minor tweaks of the AbstractEntity class

* Renamed returnDateOrTimestamp() to returnDateTimeOrTimestamp()
* Added getDateTime(), isSetterOrGetter(), getAttribute()
* Removed activate(), deactivate(), getActive(), setActive() as being too specific
* Corrected get() attribute retrieval,
* Tweaked __call() method 

### 0.9.1 (2016-11-10)

Minor tweaks of the AbstractEntity class and the RecordableTrait trait.

* Improved getManager() method by implementing getNamespace() method

### 0.9.0 (2016-11-09)

Initial commit containing the following files:

* CHANGELOG.md
* LICENSE.md,
* Module.php,
* README.md,
* composer.json,
* config/module.config.php,
* config/module.service.php,
* src/AbstractModule.php,
* src/Mvc/Controller/AbstractActionController.php,
* src/Mvc/Controller/NoopController.php,
* src/Mvc/Entity/AbstractEntity.php,
* src/Mvc/Entity/NoopEntity.php,
* src/Mvc/Exception/ActionException,
* src/Mvc/Exception/BadEntityCallException,
* src/Mvc/Exception/BadMethodCallException,
* src/Mvc/Exception/BaseException,
* src/Mvc/Exception/ConfigException.php,
* src/Mvc/Exception/DomainException,
* src/Mvc/Exception/FileException,
* src/Mvc/Exception/InvalidArgumentEception,
* src/Mvc/Exception/InvalidControllerException,
* src/Mvc/Exception/InvalidPluginException,
* src/Mvc/Exception/MappingException,
* src/Mvc/Exception/MissingLocatorException,
* src/Mvc/Exception/PersistanceException,
* src/Mvc/Exception/PropertyNotSetException,
* src/Mvc/Exception/RuntimeException,
* src/Mvc/Exception/ServiceNotFoundException,
* src/Mvc/Exception/WrongTypeException,
* src/Mvc/Manager/AbstractManager.php,
* src/Mvc/Manager/ManagerInterface.php,
* src/Mvc/Manager/NoopManager.php,
* src/Mvc/Model/ModelInterface.php,
* src/Mvc/Service/AbstractService.php,
* src/Mvc/Service/ConfigService.php,
* src/Mvc/Service/NoopService.php,
* src/Mvc/Service/ServiceInterface.php,
* src/Mvc/Service/ServiceTrait.php,
* src/Record/Filter/Priorities.php,
* src/Record/Formatter/FormatterInterface.php,
* src/Record/Formatter/FormatterTrait.php,
* src/Record/RecordableInterface.php,
* src/Record/RecordableTrait.php,
* src/Record/Service/RecordService.php,
* src/Record/Writer/RabbitMq.php,
* src/ServiceManager/Initialiser.php,
* src/Test/PhpUnit/Controller/ConsoleControllerTestCase.php,
* src/Test/PhpUnit/Controller/ControllerTestCaseTrait.php,
* src/Test/PhpUnit/Controller/HttpControllerTestCase.php,
* src/Test/PhpUnit/TestCase.php,
* src/Test/PhpUnit/TestInitialiser.php,
* src/Test/PhpUnit/bootstrap.php
