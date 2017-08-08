GZ3BASE
=======

# CHANGELOG

### 0.9.10 (2017-08-09)
* Added phpunit.xml
* Tweaked application config and phpunit bootstrap
* Replaced class property with service manager getter

### 0.9.9 (2017-08-03)
Started with test implementation, made tweaks and fixes: 
* Changed name of src/Mvc/Exception/BaseException to Gz3Exception and src/Test/PhpUnit/TestCase to Gz3TestCase
* Added getServiceManager() method to Gz3TestCase and setServiceManager() method to TestInitialiser
* Tweaked .gitignore
* Added ActionControllerTest and ActionController
* Removed register namespache for Gz3Base\\Test
* Tweaked TestInitialiser: new set method, type hinting, ...
* Added init_autoloader.php
* Extended requirements, updated and cleaned up composer.json

### 0.9.8 (2017-07-06)
* Tweaked deprecation of \Gz3Base\Record\RecordableTrait::setReflectionClass() method
* phpDoc and format tweaks
* Fixed and adjusted RecordService handling especially on the exception classes

### 0.9.7 (2017-06-24)
* Equalised logging approach on init and deinit

### 0.9.6 (2017-06-17)
* Added functionality to the abstract entity class
* Overhaul/cleanup

### 0.9.5 (2017-06-11)
* Minor tweaks
* phpDoc cleanup

### 0.9.4 (2017-06-10)
* phpDoc cleanup
* Minor log tweaks
* Restructured \Gz3Base\Test

### 0.9.3 (2016-12-18)
* Few bug fixes
* Added \Gz3Base\Record\Service\NoopRecordService

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
