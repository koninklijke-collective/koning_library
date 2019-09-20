<?php

namespace Keizer\KoningLibrary\ViewHelper\Form;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException;

/**
 * View helper: If property has error
 *
 * @deprecated
 */
class IfPropertyHasErrorViewHelper extends AbstractConditionViewHelper
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registerArgument('property', 'string', 'Property to validate', true);
    }

    /**
     * Returns thenChild if the provided property contains one or more errors
     *
     * @return string
     */
    public function render()
    {
        $property = $this->arguments['property'];

        $originalRequestMappingResults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults();
        try {
            $formObjectName = $this->viewHelperVariableContainer->get(
                'TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper',
                'formObjectName'
            );

            if ($originalRequestMappingResults->forProperty($formObjectName)->forProperty($property)->hasErrors()) {
                return $this->renderThenChild();
            }

            return $this->renderElseChild();
        } catch (InvalidVariableException $e) {
            if ($originalRequestMappingResults->forProperty($property)->hasErrors()) {
                return $this->renderThenChild();
            }

            return $this->renderElseChild();
        }
    }
}
