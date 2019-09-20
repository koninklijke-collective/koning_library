<?php

namespace Keizer\KoningLibrary\Controller;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Controller: Abstract Action
 */
abstract class AbstractActionController extends ActionController
{
    /**
     * Translate mapping for <ext>/Resources/Private/Language/locallang.xlf
     *
     * @param  string  $key
     * @param  array  $arguments
     * @return string
     */
    protected function translate(string $key, ?array $arguments = null): string
    {
        $translation = LocalizationUtility::translate(
            $key,
            $this->request->getControllerExtensionName(),
            $arguments
        );

        return (!empty($translation) ? $translation : $key);
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected function getPageRenderer(): PageRenderer
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected function getObjectManager(): ObjectManagerInterface
    {
        if ($this->objectManager === null) {
            $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        }

        return $this->objectManager;
    }
}
