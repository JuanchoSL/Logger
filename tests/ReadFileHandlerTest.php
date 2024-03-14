<?php

namespace JuanchoSL\EnvVars\Tests;

use JuanchoSL\Logger\Debugger;
use JuanchoSL\Logger\Handlers\FileHandler;
use JuanchoSL\Logger\Logger;
use PHPUnit\Framework\TestCase;

class ReadFileHandlerTest extends TestCase
{

    public function testLoggerFile()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $log_name = 'test_logger.log';
        $full_path = implode(DIRECTORY_SEPARATOR, [$logs_dir, $log_name]);
        $this->assertDirectoryDoesNotExist($logs_dir);
        $this->assertFileDoesNotExist($full_path);
        $handler = new FileHandler($full_path);
        $logger = new Logger($handler);
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
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $full_path = $logs_dir . DIRECTORY_SEPARATOR . 'info.log';
        $this->assertFileDoesNotExist($full_path);
        $debugger = Debugger::getInstance($logs_dir);
        $debugger->setLogger('info', new Logger(new FileHandler($full_path)));
        $debugger::getInstance()->getLogger('info')->info('This is a info', $_SERVER);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($full_path);

        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }

    public function testTriggersDebugInit()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $full_path = $logs_dir . DIRECTORY_SEPARATOR . 'debug.log';
        $this->assertFileDoesNotExist($full_path);
        $debugger = Debugger::getInstance($logs_dir)->setLogger('debug', new Logger(new FileHandler($full_path)))->initErrorHandler('debug');
        trigger_error("This is a trigger");
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($full_path);
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
    public function testTriggerErrorInit()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $full_path = $logs_dir . DIRECTORY_SEPARATOR . 'error.log';
        $debugger = Debugger::getInstance($logs_dir)->setLogger('error', new Logger(new FileHandler($full_path)))->initErrorHandler('error');
        $this->assertFileDoesNotExist($full_path);
        trigger_error("This is a trigger", E_USER_ERROR);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileExists($full_path);
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }

    public function testTriggerWarningSupressed()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $full_path = $logs_dir . DIRECTORY_SEPARATOR . 'error.log';
        $debugger = Debugger::getInstance($logs_dir)->setLogger('error', new Logger(new FileHandler($full_path)))->initErrorHandler('error');
        $this->assertFileDoesNotExist($full_path);
        @trigger_error("This is a trigger", E_USER_WARNING);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileDoesNotExist($full_path);
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }

    public function testTriggerNoticeNotReported()
    {
        $logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $full_path = $logs_dir . DIRECTORY_SEPARATOR . 'notice.log';
        $debugger = Debugger::getInstance($logs_dir)->setLogger('notice', new Logger(new FileHandler($full_path)))->initErrorHandler('notice', E_ALL ^ E_USER_NOTICE);
        $this->assertFileDoesNotExist($full_path);
        @trigger_error("This is a trigger", E_USER_NOTICE);
        $this->assertDirectoryExists($logs_dir);
        $this->assertFileDoesNotExist($full_path);
        foreach (glob($logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        $deleted = rmdir($logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
}