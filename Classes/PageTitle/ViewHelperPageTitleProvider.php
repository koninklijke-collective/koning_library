<?php

namespace Keizer\KoningLibrary\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

/**
 * Used in config.pageTitleProviders
 */
final class ViewHelperPageTitleProvider extends AbstractPageTitleProvider
{
    /**
     * @param  string  $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
