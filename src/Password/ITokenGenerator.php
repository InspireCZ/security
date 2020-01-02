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
 * Rozhrani generatoru nahodnoych tokenu
 *
 * @author Martin Lutonsky <martin.lutonsky@inspire.cz>
 */
interface ITokenGenerator
{
    /**
     * Vygeneruje nahodny retezec/token
     *
     * @return string
     */
    public function generate(): string;
}
