<?php

declare(strict_types=1);

namespace Keizer\KoningLibrary\Routing\Aspect;

use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Routing\Aspect\AspectFactory;
use TYPO3\CMS\Core\Routing\Aspect\MappableAspectInterface;
use TYPO3\CMS\Core\Routing\Aspect\PersistedMappableAspectInterface;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Very useful for building an a path segment from a combined value of the database.
 * Sanitize full aspect output.
 *
 * Example:
 *   routeEnhancers:
 *     EventsPlugin:
 *       type: Extbase
 *       extension: Events2
 *       plugin: Pi1
 *       routes:
 *         - { routePath: '/events/{event}', _controller: 'Event::detail', _arguments: {'event': 'event_name'}}
 *       defaultController: 'Events2::list'
 *       aspects:
 *         event:
 *           type SanitizedValueMapper
 *           renderType: PersistedPatternMapper
 *           tableName: 'tx_events2_domain_model_event'
 *           routeFieldPattern: '^(?P<title>.+)-(?P<uid>\d+)$'
 *           routeFieldResult: '{title}-{uid}'
 *
 * @see https://forge.typo3.org/issues/86797 inspired to sanitize output based on generic rendered type
 */
final class SanitizedValueMapper implements PersistedMappableAspectInterface, StaticMappableAspectInterface
{
    use SiteLanguageAwareTrait;

    /** @var MappableAspectInterface */
    protected $aspect;

    /** @var string */
    protected $renderType;

    /** @var array */
    protected $configuration;

    /**
     * @param  array  $settings
     */
    public function __construct(array $settings)
    {
        $this->renderType = $settings['renderType'] ?? '';
        $this->configuration = $settings;
    }

    /**
     * @param  string  $value
     * @return string|null
     */
    public function generate(string $value): ?string
    {
        try {
            $output = $this->getAspectFromRenderType()->generate($value);

            return $this->getSlugHelper()->sanitize($output);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param  string  $value
     * @return string|null
     */
    public function resolve(string $value): ?string
    {
        try {
            return $this->getAspectFromRenderType()->resolve($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return \TYPO3\CMS\Core\Routing\Aspect\MappableAspectInterface
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     */
    protected function getAspectFromRenderType(): MappableAspectInterface
    {
        if ($this->aspect === null) {
            $configuration = $this->configuration;
            $configuration['type'] = $configuration['renderType'];
            unset($configuration['renderType']);

            $aspects = GeneralUtility::makeInstance(AspectFactory::class)
                ->createAspects([$configuration], $this->siteLanguage);
            $this->aspect = $aspects[0] ?? null;
        }

        return $this->aspect;
    }

    /**
     * @return \TYPO3\CMS\Core\DataHandling\SlugHelper
     */
    protected function getSlugHelper(): SlugHelper
    {
        return GeneralUtility::makeInstance(
            SlugHelper::class,
            $this->configuration['tableName'],
            '',
            []
        );
    }
}
