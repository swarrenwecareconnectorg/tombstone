<?php

namespace Scheb\Tombstone\Test;

use Scheb\Tombstone\Graveyard;
use Scheb\Tombstone\Test\Fixtures\TraceFixture;
use Scheb\Tombstone\Vampire;

class GraveyardTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $handler;

    /**
     * @var Graveyard
     */
    private $graveyard;

    public function setUp()
    {
        $this->handler = $this->getHandlerMock();
        $this->graveyard = new Graveyard(array($this->handler));
    }

    private function getHandlerMock()
    {
        return $this->createMock('Scheb\Tombstone\Handler\HandlerInterface');
    }

    /**
     * @test
     */
    public function addHandler_anotherHandler_isCalledOnTombstone()
    {
        $handler = $this->getHandlerMock();
        $this->graveyard->addHandler($handler);

        $this->handler
            ->expects($this->once())
            ->method('log')
            ->with($this->isInstanceOf('Scheb\Tombstone\Vampire'));

        $trace = TraceFixture::getTraceFixture();
        $this->graveyard->tombstone('date', 'author', 'label', $trace);
    }

    /**
     * @test
     */
    public function tombstone_handlersRegistered_callAllHandlers()
    {
        $this->handler
            ->expects($this->once())
            ->method('log')
            ->with($this->isInstanceOf('Scheb\Tombstone\Vampire'));

        $trace = TraceFixture::getTraceFixture();
        $this->graveyard->tombstone('date', 'author', 'label', $trace);
    }

    /**
     * @test
     */
    public function tombstone_sourceDirSet_logRelativePath()
    {
        $this->handler
            ->expects($this->once())
            ->method('log')
            ->with($this->callback(function ($vampire) {
                return $vampire instanceof Vampire && 'file1.php' === $vampire->getFile();
            }));

        $trace = TraceFixture::getTraceFixture();
        $this->graveyard->setSourceDir('/path/to');
        $this->graveyard->tombstone('date', 'author', 'label', $trace);
    }

    /**
     * @test
     */
    public function tombstone_sourceDirNotMatchedFilePath_logAbsolutePath()
    {
        $this->handler
            ->expects($this->once())
            ->method('log')
            ->with($this->callback(function ($vampire) {
                return $vampire instanceof Vampire && '/path/to/file1.php' === $vampire->getFile();
            }));

        $trace = TraceFixture::getTraceFixture();
        $this->graveyard->setSourceDir('/other/path');
        $this->graveyard->tombstone('date', 'author', 'label', $trace);
    }

    /**
     * @test
     */
    public function flush_handlerRegistered_flushAllHandlers()
    {
        $this->handler
            ->expects($this->once())
            ->method('flush');

        $this->graveyard->flush();
    }
}
