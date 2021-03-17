<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use Closure;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper: Render <head> data, based on typoscript configuration
 */
class HeaderDataViewHelper extends AbstractTagBasedViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Renders tag in header data
     *
     * @param  array  $arguments
     * @param  \Closure  $renderChildrenClosure
     * @param  \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface  $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        if (isset($arguments['content']) && !empty($arguments['content'])) {
            $content = $arguments['content'];
        } else {
            $content = $renderChildrenClosure;
        }

        $content = trim($content);
        if (!empty($content)) {
            static::getPageRenderer()->addHeaderData($content);
        }

        return parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected static function getPageRenderer(): PageRenderer
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }
}
