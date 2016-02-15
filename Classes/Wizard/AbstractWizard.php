<?php
namespace Keizer\KoningLibrary\Wizard;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract Wizard
 *
 * @package Keizer\KoningLibrary\Wizard
 */
abstract class AbstractWizard implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var string
     */
    protected $extensionName = 'koning_library';

    /**
     * @var string
     */
    protected $localisationFile = 'Resources/Private/Language/locallang_be.xlf';

    /**
     * @var array
     */
    protected $localisation;

    /**
     * Get configured label from localisation
     *
     * @param string $identifier
     * @return string
     */
    protected function getLabel($identifier)
    {
        $label = $this->getLanguageService()->getLLL($identifier, $this->getLocalisation());
        return ($label) ? $label : $identifier;
    }

    /**
     * Get icon from resource public folder
     *
     * @param string $filename
     * @return string
     */
    protected function getIcon($filename)
    {
        return ExtensionManagementUtility::extRelPath($this->extensionName) . 'Resources/Public/Icons/' . $filename;
    }

    /**
     * @return array
     */
    protected function getLocalisation()
    {
        if ($this->localisation === null) {
            $llFile = GeneralUtility::getFileAbsFileName('EXT:' . $this->extensionName . '/' . $this->localisationFile);
            $this->localisation = $this->getLanguageService()->includeLLFile($llFile, false, true);
        }
        return $this->localisation;
    }

    /**
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Processing the wizard items array
     *
     * @param array $wizardItems : The wizard items
     * @return array Modified array with wizard items
     */
    abstract public function proc($wizardItems);
}