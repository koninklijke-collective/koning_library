<?php
namespace Keizer\KoningLibrary\ViewHelper\Head;

/**
 * ViewHelper for header status codes
 * Example
 * <n:head.headerStatus status="404" />
 *
 * @package Keizer\KoningLibrary\ViewHelper\Head
 */
class HeaderStatusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Override the status code
     *
     * @param string $status
     * @return void
     */
    public function render($status = '404')
    {
        \TYPO3\CMS\Core\Utility\HttpUtility::setResponseCode($this->getResponseCode($status));
    }

    /**
     * Map status code to given HttpUtility status string
     *
     * @param string|integer $statusCode
     * @return string
     * @throws \Exception
     */
    protected function getResponseCode($statusCode)
    {
        if (\TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($statusCode)) {
            // Get response code constant from core
            $constantLookUp = '\TYPO3\CMS\Core\Utility\HttpUtility::HTTP_STATUS_' . $statusCode;
            $header = (defined($constantLookUp) ? constant($constantLookUp) : null);
            if ($header === null) {
                throw new \Exception('Unknown HTTP status');
            }
            return $header;
        }
        return $statusCode;
    }
}