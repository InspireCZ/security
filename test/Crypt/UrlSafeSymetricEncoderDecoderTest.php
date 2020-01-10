<?php

namespace Inspire\Security\Test\Crypt;

use Inspire\Security\Crypt\ISymetricEncoderDecoder;
use Inspire\Security\Crypt\UrlSafeSymetricEncoderDecoder;
use Inspire\Security\InvalidArgumentException;
use Mockery;
use Nette\Utils\Strings;

/**
 * @author Martin Lutonsky <martin.lutonsky@gmail.com>
 *
 * @covers \Inspire\Security\Crypt\UrlSafeSymetricEncoderDecoder
 */
class UrlSafeSymetricEncoderDecoderTest extends \PHPUnit\Framework\TestCase
{
    public function testEncode()
    {
        $plainText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $encoder = new UrlSafeSymetricEncoderDecoder($this->getCodec($plainText));
        $plaintTextRev = Strings::reverse($plainText);
        $encoded = $encoder->encode($plainText);

        self::assertEquals(unpack('H*', $plaintTextRev)[1], $encoded);
        self::assertTrue((bool) Strings::match($encoded, '~[a-f0-9]+~'));
    }

    public function testDecode()
    {
        $plainText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $plainTextRev = Strings::reverse($plainText);
        $encoded = unpack('H*', $plainText)[1];
        $encoder = new UrlSafeSymetricEncoderDecoder($this->getCodec($plainText));
        $decoded = $encoder->decode($encoded);

        self::assertEquals($plainTextRev, $decoded);
    }

    public function testDecodeInvalidCharacters()
    {
        $plainText = 'ZZ Top!';
        $encoder = new UrlSafeSymetricEncoderDecoder($this->getCodec($plainText));

        self::expectException(InvalidArgumentException::class);
        $encoder->decode($plainText);
    }

    private function getCodec(string $input): ISymetricEncoderDecoder
    {
        $mock = Mockery::mock(ISymetricEncoderDecoder::class);
        $mock->shouldReceive('encode')->with($input)->andReturn(Strings::reverse($input));
        $mock->shouldReceive('decode')->with($input)->andReturn(Strings::reverse($input));

        return $mock;
    }
}
