<?php

namespace Keizer\KoningLibrary\ViewHelper;

use Keizer\KoningLibrary\Utility\ResourceUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Retrieve reference details from given content
 * = Examples =
 * <code title="Default">
 * <l:reference uid="231" table="tt_content" field="settings.data.image">
 *      {references -> f:debug}
 * </l:reference>
 * </code>
 */
class ReferenceViewHelper extends AbstractViewHelper
{

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @param  integer  $uid
     * @param  string  $table
     * @param  string  $field
     * @param  string  $as
     * @return string path to the image
     */
    public function render($uid, $table = 'tt_content', $field = 'image', $as = 'references')
    {
        $references = ResourceUtility::getReferenceObjects($uid, $table, $field);
        $this->templateVariableContainer->add($as, $references);
        $content = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $content;
    }
}
