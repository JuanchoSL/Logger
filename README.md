# Logger

## Description

Little adapter to save log data using PSR3. 
This library have the availability of put the data log into distincts repositories or with different format. We can change the destination of the data, or his format,
only changing the injected dependency.

We can create distincts log for differents cases, save it with an alias into the Debugger instance and call or inject any one into a any lib. In example, we can create a log 
for database queries, other for debug, other for errors...

## Installation

Use composer in order to install it
```shell
composer require juanchosl/logger
composer update
```

## How to use

### First steps

#### Create or use provided Data Composers, 
Using composers, you can create and design your own messages structure, you can convert to String (for save into files) or can convert to Array or Objects in order to save into tables. 

```php
$composer = new TextComposer;
```

#### Create or use a provided repository 
Is the message destination, you can use the same Composer for send data to few Repositories, mantaining the same structure for all, create an instance in order to put the composer

```php
$repository = new FileRepository(PATH . DIRECTORY_SEPARATOR . 'error.log');
$repository->setComposer($composer);
```

### Logger

#### Declare a Logger directly

```php
$logger = new JuanchoSL\Logger\Logger($repository);
$logger->error("This is a message error");
```

#### Declare a Logger using the provided Debugger class

```php
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::init();
$debugger->setLogger('errors', $repository);

//.... your code ...

Debugger::get('errors')->error("This is a message error");
```

#### Declaring few Loggers in order to save separated data

```php
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::init()
    ->setLogger('errors', $repository)
    ->setLogger('database', (new ModelRepository())->setComposer(new ObjectComposer));

//.... your code ...

Debugger::get('errors')?->error("This is a message error");
Debugger::get('database')?->debug($sql);
```

### Error control

#### Initializing error or exception handlers

```php
use JuanchoSL\Logger\Debugger;

$debugger = Debugger::init()->setLogger('errors', $repository)->initFailuresHandler('errors', E_ALL^E_USER_NOTICE);
```

### Use declared Loggers for inject as dependecy into Libraries

```php
use JuanchoSL\Logger\Debugger;
use JuanchoSL\Orm\Engine\Drivers\Mysqli;

$debugger = Debugger::init()
    ->setLogger('errors', $repository)
    ->setLogger('database', (new ModelRepository())->setComposer(new ObjectComposer))
    ->initFailuresHandler('errors', E_ALL^E_USER_NOTICE);

$database = new Mysqli($credentials);
$database->setLogger($debugger->getLogger('database'));
```
