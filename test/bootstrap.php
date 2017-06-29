<?php declare(strict_types = 1);

/**
 * This file is part of the Webspire (https://www.webspire.eu/)
 *
 * Copyright (c) 2002 INSPIRE CZ s.r.o. (support@inspire.cz)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this
 * source code.
 */

require_once __DIR__ . "/../vendor/autoload.php";

define('WWW_DIR', realpath(__DIR__ . '/../test'));
define('APP_DIR', WWW_DIR);

@mkdir(__DIR__ . '/../tmp');
define('TEMP_DIR', realpath(__DIR__ . '/../tmp'));

define('LOG_DIR', TEMP_DIR . '/log');
@mkdir(LOG_DIR);
define('LIB_DIR', realpath(__DIR__ . '/../vendor'));
define('COMPOSER_DIR', LIB_DIR);
