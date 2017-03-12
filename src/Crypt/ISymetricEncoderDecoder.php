<?php declare(strict_types = 1);

/**
 * This file is part of the Netwings (https://www.netwings.cz/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Inspire\Security\Crypt;


/**
 * Rozhranni pro kodery a dekodery tajemnstvi pomoci symetrickeho sifrovani.
 *
 * @author Martin Odstrcilik <martin.odstrcilik@gmail.com>
 */
interface ISymetricEncoderDecoder
{
    /**
     * Zasifruje data pomoci klice.
     *
     * @param string $plaintext
     *
     * @return string
     */
    public function encode(string $plaintext): string;

    /**
     * Rozsifruje data pomoci klice.
     *
     * @param string $ciphertext
     *
     * @return string
     */
    public function decode(string $ciphertext): string;
}
