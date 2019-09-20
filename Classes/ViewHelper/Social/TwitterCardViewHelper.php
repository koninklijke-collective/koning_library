<?php

namespace Keizer\KoningLibrary\ViewHelper\Social;

use Exception;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\MetaTag\TwitterCardMetaTagManager;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render twitter summary cards.
 *
 * @deprecated use ext:seo TwitterCard ViewHelper
 */
class TwitterCardViewHelper extends AbstractViewHelper
{

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('title', 'string', 'Title', true);
        $this->registerArgument('description', 'string', 'Description', true);
        $this->registerArgument('site', 'string', 'Username', false, '@RSMErasmus');

        // Image attributes
        $this->registerArgument('image', 'string', 'Image', false, null);

        // Player attributes
        $this->registerArgument('player', 'string', 'Player', false, null);
        $this->registerArgument('playerWidth', 'integer', 'Player Width', false, null);
        $this->registerArgument('playerHeight', 'integer', 'Player Height', false, null);
        $this->registerArgument('stream', 'string', 'Player Stream location (mp4)', false, null);
        $this->registerArgument('streamType', 'string', 'Player Stream MimeType', false, 'video/mp4');
    }

    /**
     * Renders a generic twitter card based on given parameters
     *
     * @see https://dev.twitter.com/cards/types/summary Card, site, title and description are required properties.
     * @see https://dev.twitter.com/cards/markup Full list of supported properties:
     * @return void
     */
    public function render(): void
    {
        // Only render ViewHelper in frontend
        if ('FE' === TYPO3_MODE) {
            // Define card per given arguments
            $card = 'summary';

            $image = null;
            if (!empty($this->arguments['image'])) {
                try {
                    $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
                    $image = $resourceFactory->getFileObjectFromCombinedIdentifier($this->arguments['image']);

                    if ($image instanceof File && $image->getProperty('width') > 280) {
                        $card = 'summary_large_image';
                    }
                } catch (Exception $e) {
                }
            }

            if (!empty($this->arguments['player'])) {
                $card = 'player';
            }

            // Add all attributes as meta tags
            $twitterCardTagManager = $this->getTwitterCardTagManager();

            // Add default required fields
            $twitterCardTagManager->addProperty('twitter:card', $card);
            $twitterCardTagManager->addProperty('twitter:site', $this->arguments['site']);
            $twitterCardTagManager->addProperty('twitter:title', $this->arguments['title']);
            $twitterCardTagManager->addProperty('twitter:description', $this->arguments['description']);

            if ($image instanceof File) {
                $imageUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . ltrim($image->getPublicUrl(), '/');
                $twitterCardTagManager->addProperty(
                    'twitter:image',
                    $imageUrl,
                    ['width' => $image->getProperty('width'), 'height' => $image->getProperty('height')]
                );
            }

            if (!empty($this->arguments['player'])) {
                $subProperties = [
                    'width' => $this->arguments['playerWidth'],
                    'height' => $this->arguments['playerHeight'],
                ];

                if (!empty($this->arguments['stream'])) {
                    $subProperties['stream'] = $this->arguments['stream'];
                    $subProperties['stream:content_type'] = $this->arguments['streamType'];
                }

                $twitterCardTagManager->addProperty(
                    'twitter:player',
                    $this->arguments['player'],
                    $subProperties
                );
            }
        }
    }

    /**
     * @return \TYPO3\CMS\Seo\MetaTag\TwitterCardMetaTagManager
     */
    protected function getTwitterCardTagManager(): TwitterCardMetaTagManager
    {
        return GeneralUtility::makeInstance(TwitterCardMetaTagManager::class);
    }
}
