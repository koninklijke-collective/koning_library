<?php

namespace Keizer\KoningLibrary\ViewHelper\Form;

use Closure;
use Exception;
use TYPO3\CMS\Fluid\ViewHelpers\CoreFormViewHelper;
use TYPO3\CMS\Form\ViewHelpers\CoreFormViewHelper as FormViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * View helper: If property has error
 */
class IfPropertyHasErrorViewHelper extends AbstractConditionViewHelper
{
    /**
     * Constructor
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('property', 'string', 'Property to validate', true);
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
        if (static::propertyHasErrorInRequest($renderingContext, $arguments['property'])) {
            if (isset($arguments['then'])) {
                return $arguments['then'];
            }
            if (isset($arguments['__thenClosure'])) {
                return $arguments['__thenClosure']();
            }
        } elseif (!empty($arguments['__elseClosures'])) {
            $elseIfClosures = $arguments['__elseifClosures'] ?? [];

            return static::customEvaluateElseClosures($arguments['__elseClosures'], $elseIfClosures, $renderingContext);
        } elseif (array_key_exists('else', $arguments)) {
            return $arguments['else'];
        }

        return '';
    }

    /**
     * @param  \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface  $renderingContext
     * @param  string  $property
     * @return bool
     */
    public static function propertyHasErrorInRequest(
        RenderingContextInterface $renderingContext,
        string $property
    ): bool {
        if (!is_callable([$renderingContext, 'getControllerContext'])) {
            return false;
        }

        /** @var \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext */
        $controllerContext = $renderingContext->getControllerContext();
        $originalRequestMappingResults = $controllerContext->getRequest()->getOriginalRequestMappingResults();
        try {
            $formObjectName = $renderingContext->getViewHelperVariableContainer()
                    ->get(CoreFormViewHelper::class, 'formObjectName')
                ?? $renderingContext->getViewHelperVariableContainer()
                    ->get(FormViewHelper::class, 'formObjectName');

            if (!empty($formObjectName)) {
                return $originalRequestMappingResults
                    ->forProperty($formObjectName)
                    ->forProperty($property)
                    ->hasErrors();
            }

            return $originalRequestMappingResults
                ->forProperty($property)
                ->hasErrors();
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * @param  array  $closures
     * @param  array  $conditionClosures
     * @param  \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface  $renderingContext
     * @return string
     * @see \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper::evaluateElseClosures
     */
    private static function customEvaluateElseClosures(
        array $closures,
        array $conditionClosures,
        RenderingContextInterface $renderingContext
    ): string {
        foreach ($closures as $elseNodeIndex => $elseNodeClosure) {
            if (!isset($conditionClosures[$elseNodeIndex])) {
                return $elseNodeClosure();
            }
            if ($conditionClosures[$elseNodeIndex]()) {
                return $elseNodeClosure();
            }
        }

        return '';
    }
}
