<?php

// Custom Routing Aspects Mapper
$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['SimpleValueMapper'] ??=
    \Keizer\KoningLibrary\Routing\Aspect\SimpleValueMapper::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['SanitizedValueMapper'] ??=
    \Keizer\KoningLibrary\Routing\Aspect\SanitizedValueMapper::class;
