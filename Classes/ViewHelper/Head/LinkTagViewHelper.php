<?php
namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to render link tags
 *
 * @package Keizer\KoningLibrary\ViewHelper\Head
 */
class LinkTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * Used tag name
     *
     * @var string
     */
    protected $tagName = 'link';

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerTagAttribute('href', 'string', 'Location');
        $this->registerTagAttribute('rel', 'string', 'Rel');
        $this->registerTagAttribute('type', 'string', 'Type of script');
        $this->registerTagAttribute('media', 'string', 'Media');
    }

    /**
     * Renders a script tag
     *
     * @param boolean $useCurrentDomain If set, current domain is used
     * @param boolean $forceAbsoluteUrl If set, absolute url is forced
     * @return void
     */
    public function render($useCurrentDomain = false, $forceAbsoluteUrl = false)
    {
        // set current domain
        if ($useCurrentDomain) {
            $this->tag->addAttribute('href', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($forceAbsoluteUrl) {
            $path = $this->arguments['href'];
            if (!GeneralUtility::isFirstPartOfStr($path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute(
                    'href',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $this->arguments['href']
                );
            }
        }

        if ($useCurrentDomain || (isset($this->arguments['href']) && !empty($this->arguments['href']))) {
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