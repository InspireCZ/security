<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Inspire\Security\Crypt;

/**
 * Rozhrani pro sifrovani data pomoci OpenSSL klice.
 *
 * @author Jan Zahorsky <jan.zahorsky@inspire.cz>
 */
interface IKeyCrypt
{

    /**
     * Zasifruje data pomoci privatniho klice.
     *
     * @param string $data
     *
     * @return string
     */
    public function encrypt(string $data): string;

    /**
     * Rozsifruje data pomoci privatniho klice.
     *
     * @param string $data
     *
     * @return string
     */
    public function decrypt(string $data): string;
}
