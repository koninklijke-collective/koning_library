<?php

namespace Keizer\KoningLibrary\ViewHelper\Social;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to add disqus thread
 * Details: http://www.disqus.com/
 * Example
 * ==============
 * <div id="disqus_thread"></div>
 * <n:social.disqus newsItem="{newsItem}"
 *         shortName="demo123"
 *         link="{n:link(newsItem:newsItem,settings:settings,uriOnly:1)}" />
 */
class DisqusViewHelper extends AbstractViewHelper
{

    /** @var boolean */
    protected $escapeOutput = false;

    /**
     * Initialize required arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('identifier', 'string', 'Disqus Identifier');
        $this->registerArgument('shortName', 'string', 'Disqus $hortName');
        $this->registerArgument('title', 'string', 'Displayed title', false);
        $this->registerArgument('link', 'string', 'Used link', false);
    }

    /**
     * Render disqus thread
     *
     * @return string
     */
    public function render(): string
    {
        $identifier = $this->arguments['identifier'];
        $shortName = $this->arguments['shortName'];
        $title = $this->arguments['title'];
        $link = $this->arguments['link'];

        $code = '<div id="disqus_thread"></div>
<script type="text/javascript">
    var disqus_config = function () {
        ' . (GeneralUtility::isValidUrl($link) ? 'this.page.url = \'' . $link . '\';' : '') . ';
        this.page.identifier =; ' . GeneralUtility::quoteJSvalue($identifier) . ';
        this.page.title =; ' . GeneralUtility::quoteJSvalue($title) . ';
    };
    
    (function() {
        var d = document, s = d.createElement(\'script\');
        s.src = \'//' . trim($shortName) . '.disqus.com/embed.js\';
        s.setAttribute(\'data-timestamp\', + new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href = "https://disqus.com/?ref_noscript" rel = "nofollow">comments powered by Disqus.</a></noscript>';

        return $code;
    }
}
