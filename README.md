# Logger

## Description

Little adapter to save log data using PSR3

## How to use

### Declare a Logger directly
```
$logger = new JuanchoSL\Logger\Logger(PATH . DIRECTORY_SEPARATOR . 'error.log');
$logger->error("This is a message error");
```

### Declare a Logger using the provided Debugger class
```
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::getInstance(PATH);
$debugger->setLogger('errors');

//.... your code ...

Debugger::getInstance()->getLogger('errors)->error("This is a message error");
```

### Declaring few Logger in order to save separated data
```
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::getInstance(PATH);
$debugger->setLogger('errors');
$debugger->setLogger('database');

//.... your code ...

Debugger::getInstance()->getLogger('errors)->error("This is a message error");
Debugger::getInstance()->getLogger('database)->debug($sql);
```

### Initializing error or exception handlers
```
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::getInstance(PATH);
$debugger->initErrorHandler('errors', E_ALL^E_USER_NOTICE);
$debugger->initExceptionHandler('errors');
```

### Use declared Loggers fon inject as dependecy into Libraries
```
use JuanchoSL\Logger\Debugger;
use JuanchoSL\Orm\engine\Drivers\Mysqli;

$debugger = Debugger::getInstance(PATH);
$debugger->initErrorHandler('errors', E_ALL^E_USER_NOTICE);
$debugger->initExceptionHandler('errors');
$debugger->setLogger('database');

$database = new Mysqli($credentials);
$database->setLogger($debugger->getLogger('database'));
```
