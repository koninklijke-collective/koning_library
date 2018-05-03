<?php
namespace Keizer\KoningLibrary\ViewHelper\Head;

/**
 * ViewHelper: Render <head> data, based on typoscript configuration
 *
 * @package Keizer\KoningLibrary\ViewHelper\Head
 */
class HeaderDataViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * Renders tag in header data
     *
     * @param string $content
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
            return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
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
