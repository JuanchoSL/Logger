<?php

namespace JuanchoSL\EnvVars\Tests;

use JuanchoSL\Logger\Debugger;
use JuanchoSL\Logger\Logger;
use PHPUnit\Framework\TestCase;

class ReadFileTest extends TestCase
{

    public function testLoggerFile()
    {
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';
        $log_name = 'test_logger.log';
        $logger = new Logger($logs_dir . DIRECTORY_SEPARATOR . $log_name);
        $logger->warning('This is a warning');
        $this->assertDirectoryExists($logs_dir);

        $deleted = unlink(implode(DIRECTORY_SEPARATOR, [$logs_dir, $log_name]));
        $this->assertTrue($deleted);
    }
    public function testDebuggerFile()
    {
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';

        Debugger::init($logs_dir);
        Debugger::warning('This is a warning', $_SERVER);
        $this->assertDirectoryExists($logs_dir);

        foreach(glob($logs_dir.DIRECTORY_SEPARATOR.'*') as $file){
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
    }

}