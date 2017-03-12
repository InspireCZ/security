<?php declare(strict_types = 1);

/**
 * This file is part of the Netwings (https://www.netwings.cz/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Inspire\Security\Test\Crypt;


use Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder;
use Inspire\Security\InvalidArgumentException;
use Nette\Utils\Random;
use PHPUnit\Framework\TestCase;
use VladaHejda\AssertException;

/**
 * OpenSSLSymetricEncoderDecoderTest
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 *
 * @covers \Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder
 */
final class OpenSSLSymetricEncoderDecoderTest extends TestCase
{

    use AssertException;

    /**
     * Helper pro generovani klice k sifrovani
     *
     * @param int $length
     *
     * @return string
     */
    protected function randomString(int $length): string
    {
        return Random::generate($length, '0-9a-f');
    }

    /**
     * @expectedException \Inspire\Security\InvalidArgumentException
     */
    public function testConstructEmpty()
    {
        new OpenSSLSymetricEncoderDecoder('');
    }

    /**
     * @expectedException \TypeError
     */
    public function testConstructNull()
    {
        new OpenSSLSymetricEncoderDecoder(null);
    }

    /**
     * @expectedException \Inspire\Security\InvalidArgumentException
     */
    public function testConstructShorterKey()
    {
        $shortKey = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH - 1);
        new OpenSSLSymetricEncoderDecoder($shortKey);
    }

    /**
     * @expectedException \Inspire\Security\InvalidArgumentException
     */
    public function testConstructLongerKey()
    {
        $longerKey = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH + 1);
        new OpenSSLSymetricEncoderDecoder($longerKey);
    }

    public function testConstructLonger()
    {
        $encoderDecoder = new OpenSSLSymetricEncoderDecoder($this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH));
        self::assertInstanceOf(OpenSSLSymetricEncoderDecoder::class, $encoderDecoder);
    }

    public function testEncode()
    {
        $encoder = new OpenSSLSymetricEncoderDecoder($this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH));

        $plainText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

        $encoded = $encoder->encode($plainText);

        self::assertEquals($plainText, $encoder->decode($encoded));
    }

    public function testDecode()
    {
        $decoder = new OpenSSLSymetricEncoderDecoder($this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH));

        $ivSize = openssl_cipher_iv_length(OpenSSLSymetricEncoderDecoder::CIPHER);

        $invalidCiphertext = $this->randomString($ivSize - 1);

        self::assertException(function () use ($decoder, $invalidCiphertext) {
            $decoder->decode($invalidCiphertext);
        }, InvalidArgumentException::class);
    }

}
