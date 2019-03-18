<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Parts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class implements the TocList component class
 * It is used to generate the table of contents component
 *
 * @category   Parts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class TocBox
{
    /**
     * It generates table of contents for the given article text
     *
     * @param string $toc_list the table of contents list
     *
     * @return string $toc_box_html the table of contents box
     */
    public function Generate(string $toc_list) : string
    {
        /** The extra css styles for the toc box. It is used to hide the box if there are no headings in the article */
        $css          = ($toc_list == "<ul></ul>") ? "hidden" : "visible";
        /** The tag values for generating the toc */
        $tag_values   = array("toc_list" => $toc_list, "css" => $css);        
        /** The toc list is generated */
        $toc_box_html = Config::GetComponent("templateengine")->Generate("toc", $tag_values);
                  
        return $toc_box_html;        
	}
}
