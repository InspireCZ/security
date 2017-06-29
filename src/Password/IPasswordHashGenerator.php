<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */


namespace Inspire\Security\Password;

/**
 * Rozhrani generatoru hesel se schopnosti vygenerovane heslo i overit
 *
 * @author Martin Lutonsky <martin.lutonsky@inspire.cz>
 */
interface IPasswordHashGenerator
{
    /**
     * Vygeneruje hash hesla
     *
     * @param string $password
     *
     * @return string hash hesla
     */
    public function generate(string $password): string;

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool zdali heslo odpovida hashi
     */
    public function verify(string $password, string $hash): bool;
}
