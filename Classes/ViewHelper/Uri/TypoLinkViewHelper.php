<?php
namespace Keizer\KoningLibrary\ViewHelper\Uri;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * A view helper for creating links to TYPO3 pages.
 * = Examples =
 * <code title="link to the current page">
 * <c:uri.typoLink/>
 * </code>
 * <output>
 * index.php?id=123
 * (depending on the current page and your TS configuration)
 * </output>
 * <code title="query parameters">
 * <c:uri.typoLink parameter="1"/>
 * </code>
 * <output>
 * index.php?id=1
 * (depending on your TS configuration)
 * </output>
 *
 * @package Keizer\KoningLibrary\ViewHelper\Link
 * @deprecated
 */
class TypoLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Configuration Manager
     *
     * @var ConfigurationManagerInterface
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
     * Render URL from typolink configuration
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

        return $this->contentObject->typoLink_URL($typoLinkConfiguration);
    }
}
