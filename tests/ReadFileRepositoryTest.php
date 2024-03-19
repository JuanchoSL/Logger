<?php

namespace JuanchoSL\Logger\Tests;

use JuanchoSL\Logger\Composers\PlainText;
use JuanchoSL\Logger\Debugger;
use JuanchoSL\Logger\Logger;
use JuanchoSL\Logger\Repositories\FileRepository;
use JuanchoSL\Logger\Repositories\ScreenRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ReadFileRepositoryTest extends TestCase
{

    protected LoggerInterface $logger;
    protected $logs_dir;
    protected $log_name;
    protected $full_path;

    public function setUp(): void
    {
        $this->logs_dir = realpath(dirname(__DIR__, 1)) . DIRECTORY_SEPARATOR . 'logs';
        $this->log_name = 'test_logger.log';
        $this->full_path = implode(DIRECTORY_SEPARATOR, [$this->logs_dir, $this->log_name]);
        $this->assertDirectoryDoesNotExist($this->logs_dir);
        $this->assertFileDoesNotExist($this->full_path);
        $composer = new PlainText;
        $handler = new FileRepository($this->full_path);
        //$handler = new ScreenRepository();
        $handler->setComposer($composer);
        $handler->setTimeFormat(DATE_RFC2822);
        $this->logger = new Logger($handler);
    }
    public function tearDown(): void
    {
        //exit;
        foreach (glob($this->logs_dir . DIRECTORY_SEPARATOR . '*') as $file) {
            $deleted = unlink($file);
            $this->assertTrue($deleted);
        }
        $deleted = rmdir($this->logs_dir);
        $this->assertTrue($deleted);
        sleep(1);
    }
    public function testLoggerFile()
    {
        $this->logger->warning('This is a warning');
        $this->assertDirectoryExists($this->logs_dir);
        $this->assertFileExists($this->full_path);
        $content = file_get_contents($this->full_path);
        $this->assertStringContainsString("This is a warning", $content);
    }
    public function testDebuggerFile()
    {
        $debugger = Debugger::getInstance()->setLogger('info', $this->logger)->getLogger('info')?->info('This is a info', $_SERVER);
        $this->assertDirectoryExists($this->logs_dir);
        $this->assertFileExists($this->full_path);
        $content = file_get_contents($this->full_path);
        $this->assertStringContainsString("This is a info", $content);
    }
    
    public function testTriggersDebugInit()
    {
        $debugger = Debugger::getInstance()->setLogger('debug', $this->logger)->initErrorHandler('debug');
        trigger_error("This is a trigger");
        $this->assertDirectoryExists($this->logs_dir);
        $this->assertFileExists($this->full_path);
        $content = file_get_contents($this->full_path);
        $this->assertStringContainsString("This is a trigger", $content);
    }
    public function testTriggerErrorInit()
    {
        $debugger = Debugger::getInstance()->setLogger('error', $this->logger)->initErrorHandler('error');
        $this->assertFileDoesNotExist($this->full_path);
        trigger_error("This is a trigger", E_USER_ERROR);
        $this->assertDirectoryExists($this->logs_dir);
        $this->assertFileExists($this->full_path);
        $content = file_get_contents($this->full_path);
        $this->assertStringContainsString("This is a trigger", $content);
    }
    
    public function testTriggerWarningSupressed()
    {
        $debugger = Debugger::getInstance()->setLogger('error', $this->logger)->initErrorHandler('error');
        $this->assertFileDoesNotExist($this->full_path);
        @trigger_error("This is a trigger", E_USER_WARNING);
        $this->assertDirectoryExists($this->logs_dir);
        $this->assertFileDoesNotExist($this->full_path);
    }

    public function testTriggerNoticeNotReported()
    {
        $debugger = Debugger::getInstance()->setLogger('notice', $this->logger)->initErrorHandler('notice', E_ALL ^ E_USER_NOTICE);
        $this->assertFileDoesNotExist($this->full_path);
        @trigger_error("This is a trigger", E_USER_NOTICE);
        $this->assertDirectoryExists($this->logs_dir);
        $this->assertFileDoesNotExist($this->full_path);
    }
}