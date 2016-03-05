<?php
namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to render meta tags
 *
 * @package Keizer\KoningLibrary\ViewHelper\Head
 */
class MetaTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * Used tag name
     *
     * @var string
     */
    protected $tagName = 'meta';

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerTagAttribute('name', 'string', 'Name of meta tag');
        $this->registerTagAttribute('property', 'string', 'Property of meta tag');
        $this->registerTagAttribute('content', 'string', 'Content of meta tag');
    }

    /**
     * Renders a meta tag
     *
     * @param boolean $useCurrentDomain If set, current domain is used
     * @param boolean $forceAbsoluteUrl If set, absolute url is forced
     * @return void
     */
    public function render($useCurrentDomain = false, $forceAbsoluteUrl = false)
    {
        // set current domain
        if ($useCurrentDomain) {
            $this->tag->addAttribute('content', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($forceAbsoluteUrl) {
            $path = $this->arguments['content'];
            if (!GeneralUtility::isFirstPartOfStr($path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute(
                    'content',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . ltrim($this->arguments['content'], '/')
                );
            }
        }

        if ($useCurrentDomain || (isset($this->arguments['content']) && !empty($this->arguments['content']))) {
            $this->getPageRenderer()->addHeaderData($this->tag->render());
        }
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected function getPageRenderer()
    {
        if ('FE' === TYPO3_MODE && is_callable(array($this->getTypoScriptFrontendController(), 'getPageRenderer'))) {
            return $this->getTypoScriptFrontendController()->getPageRenderer();
        } else {
            return GeneralUtility::makeInstance('TYPO3\CMS\Core\Page\PageRenderer');
        }
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}