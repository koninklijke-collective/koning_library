<?php

namespace Keizer\KoningLibrary\ViewHelper\Head;

/**
 * ViewHelper for <title> tag
 * == Example ==
 * <n:titleTag>{newsItem.title}</n:titleTag>
 * == Result ==
 * Sets the title of the news record as title tag
 */
class TitleTagViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Override the title tag
     *
     * @return void
     */
    public function render()
    {
        if ('FE' === TYPO3_MODE) {
            $content = $this->renderChildren();
            if (!empty($content)) {
                $content = trim($content);
                if ($this->getTypoScriptFrontendController() !== null) {
                    $this->getTypoScriptFrontendController()->indexedDocTitle = $content;
                    $this->getTypoScriptFrontendController()->page['title'] = $content;
                    $this->getTypoScriptFrontendController()->altPageTitle = $content;
                }
            }
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
