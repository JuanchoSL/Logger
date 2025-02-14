<?php

namespace JuanchoSL\Logger\Tests;

use JuanchoSL\Logger\Composers\ObjectComposer;
use JuanchoSL\Logger\Debugger;
use JuanchoSL\Logger\Logger;
use JuanchoSL\Logger\Repositories\ScreenRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ReadScreenObjectTest extends TestCase
{

    protected LoggerInterface $logger;
    protected $logs_dir;
    protected $log_name;
    protected $full_path;

    public function setUp(): void
    {
        $composer = new ObjectComposer;
        $composer->setTimeFormat(DATE_RFC2822);
        $handler = new ScreenRepository();
        $handler->setComposer($composer);
        $this->logger = new Logger($handler);
        ob_start();
    }
    public function tearDown(): void
    {
        //exit;
    }
    public function testLoggerFile()
    {
        $this->logger->warning('This is a warning');
        $content = ob_get_clean();
        $this->assertStringContainsString("This is a warning", $content);
    }
    public function testDebuggerFile()
    {
        $debugger = Debugger::init()->setLogger('info', $this->logger)->getLogger('info')?->info('This is a info', $_SERVER);
        $content = ob_get_clean();
        $this->assertStringContainsString("This is a info", $content);
    }

    public function testTriggersDebugInit()
    {
        $debugger = Debugger::init()->setLogger('debug', $this->logger)->initFailuresHandler('debug');
        trigger_error("This is a trigger");
        $content = ob_get_clean();
        $this->assertStringContainsString("This is a trigger", $content);
    }
    public function testTriggerErrorInit()
    {
        $debugger = Debugger::init()->setLogger('error', $this->logger)->initFailuresHandler('error');
        trigger_error("This is a trigger", E_USER_ERROR);
        $content = ob_get_clean();
        $this->assertStringContainsString("This is a trigger", $content);
    }

    public function testTriggerWarningSupressed()
    {
        $debugger = Debugger::init()->setLogger('error', $this->logger)->initFailuresHandler('error');
        @trigger_error("This is a trigger", E_USER_WARNING);
        $content = ob_get_clean();
        $this->assertStringNotContainsString("This is a trigger", $content);
    }

    public function testTriggerNoticeNotReported()
    {
        $debugger = Debugger::init()->setLogger('notice', $this->logger)->initFailuresHandler('notice', E_ALL ^ E_USER_NOTICE);
        @trigger_error("This is a trigger", E_USER_NOTICE);
        $content = ob_get_clean();
        $this->assertStringNotContainsString("This is a trigger", $content);
    }
}