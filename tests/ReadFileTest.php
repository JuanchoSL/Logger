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
        $full_path = implode(DIRECTORY_SEPARATOR, [$logs_dir, $log_name]);
        $this->assertDirectoryDoesNotExist($logs_dir);
        $this->assertFileDoesNotExist($full_path);
        $logger = new Logger($full_path);
        $logger->warning('This is a warning');
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($full_path);
        $deleted = unlink($full_path);
        $this->assertTrue($deleted);
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
    public function testDebuggerFile()
    {
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'info.log');
        $debugger = Debugger::getInstance($logs_dir);
        $debugger->setLogger('info');
        $debugger::getInstance()->getLogger('info')->info('This is a info', $_SERVER);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($logs_dir . DIRECTORY_SEPARATOR . 'info.log');
        
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
    
    public function testTriggersDebugInit()
    {
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'debug.log');
        $debugger = Debugger::getInstance($logs_dir)->setLogger('debug')->initErrorHandler('debug');
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
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';
        $debugger = Debugger::getInstance($logs_dir)->setLogger('error')->initErrorHandler('error');
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'error.log');
        trigger_error("This is a trigger", E_USER_ERROR);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($logs_dir . DIRECTORY_SEPARATOR . 'error.log');
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }

    public function testTriggerWarningSupressed()
    {
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';
        $debugger = Debugger::getInstance($logs_dir)->setLogger('error')->initErrorHandler('error');
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'error.log');
        @trigger_error("This is a trigger", E_USER_WARNING);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'error.log');
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }

    public function testTriggerNoticeNotReported()
    {
        $logs_dir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'logs';
        $debugger = Debugger::getInstance($logs_dir)->setLogger('notice')->initErrorHandler('notice', E_ALL^E_USER_NOTICE);
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'notice.log');
        @trigger_error("This is a trigger", E_USER_NOTICE);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileDoesNotExist($logs_dir . DIRECTORY_SEPARATOR . 'notice.log');
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
}