<?php

namespace Keizer\KoningLibrary\ViewHelper\Social;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
class DisqusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * Render disqus thread
     *
     * @param  integer|string  $uid
     * @param  string  $title
     * @param  string  $shortName
     * @param  string  $link
     * @return string
     */
    public function render($uid, $title, $shortName, $link)
    {
        $code = '<script type="text/javascript">
                    var disqus_shortname = ' . GeneralUtility::quoteJSvalue($shortName, true) . ';
                    var disqus_identifier = \'article_' . $uid . ' . GeneralUtility::quoteJSvalue($link, true) . ';
                    var disqus_title =; ' . GeneralUtility::quoteJSvalue($title, true) . ';

                    (function() {
                        var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
                        dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
                        (document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
                    })();
                </script>
                <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>';

        return $code;
    }
}
