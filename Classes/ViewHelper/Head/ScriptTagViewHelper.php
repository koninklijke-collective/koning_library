<?php
namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to render <script> tags
 *
 * @package Keizer\KoningLibrary\ViewHelper\Head
 */
class ScriptTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * Used tag name
     *
     * @var string
     */
    protected $tagName = 'script';

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerTagAttribute('src', 'string', 'Location of script tag');
        $this->registerTagAttribute('type', 'string', 'Type of script');
    }

    /**
     * Renders a script tag
     *
     * @param boolean $useCurrentDomain If set, current domain is used
     * @param boolean $forceAbsoluteUrl If set, absolute url is forced
     * @param boolean $addToFooter
     * @return void
     */
    public function render($useCurrentDomain = false, $forceAbsoluteUrl = false, $addToFooter = false)
    {
        // set current domain
        if ($useCurrentDomain) {
            $this->tag->addAttribute('src', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($forceAbsoluteUrl) {
            $path = $this->arguments['src'];
            if (!GeneralUtility::isFirstPartOfStr($path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute(
                    'src',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $this->arguments['src']
                );
            }
        }

        if ($useCurrentDomain || (isset($this->arguments['src']) && !empty($this->arguments['src']))) {
            $this->tag->forceClosingTag(true);
            if ($addToFooter === true) {
                $this->getPageRenderer()->addFooterData($this->tag->render());
            } else {
                $this->getPageRenderer()->addHeaderData($this->tag->render());
            }
        }
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected function getPageRenderer()
    {
        if ('FE' === TYPO3_MODE && is_callable([$this->getTypoScriptFrontendController(), 'getPageRenderer'])) {
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
