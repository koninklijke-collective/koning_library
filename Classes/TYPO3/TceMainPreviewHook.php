<?php
namespace Keizer\KoningLibrary\TYPO3;

/**
 * Preview external tables
 *
 * @package ERIM\ErimPageOptions\Hook
 */
class TceMainPreviewHook implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @param array $fieldArray
     * @param string $table
     * @param integer $id
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $reference
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $reference)
    {
        if (isset($GLOBALS['_POST']['_savedokview_x']) && !$this->getBackendUserAuthentication()->workspace) {
            $pageId = (int) $GLOBALS['_POST']['popViewId'];
            $configuration = $this->getFutureFeatureConfiguration($pageId, $table);
            if (!empty($configuration)) {
                $currentRecord = $reference->checkValue_currentRecord;
                \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($currentRecord, $fieldArray);

                // Set pid of single view if configured
                $GLOBALS['_POST']['popViewId'] = ($configuration['previewPageId']) ? $configuration['previewPageId'] : $GLOBALS['_POST']['popViewId'];

                $query = '';

                // Set generic default query parameters
                if (!empty($configuration['additionalGetParameters'])) {
                    $query .= '&' . http_build_query($configuration['additionalGetParameters']);
                }

                // Set language parameter
                if ($configuration['useDefaultLanguageRecord'] === false && $currentRecord['sys_language_uid'] > 0) {
                    if ($fieldArray['sys_language_uid'] > 0) {
                        $query .= '&L=' . $currentRecord['sys_language_uid'];
                    }
                }

                // Set plugin dependent variables
                foreach ((array) $configuration['fieldToParameterMap'] as $field => $parameter) {
                    $query .= '&' . $parameter . '=' . ($currentRecord[$field] ? $currentRecord[$field] : 0);
                }
                $GLOBALS['_POST']['popViewId_addParams'] = '&' . trim($query, '&');
            }
        }
    }

    /**
     * Get 7.2 feature functionality
     * See Feature: #66370 - Add flexible Preview URL configuration
     *
     * @param integer $id
     * @param string $table
     * @return array
     */
    protected function getFutureFeatureConfiguration($id, $table)
    {
        $configuration = null;
        $TSConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($id);
        if (isset($TSConfig['TCEMAIN.'], $TSConfig['TCEMAIN.']['preview.'], $TSConfig['TCEMAIN.']['preview.'][$table . '.'])) {
            $configuration = $TSConfig['TCEMAIN.']['preview.'][$table . '.'];

            $configuration = $this->parseTypoScriptArray($configuration);
        }
        return $configuration;
    }

    /**
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUserAuthentication()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Remove all trailing dots from configuration
     *
     * @param array $array
     * @return array
     */
    protected function parseTypoScriptArray($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->parseTypoScriptArray($value);
            }
            $result[rtrim($key, '.')] = $value;
        }

        return $result;
    }
}