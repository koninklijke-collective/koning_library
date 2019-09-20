<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper to render <script> tags
 */
class ScriptTagViewHelper extends AbstractTagBasedViewHelper
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
        $this->registerArgument(
            'addToFooter',
            'boolean',
            'If set, script is added to footer',
            false,
            false
        );

        $this->registerTagAttribute('src', 'string', 'Location of script tag');
        $this->registerTagAttribute('type', 'string', 'Type of script');
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
            $this->tag->addAttribute('src', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($arguments['forceAbsoluteUrl']) {
            $path = $arguments['src'];
            if (!GeneralUtility::isFirstPartOfStr($path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute(
                    'src',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $arguments['src']
                );
            }
        }

        if ($arguments['useCurrentDomain'] || (isset($arguments['src']) && !empty($arguments['src']))) {
            $this->tag->forceClosingTag(true);
            if ($arguments['addToFooter'] === true) {
                $this->getPageRenderer()->addFooterData($this->tag->render());
            } else {
                $this->getPageRenderer()->addHeaderData($this->tag->render());
            }
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
