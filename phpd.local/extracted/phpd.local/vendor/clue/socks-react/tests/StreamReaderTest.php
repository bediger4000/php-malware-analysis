<?php

use Clue\React\Socks\StreamReader;

class StreamReaderTest extends TestCase
{
    private $reader;

    public function setUp()
    {
        $this->reader = new StreamReader();
    }

    public function testReadByteAssertCorrect()
    {
        $this->reader->readByteAssert(0x01)->then($this->expectCallableOnce(0x01));

        $this->reader->write("\x01");
    }

    public function testReadByteAssertInvalid()
    {
        $this->reader->readByteAssert(0x02)->then(null, $this->expectCallableOnce());

        $this->reader->write("\x03");
    }

    public function testReadStringNull()
    {
        $this->reader->readStringNull()->then($this->expectCallableOnce('hello'));

        $this->reader->write("hello\x00");
    }

    public function testReadStringLength()
    {
        $this->reader->readLength(5)->then($this->expectCallableOnce('hello'));

        $this->reader->write('he');
        $this->reader->write('ll');
        $this->reader->write('o ');

        $this->assertEquals(' ', $this->reader->getBuffer());
    }

    public function testReadBuffered()
    {
        $this->reader->write('hello');

        $this->reader->readLength(5)->then($this->expectCallableOnce('hello'));

        $this->assertEquals('', $this->reader->getBuffer());
    }

    public function testSequence()
    {
        $this->reader->readByte()->then($this->expectCallableOnce(ord('h')));
        $this->reader->readByteAssert(ord('e'))->then($this->expectCallableOnce(ord('e')));
        $this->reader->readLength(4)->then($this->expectCallableOnce('llo '));
        $this->reader->readBinary(array('w'=>'C', 'o' => 'C'))->then($this->expectCallableOnce(array('w' => ord('w'), 'o' => ord('o'))));

        $this->reader->write('hello world');

        $this->assertEquals('rld', $this->reader->getBuffer());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStructure()
    {
        $this->reader->readBinary(array('invalid' => 'y'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCallback()
    {
        $this->reader->readBufferCallback(array());
    }
}
