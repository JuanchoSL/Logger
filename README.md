# Logger

## Description

Little adapter to save log data using PSR3

## How to use

```
$logger = new JuanchoSL\Logger\Logger(PATH . DIRECTORY_SEPARATOR . 'error.log');
$logger->error("This is a message error");
```

or

```
use JuanchoSL\Logger\Debugger;
Debugger::init(PATH);
Debugger::error("This is a message error");
```