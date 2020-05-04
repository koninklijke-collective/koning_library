<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use Closure;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper for header status codes
 * Example
 * <n:head.headerStatus status="404" />
 */
class HeaderStatusViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('status', 'string', '', false, '404');
    }

    /**
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
        HttpUtility::setResponseCode(static::getResponseCode($arguments['status']));

        return parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
    }

    /**
     * Map status code to given HttpUtility status string
     *
     * @param  string|int  $statusCode
     * @return string
     */
    protected static function getResponseCode(string $statusCode): string
    {
        if (MathUtility::canBeInterpretedAsInteger($statusCode)) {
            // Get response code constant from core
            $constantLookUp = '\TYPO3\CMS\Core\Utility\HttpUtility::HTTP_STATUS_' . $statusCode;
            $header = (defined($constantLookUp) ? constant($constantLookUp) : null);

            return $header ?? $statusCode;
        }

        return $statusCode;
    }
}
