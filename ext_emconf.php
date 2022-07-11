<?php

$EM_CONF['koning_library'] = [
    'title' => 'Koning: Library',
    'description' => 'ViewHelpers, Abstract classes, Wizards and Utilities',
    'category' => 'misc',
    'version' => '3.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper,Benjamin Serfhos',
    'author_email' => 'koninklijkecollective@gmail.com',
    'author_company' => 'Koninklijke Collective',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.16-11.5.99',
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
