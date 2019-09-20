<?php
namespace Keizer\KoningLibrary\ViewHelper\Form;

/**
 * View helper: If property has error
 *
 * @package Keizer\KoningLibrary\ViewHelpers\Form
 * @deprecated
 */
class IfPropertyHasErrorViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
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
        } catch (\TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException $e) {
            if ($originalRequestMappingResults->forProperty($property)->hasErrors()) {
                return $this->renderThenChild();
            }
            return $this->renderElseChild();
        }
    }
}
