<?php

namespace JuanchoSL\EnvVars\Tests;

use JuanchoSL\Logger\Debugger;
use JuanchoSL\Logger\Logger;
use PHPUnit\Framework\TestCase;

class ReadFileTest extends TestCase
{

    public function testLoggerFile()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $log_name = 'test_logger.log';
        $logger = new Logger($logs_dir, $log_name, false);
        $logger->warning('This is a warning');
        $this->assertDirectoryExists($logs_dir);

        $deleted = unlink(implode(DIRECTORY_SEPARATOR, [$logs_dir, $log_name]));
        $this->assertTrue($deleted);
    }
    public function testDebuggerFile()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $log_name = 'test_debugger.log';
        $logger = new Logger($logs_dir, $log_name, false);

        Debugger::init($logger);
        Debugger::warning('This is a warning');
        $this->assertDirectoryExists($logs_dir);

        $deleted = unlink(implode(DIRECTORY_SEPARATOR, [$logs_dir, $log_name]));
        $this->assertTrue($deleted);
    }

}