<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use Closure;
use Keizer\KoningLibrary\PageTitle\ViewHelperPageTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper for <title> tag
 * == Example ==
 * <n:titleTag>{newsItem.title}</n:titleTag>
 * == Result ==
 * Sets the title of the news record as title tag
 */
class TitleTagViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Default implementation of static rendering; useful API method if your ViewHelper
     * when compiled is able to render itself statically to increase performance. This
     * default implementation will simply delegate to the ViewHelperInvoker.
     *
     * @param  array  $arguments
     * @param  \Closure  $renderChildrenClosure
     * @param  \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface  $renderingContext
     * @return void
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): void {
        $content = trim($renderChildrenClosure());

        if (!empty($content)) {
            GeneralUtility::makeInstance(ViewHelperPageTitleProvider::class)
                ->setTitle($content);
        }
    }
}
