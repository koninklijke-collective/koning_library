<?php

declare(strict_types=1);

namespace Keizer\KoningLibrary\Routing\Aspect;

use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;

/**
 * Mapper for having a static value without any processing.
 *
 * routeEnhancers:
 *   YourExamplePlugin:
 *     type: Plugin
 *     routePath: '/people/{slug}'
 *     namespace: 'tx_yourexample_plugin'
 *     _arguments:
 *       slug: identifier
 *     requirements:
 *       slug: '.*'
 *     aspects:
 *       slug:
 *         type: SimpleValueMapper
 */
final class SimpleValueMapper implements StaticMappableAspectInterface
{
    /**
     * @param  string  $value
     * @return string
     */
    public function generate(string $value): ?string
    {
        return $this->trim($value);
    }

    /**
     * @param  string  $value
     * @return string|null
     */
    public function resolve(string $value): ?string
    {
        return $this->trim($value);
    }

    /**
     * @param  string  $value
     * @return string|null
     */
    protected function trim(string $value): ?string
    {
        $value = trim($value);

        return !empty($value) ? $value : null;
    }
}
