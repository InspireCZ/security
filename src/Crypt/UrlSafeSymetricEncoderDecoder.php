<?php

namespace Inspire\Security\Crypt;

use Inspire\Security\InvalidArgumentException;

/**
 * Wrapper of another ISymmetricEncoderDecoder - encoded/decoded string is converted to/from hexa characters
 * (it can be safely used in URL query parameter value).
 *
 * @author Martin Lutonsky <martin.lutonsky@gmail.com>
 */
class UrlSafeSymetricEncoderDecoder implements ISymetricEncoderDecoder
{
    /** @var ISymetricEncoderDecoder */
    private $codec;

    public function __construct(ISymetricEncoderDecoder $codec)
    {
        $this->codec = $codec;
    }

    public function encode(string $plaintext): string
    {
        $encoded = $this->codec->encode($plaintext);
        $unpacked = @\unpack('H*', $encoded);

        if (false === $unpacked) {
            throw new InvalidArgumentException("Unable to unpack encoded string");
        }

        return $unpacked[1];
    }

    public function decode(string $plaintext): string
    {
        $packed = @\pack('H*', $plaintext);

        if (false === $packed) {
            throw new InvalidArgumentException("Unable to pack decoded string");
        }

        return $this->codec->decode($packed);
    }
}
