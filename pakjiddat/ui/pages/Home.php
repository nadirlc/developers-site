<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Pages;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class implements the Home page class
 * It is used to generate the home pages
 *
 * @category   Page
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class Home extends BasePage
{
    /**
     * It provides the html for the page body
     *
     * @param array $params the parameters for generating the body
     *
     * @return string $body_html the html for the body
     */
    protected function GetBody(?array $params = null) : string
    {
        /** The bottom boxes html is generated */
        $box_html   = $this->GetBottomBoxes();
        /** The tag values for the home page body */
        $tag_values = array("bottom_boxes" => $box_html, "ic_link" => $params['tag_values']['ic_link']);
        /** The html for the home page body is generated */
    	$body_html  = Config::GetComponent("templateengine")->Generate("home", $tag_values);
    	
    	return $body_html;
    }
}
