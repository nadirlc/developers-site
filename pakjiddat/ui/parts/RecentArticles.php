<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Parts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class implements the RecentArticles component class
 * It is used to generate the recent articles component
 *
 * @category   Parts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class RecentArticles
{
    /**
     * It generates the recent articles box
     *
     * @return string $box_html the recent articles box html
     */
    public function Generate() : string
    {
        /** The dbinit object is fetched */
        $dbinit      = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database    = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name  = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** The SQL query for fetching the website content */
        $sql         = "SELECT id, title, updated_on FROM `" . $table_name . "` WHERE tags!='open source'";
        $sql        .= " ORDER BY created_on DESC LIMIT 0,15";
        /** All rows are fetched */
        $data        = $database->AllRows($sql);
        
        /** If articles were found */
        if (is_array($data)) {
            /** The list of recent articles is initialized */
            $articles    = array();
            /** Each article is checked */
            for ($count = 0; $count < count($data); $count++) {
                /** The article title */
                $title         = ($data[$count]['title']);
                /** The article link */
                $link          = "/articles/view/" . $data[$count]['id'] . "/";
                $link         .= Config::GetComponent("viewarticlepage")->GetSlug($title);
                /** The date of update */
                $updated_on    = date("d-m-Y", (int) $data[$count]['updated_on']);
                /** The formatted tag list parameters are updated */
                $articles     []= array("text" => $title, "link" => $link, "updated_on" => $updated_on);
            }
            
            /** Array chunk is converted to html list items */
            $tag_values['recent_list']   = Config::GetComponent("templateengine")->Generate(
                                                "article",
                                                $articles
                                             );
        }
        /** If articles were not found */
        else {
            /** The tag values are set to empty */
            $tag_values['recent_list']   = "";
        }
                                                 
        /** The category list box is generated */
        $box_html                     = Config::GetComponent("templateengine")->Generate(
                                            "article_box",
                                            $tag_values
                                        );                                           

        return $box_html;        
	}
}
