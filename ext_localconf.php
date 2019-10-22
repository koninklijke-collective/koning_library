<?php

defined('TYPO3_MODE') or die('Access denied.');

call_user_func(function (string $extension): void {
    // Custom Routing Aspects Mapper
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['SimpleValueMapper'] =
        \Keizer\KoningLibrary\Routing\Aspect\SimpleValueMapper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['SanitizedValueMapper'] =
        \Keizer\KoningLibrary\Routing\Aspect\SanitizedValueMapper::class;
}, 'koning_library');
