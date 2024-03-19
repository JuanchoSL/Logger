# Logger

## Description

Little adapter to save log data using PSR3

## How to use

### First steps

#### Create or use provided Data Composers, you can convert to String (for save into files) or can convert to Array or Objects in order to save into tables
```
$composer = new PlainText.php;
```
#### Use a repository in order to put the composer
```
$repository = new FileRepository(PATH . DIRECTORY_SEPARATOR . 'error.log');
$repository->setComposer($composer);
```

### Declare a Logger directly
```
$logger = new JuanchoSL\Logger\Logger($repository);
$logger->error("This is a message error");
```

### Declare a Logger using the provided Debugger class
```
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::getInstance();
$debugger->setLogger('errors', $repository);

//.... your code ...

Debugger::getInstance()->getLogger('errors)->error("This is a message error");
```

### Declaring few Loggers in order to save separated data
```
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::getInstance(PATH);
$debugger->setLogger('errors', $repository);
$debugger->setLogger('database', (new ModelRepository())->setComposer(new ObjectComposer));

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

### Use declared Loggers for inject as dependecy into Libraries
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
