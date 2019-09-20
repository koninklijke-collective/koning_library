<?php

namespace Keizer\KoningLibrary\Utility;

use Exception;
use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility: Resource download
 */
class ResourceUtility
{
    /**
     * Try to retrieve all reference objects
     *
     * @param  integer  $uid
     * @param  string  $table
     * @param  string  $field
     * @return array<\TYPO3\CMS\Core\Resource\FileReference>
     */
    public static function getReferenceObjects(int $uid, string $table, string $field): array
    {
        $fileReferenceObjects = [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $query = $queryBuilder
            ->select('*')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq(
                    'tablenames',
                    $queryBuilder->createNamedParameter($table)
                ),
                $queryBuilder->expr()->eq(
                    'fieldname',
                    $queryBuilder->createNamedParameter($field)
                ),
                $queryBuilder->expr()->eq(
                    'uid_foreign',
                    $queryBuilder->createNamedParameter($field, PDO::PARAM_INT)
                )
            );
        $result = $query->execute();
        while ($reference = $result->fetch()) {
            $referenceUid = (int)$reference['uid'];
            if ($referenceUid > 0) {
                try {
                    $fileReferenceObjects[] = ResourceFactory::getInstance()
                        ->getFileReferenceObject($referenceUid);
                } catch (Exception $e) {
                }
            }
        }

        return array_filter($fileReferenceObjects);
    }

    /**
     * Stream context to view and stop processing
     *
     * @param  string  $fileName
     * @param  string  $content
     * @param  array  $additionalHeaders
     */
    public static function stream(string $fileName, string $content, array $additionalHeaders = []): void
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
