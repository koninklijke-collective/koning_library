<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Koning: Library',
    'description' => 'ViewHelpers, Abstract classes, Wizards and Utilities',
    'category' => 'misc',
    'version' => '2.1.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper,Benjamin Serfhos',
    'author_email' => 'jesper@koninklijk.io, benjamin@koninklijk.io',
    'author_company' => 'Koninklijke Collective',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Keizer\\KoningLibrary\\' => 'Classes',
        ],
    ],
];
