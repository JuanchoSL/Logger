# Logger

## Description

Little adapter to save log data using PSR3

## How to use

```
$logger = new JuanchoSL\Logger\Logger(PATH, 'error.log');
$logger->error("This is a message error");
```

or

```
use JuanchoSL\Logger\Debugger;

$logger = new JuanchoSL\Logger\Logger(PATH, 'error.log');
Debugger::init($logger);

Debugger::error("This is a message error");
```