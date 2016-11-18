<?php
namespace Keizer\KoningLibrary\Utility;

/**
 * Utility: EID invokes
 *
 * @package Keizer\KoningLibrary\Utility
 */
class EidUtility
{

    /**
     * Initialize full GLOBALS[TSFE] based on given page id
     *
     * @param integer $pageId
     * @return void
     */
    public static function initializeFrontendPage($pageId = 0)
    {
        static::initializeTypoScriptFrontendController($pageId);
        static::initializeFrontendUserAuthentication();

        $controller = &$GLOBALS['TSFE'];
        if (!($controller->sys_page instanceof \TYPO3\CMS\Frontend\Page\PageRepository)) {
            $controller->determineId();
        }

        if (!($controller->tmpl instanceof \TYPO3\CMS\Core\TypoScript\TemplateService)) {
            $controller->initTemplate();
        }

        if (!is_array($controller->config)) {
            $controller->getConfigArray();
        }

        static::initializeContentObjectRenderer();

        if (empty($controller->indexedDocTitle)) {
            \TYPO3\CMS\Frontend\Page\PageGenerator::pagegenInit();
        }
    }

    /**
     * Initialize TSFE based on given page id
     *
     * @param integer $pageId
     * @return void
     */
    public static function initializeTypoScriptFrontendController($pageId = 1)
    {
        global $TYPO3_CONF_VARS;

        // fallback for timetracker
        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\NullTimeTracker();
        }

        $controller = &$GLOBALS['TSFE'];
        if (!($controller instanceof \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController)) {
            $controller = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
                $TYPO3_CONF_VARS,
                $pageId,
                0
            );
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
        if (!($GLOBALS['TSFE']->fe_user instanceof \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication)) {
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
        if (!($GLOBALS['TSFE']->cObj instanceof \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer)) {
            $GLOBALS['TSFE']->newCObj();
        }
    }

}
