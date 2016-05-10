<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Koning: Library',
    'description' => 'ViewHelpers, Abstract classes, Wizards and Utilities',
    'category' => 'misc',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper,Benjamin Serfhos',
    'author_email' => 'jesper@koninklijk.io, benjamin@koninklijk.io',
    'author_company' => 'Koninklijke Collective',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-8.99.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
    'autoload' => array(
        'psr-4' => array(
            'Keizer\\KoningLibrary\\' => 'Classes'
        )
    ),
);
