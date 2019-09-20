<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper to render link tags
 */
class LinkTagViewHelper extends AbstractTagBasedViewHelper
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
        $this->registerArgument(
            'useCurrentDomain',
            'boolean',
            'If set, current domain is used as src',
            false,
            false
        );
        $this->registerArgument(
            'forceAbsoluteUrl',
            'boolean',
            'If set, absolute url is forced',
            false,
            false
        );
        $this->registerTagAttribute('href', 'string', 'Location');
        $this->registerTagAttribute('rel', 'string', 'Rel');
        $this->registerTagAttribute('type', 'string', 'Type of script');
        $this->registerTagAttribute('media', 'string', 'Media');
    }

    /**
     * Renders a script tag
     *
     * @return void
     */
    public function render(): void
    {
        $arguments = $this->arguments;
        // set current domain
        if ($arguments['useCurrentDomain']) {
            $this->tag->addAttribute('href', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($arguments['forceAbsoluteUrl']) {
            $path = $arguments['href'];
            if (!GeneralUtility::isFirstPartOfStr($path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute(
                    'href',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $arguments['href']
                );
            }
        }

        if ($arguments['useCurrentDomain'] || (isset($arguments['href']) && !empty($arguments['href']))) {
            $this->getPageRenderer()->addHeaderData($this->tag->render());
        }
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected function getPageRenderer(): PageRenderer
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
