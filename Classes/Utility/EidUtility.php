<?php

namespace Keizer\KoningLibrary\Utility;

use Exception;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageGenerator;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Utility: AJAX EID interaction
 */
class EidUtility
{

    /**
     * Initialize full GLOBALS[TSFE] based on given page id
     *
     * @param  integer  $pageId
     * @return void
     */
    public static function initializeFrontendPage($pageId = 0)
    {
        static::initializeTypoScriptFrontendController($pageId);
        static::initializeFrontendUserAuthentication();

        $controller = &$GLOBALS['TSFE'];
        if (!($controller->sys_page instanceof PageRepository)) {
            $controller->determineId();
        }

        if (!($controller->tmpl instanceof TemplateService)) {
            $controller->initTemplate();
        }

        if (empty($controller->config)) {
            try {
                $controller->getConfigArray();
            } catch (Exception $e) {
                // Do nothing
            }
        }

        static::initializeContentObjectRenderer();

        if (empty($controller->indexedDocTitle) && is_callable('\TYPO3\CMS\Frontend\Page\PageGenerator::pagegenInit')) {
            PageGenerator::pagegenInit();
        }
    }

    /**
     * Initialize TSFE based on given page id
     *
     * @param  integer  $pageId
     * @return void
     */
    public static function initializeTypoScriptFrontendController($pageId = 1)
    {
        global $TYPO3_CONF_VARS;

        // fallback for timetracker
        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = new NullTimeTracker();
        }

        $controller = &$GLOBALS['TSFE'];
        if (!($controller instanceof TypoScriptFrontendController)) {
            $controller = GeneralUtility::makeInstance(
                'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
                $TYPO3_CONF_VARS,
                $pageId,
                0
            );

            // @TODO: deprecated workaround since 8/9
            $bootstrap = Bootstrap::getInstance();
            if (is_callable([$bootstrap, 'loadExtensionTables'])) {
                $bootstrap->loadExtensionTables();
            } elseif (is_callable([$bootstrap, 'loadCachedTca'])) {
                $bootstrap->loadCachedTca();
            }
        }
    }

    /**
     * Initialize GLOBALS Frontend User
     *
     * @return void
     */
    public static function initializeFrontendUserAuthentication()
    {
        static::initializeTypoScriptFrontendController();
        if (!($GLOBALS['TSFE']->fe_user instanceof FrontendUserAuthentication)) {
            $GLOBALS['TSFE']->initFEuser();
        }
    }

    /**
     * Initialize GLOBALS Content Object Renderer
     *
     * @return void
     */
    public static function initializeContentObjectRenderer()
    {
        static::initializeTypoScriptFrontendController();
        if (!($GLOBALS['TSFE']->cObj instanceof ContentObjectRenderer)) {
            $GLOBALS['TSFE']->newCObj();
        }
    }
}
