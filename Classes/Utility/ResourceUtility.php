<?php

namespace Keizer\KoningLibrary\Utility;

/**
 * Utility: Resource download
 */
class ResourceUtility
{

    /**
     * Try to retrieve all reference objects
     *
     * @param integer $uid
     * @param string $table
     * @param string $field
     * @return array<\TYPO3\CMS\Core\Resource\FileReference>
     */
    public static function getReferenceObjects($uid, $table, $field)
    {
        $fileReferenceObjects = [];

        /** @var \TYPO3\CMS\Core\Database\DatabaseConnection $database */
        $database = &$GLOBALS['TYPO3_DB'];
        $references = $database->exec_SELECTgetRows(
            'uid',
            'sys_file_reference',
            'tablenames = ' . $database->fullQuoteStr($table, 'sys_file_reference')
            . ' AND fieldname=' . $database->fullQuoteStr($field, 'sys_file_reference')
            . ' AND uid_foreign=' . intval($uid)
            . \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause('sys_file_reference')
            . \TYPO3\CMS\Backend\Utility\BackendUtility::versioningPlaceholderClause('sys_file_reference')
        );

        if (!empty($references)) {
            foreach ($references as $reference) {
                $referenceUid = (int)$reference['uid'];
                if ($referenceUid > 0) {
                    try {
                        $referenceObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileReferenceObject($referenceUid);
                        if ($referenceObject instanceof \TYPO3\CMS\Core\Resource\FileReference) {
                            $fileReferenceObjects[] = $referenceObject;
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
        }
        return $fileReferenceObjects;
    }

    /**
     * Stream context to view and stop processing
     *
     * @param string $fileName
     * @param string $content
     * @param array $additionalHeaders
     */
    public static function stream($fileName, $content, $additionalHeaders = [])
    {
        foreach ($additionalHeaders as $type => $value) {
            header($type . ': ' . $value);
        }

        // Clean filename, stripped from :BasicFileUtility::cleanFileName()
        $fileName = preg_replace('/[\\x00-\\x2C\\/\\x3A-\\x3F\\x5B-\\x60\\x7B-\\xBF]/u', '_', trim($fileName));
        header('Content-Disposition: inline; filename=' . $fileName . ';');

        exit($content);
    }
}
