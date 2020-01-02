<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Inspire\Security\Test\Crypt;

use Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder;
use Inspire\Security\InvalidArgumentException;
use Nette\Utils\Random;
use PHPUnit\Framework\TestCase;

/**
 * OpenSSLSymetricEncoderDecoderTest
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 *
 * @covers \Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder
 */
final class OpenSSLSymetricEncoderDecoderTest extends TestCase
{
    public function testConstructEmpty()
    {
        self::expectException(\Inspire\Security\InvalidArgumentException::class);
        new OpenSSLSymetricEncoderDecoder('');
    }

    public function testConstructShorterKey()
    {
        $shortKey = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH - 1);

        self::expectException(\Inspire\Security\InvalidArgumentException::class);
        new OpenSSLSymetricEncoderDecoder($shortKey);
    }

    /**
     * Helper pro generovani klice k sifrovani
     *
     * @param int $length
     *
     * @return string
     */
    private function randomString(int $length): string
    {
        return Random::generate($length, '0-9a-f');
    }

    public function testConstructLongerKey()
    {
        $longerKey = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH + 1);
        self::expectException(\Inspire\Security\InvalidArgumentException::class);

        new OpenSSLSymetricEncoderDecoder($longerKey);
    }

    public function testConstructLonger()
    {
        $randomString = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH);
        $encoderDecoder = new OpenSSLSymetricEncoderDecoder($randomString);
        self::assertInstanceOf(OpenSSLSymetricEncoderDecoder::class, $encoderDecoder);
    }

    public function testEncode()
    {
        $randomString = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH);
        $encoder = new OpenSSLSymetricEncoderDecoder($randomString);

        $plainText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

        $encoded = $encoder->encode($plainText);

        self::assertEquals($plainText, $encoder->decode($encoded));
    }

    public function testDecode()
    {
        $randomString = $this->randomString(OpenSSLSymetricEncoderDecoder::REQUIRED_KEY_LENGTH);
        $decoder = new OpenSSLSymetricEncoderDecoder($randomString);

        $ivSize = openssl_cipher_iv_length(OpenSSLSymetricEncoderDecoder::CIPHER);

        $invalidCiphertext = $this->randomString($ivSize - 1);

        $this->expectException(InvalidArgumentException::class);
        $decoder->decode($invalidCiphertext);
    }
}
