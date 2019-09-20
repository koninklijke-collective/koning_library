<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper: Render <head> data, based on typoscript configuration
 */
class HeaderDataViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * Renders tag in header data
     *
     * @param  string  $content
     * @return void
     */
    public function render($content = '')
    {
        if (empty($content)) {
            $content = $this->renderChildren();
        }
        $content = trim($content);

        if (!empty($content)) {
            $this->getPageRenderer()->addHeaderData($content);
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
            return GeneralUtility::makeInstance(PageRenderer::class);
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
