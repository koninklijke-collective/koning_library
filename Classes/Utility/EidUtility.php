<?php
namespace Keizer\KoningLibrary\Utility;

/**
 * Utility: AJAX EID interaction
 *
 * @package Keizer\KoningLibrary\Utility
 */
class EidUtility
{

    /**
     * Initializes TypoScript frontend controller
     *
     * @param integer $pageId
     * @return void
     */
    public static function initializeTypoScriptFrontendController($pageId = 0)
    {
        if (!($GLOBALS['TSFE'] instanceof \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController)) {
            \TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();

            if (!is_object($GLOBALS['TT'])) {
                $GLOBALS['TT'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TimeTracker\NullTimeTracker::class);
                $GLOBALS['TT']->start();
            }

            $GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::class, $GLOBALS['TYPO3_CONF_VARS'], $pageId, 0);
            $GLOBALS['TSFE']->connectToDB();
            $GLOBALS['TSFE']->initFEuser();
            $GLOBALS['TSFE']->determineId();
            $GLOBALS['TSFE']->initTemplate();
            $GLOBALS['TSFE']->getConfigArray();

            if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
                $rootLine = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($pageId);
                $host = \TYPO3\CMS\Backend\Utility\BackendUtility::firstDomainRecord($rootLine);
                $_SERVER['HTTP_HOST'] = $host;
            }
        }
    }
}
