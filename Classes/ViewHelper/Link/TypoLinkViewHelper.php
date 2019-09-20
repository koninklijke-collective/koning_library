<?php

namespace Keizer\KoningLibrary\ViewHelper\Link;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * A view helper for creating links to TYPO3 pages.
 * = Examples =
 * <code title="link to the current page">
 * <c:link.typoLink>page link</c:link.typoLink>
 * </code>
 * <output>
 * <a href="index.php?id=123">page link</a>
 * (depending on the current page and your TS configuration)
 * </output>
 * <code title="query parameters">
 * <c:link.typoLink parameter="1">page link</c:link.typoLink>
 * </code>
 * <output>
 * <a href="index.php?id=1">page link</a>
 * (depending on your TS configuration)
 * </output>
 *
 * @deprecated
 */
class TypoLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Configuration Manager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->contentObject = $this->configurationManager->getContentObject();
    }

    /**
     * Register Arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('title', 'string', 'Tooltip text of element');
    }

    /**
     * Render URL
     *
     * @param string $parameter
     * @param array $configuration
     * @return string Rendered page URI
     */
    public function render($parameter = null, $configuration = [])
    {
        $typoLinkConfiguration = [
            'parameter' => ($parameter) ? $parameter : $this->contentObject->data['pid'],
        ];

        if (!empty($configuration)) {
            ArrayUtility::mergeRecursiveWithOverrule($typoLinkConfiguration, $configuration);
        }

        $content = $this->renderChildren();
        return $this->contentObject->typoLink($content, $typoLinkConfiguration);
    }
}
