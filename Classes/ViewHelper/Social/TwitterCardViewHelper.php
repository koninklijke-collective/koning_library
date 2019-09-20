<?php

namespace Keizer\KoningLibrary\ViewHelper\Social;

use Exception;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render twitter summary cards.
 */
class TwitterCardViewHelper extends AbstractViewHelper
{

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('site', 'string', 'Username', false, '@grandslam_media');

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
     * @param  string  $title
     * @param  string  $description
     */
    public function render($title, $description)
    {
        // Only render ViewHelper in frontend
        if ('FE' === TYPO3_MODE) {
            // Define card per given arguments
            $card = 'summary';

            $image = null;
            if ($this->getArgument('image')) {
                try {
                    $resourceFactory = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
                    $image = $resourceFactory->getFileObjectFromCombinedIdentifier($this->getArgument('image'));

                    if ($image instanceof File && $image->getProperty('width') > 280) {
                        $card = 'summary_large_image';
                    }
                } catch (Exception $e) {
                }
            }

            if ($this->getArgument('player')) {
                $card = 'player';
            }

            // Add all attributes as meta tags
            if ($this->getPageRenderer() !== null) {
                // Add default required fields
                $this->getPageRenderer()->addHeaderData('<meta name="twitter:card" content="' . $card . '">');
                $this->getPageRenderer()
                    ->addHeaderData('<meta name="twitter:site" content="' . $this->getArgument('site') . '">');
                $this->getPageRenderer()->addHeaderData('<meta name="twitter:title" content="' . $title . '">');
                $this->getPageRenderer()
                    ->addHeaderData('<meta name="twitter:description" content="' . $description . '">');

                if ($image instanceof File) {
                    $imageUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . ltrim($image->getPublicUrl(), '/');

                    $this->getPageRenderer()->addHeaderData('<meta name="twitter:image" content="' . $imageUrl . '">');
                    $this->getPageRenderer()
                        ->addHeaderData('<meta name="twitter:image:width" content="' . $image->getProperty('width') . '">');
                    $this->getPageRenderer()
                        ->addHeaderData('<meta name="twitter:image:height" content="' . $image->getProperty('height') . '">');
                }

                if ($this->getArgument('player')) {
                    $this->getPageRenderer()
                        ->addHeaderData('<meta name="twitter:player" content="' . $this->getArgument('player') . '">');
                    $this->getPageRenderer()
                        ->addHeaderData('<meta name="twitter:player:width" content="' . $this->getArgument('playerWidth') . '">');
                    $this->getPageRenderer()
                        ->addHeaderData('<meta name="twitter:player:height" content="' . $this->getArgument('playerHeight') . '">');

                    if ($this->getArgument('stream')) {
                        $this->getPageRenderer()
                            ->addHeaderData('<meta name="twitter:player:stream" content="' . $this->getArgument('stream') . '">');
                        $this->getPageRenderer()
                            ->addHeaderData('<meta name="twitter:player:stream:content_type" content="' . $this->getArgument('streamType') . '">');
                    }
                }
            }
        }
    }

    /**
     * Get argument based on key
     *
     * @param  string  $key
     * @return string
     */
    protected function getArgument($key)
    {
        return ((isset($this->arguments[$key]) && !empty($this->arguments[$key])) ? $this->arguments[$key] : null);
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected function getPageRenderer()
    {
        if ('FE' === TYPO3_MODE && is_callable([$this->getTypoScriptFrontendController(), 'getPageRenderer'])) {
            return $this->getTypoScriptFrontendController()->getPageRenderer();
        } else {
            return GeneralUtility::makeInstance('TYPO3\CMS\Core\Page\PageRenderer');
        }
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
