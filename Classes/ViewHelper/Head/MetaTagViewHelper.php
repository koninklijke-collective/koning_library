<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper to render meta tags
 */
class MetaTagViewHelper extends AbstractTagBasedViewHelper
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
    public function initializeArguments(): void
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
        $this->registerTagAttribute('name', 'string', 'Name of meta tag');
        $this->registerTagAttribute('property', 'string', 'Property of meta tag');
        $this->registerTagAttribute('content', 'string', 'Content of meta tag');
    }

    /**
     * Renders a meta tag
     *
     * @return void
     */
    public function render(): void
    {
        $arguments = $this->arguments;
        // set current domain
        if ($arguments['useCurrentDomain']) {
            $this->tag->addAttribute('content', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($arguments['forceAbsoluteUrl']) {
            $path = $arguments['content'];
            if (!GeneralUtility::isFirstPartOfStr($path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute(
                    'content',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . ltrim($arguments['content'], '/')
                );
            }
        }

        if ($arguments['useCurrentDomain'] || (isset($arguments['content']) && !empty($arguments['content']))) {
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
