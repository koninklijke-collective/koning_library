<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

use Exception;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper for header status codes
 * Example
 * <n:head.headerStatus status="404" />
 */
class HeaderStatusViewHelper extends AbstractViewHelper
{

    /**
     * Override the status code
     *
     * @param  string  $status
     * @return void
     */
    public function render($status = '404')
    {
        HttpUtility::setResponseCode($this->getResponseCode($status));
    }

    /**
     * Map status code to given HttpUtility status string
     *
     * @param  string|integer  $statusCode
     * @return string
     * @throws \Exception
     */
    protected function getResponseCode($statusCode)
    {
        if (MathUtility::canBeInterpretedAsInteger($statusCode)) {
            // Get response code constant from core
            $constantLookUp = '\TYPO3\CMS\Core\Utility\HttpUtility::HTTP_STATUS_' . $statusCode;
            $header = (defined($constantLookUp) ? constant($constantLookUp) : null);
            if ($header === null) {
                throw new Exception('Unknown HTTP status');
            }

            return $header;
        }

        return $statusCode;
    }
}
