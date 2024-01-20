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
        sleep(1);
    }
    public function testDebuggerFile()
    {
        $logs_dir = Debugger::initPaths();
        $this->assertFileNotExists($logs_dir . DIRECTORY_SEPARATOR . 'access.log');
        Debugger::info('This is a info', $_SERVER);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($logs_dir . DIRECTORY_SEPARATOR . 'access.log');

        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }

    public function testTriggersDebugInit()
    {
        Debugger::initPaths();
        $logs_dir = Debugger::initErrorHandler();
        $this->assertFileNotExists($logs_dir . DIRECTORY_SEPARATOR . 'debug.log');
        trigger_error("This is a trigger");
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($logs_dir . DIRECTORY_SEPARATOR . 'debug.log');
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
    public function testTriggerErrorInit()
    {
        Debugger::initPaths();
        $logs_dir = Debugger::initErrorHandler();
        $this->assertFileNotExists($logs_dir . DIRECTORY_SEPARATOR . 'errors.log');
        trigger_error("This is a trigger", E_USER_ERROR);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($logs_dir . DIRECTORY_SEPARATOR . 'errors.log');
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
}