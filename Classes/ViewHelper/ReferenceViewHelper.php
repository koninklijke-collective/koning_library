<?php

namespace Keizer\KoningLibrary\ViewHelper;

use Closure;
use Keizer\KoningLibrary\Utility\ResourceUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Retrieve reference details from given content
 * = Examples =
 * <code title="Default">
 * <l:reference uid="231" table="tt_content" field="settings.data.image">
 *      {references -> f:debug}
 * </l:reference>
 * </code>
 */
class ReferenceViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /** @var bool */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument(
            'uid',
            'integer',
            'Id of table',
            true
        );
        $this->registerArgument(
            'table',
            'string',
            'Used table',
            false,
            'tt_content'
        );
        $this->registerArgument(
            'field',
            'string',
            'Field column',
            false,
            'image'
        );
        $this->registerArgument(
            'as',
            'string',
            'The name of the new registered variable',
            false,
            'references'
        );
    }

    /**
     * @param  array  $arguments
     * @param  \Closure  $renderChildrenClosure
     * @param  \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface  $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $variableProvider = $renderingContext->getVariableProvider();
        $references = ResourceUtility::getReferenceObjects(
            $arguments['uid'],
            $arguments['table'],
            $arguments['field']
        );

        $variableProvider->add($arguments['as'], $references);
        $content = $renderChildrenClosure();
        $variableProvider->remove($arguments['as']);

        return $content;
    }
}
