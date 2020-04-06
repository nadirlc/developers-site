<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Parts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class implements the CategoryList component class
 * It is used to generate the category list component
 *
 * @category   Parts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class CategoryList
{
    /**
     * It generates the category list box
     *
     * @return string $box_html the category list box html
     */
    public function Generate() : string
    {		                     
        /** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** The SQL query for fetching the website content */
        $sql             = "SELECT DISTINCT tags FROM `" . $table_name . "`";
        /** All rows are fetched */
        $data            = $database->AllRows($sql);
        
        /** The tag list is initialized */
        $tag_list        = array();
        /** The tag values for the formatted tag list */
        $tag_list_params = array();
        
        /** If categories were found */
        if (is_array($data)) {
            /** Each category is checked */
            for ($count1 = 0; $count1 < count($data); $count1++) {
                /** The tag values are parsed */
                $temp_arr    = explode(",", $data[$count1]['tags']);
                /** Each parsed tag is checked */
                for ($count2 = 0; $count2 < count($temp_arr); $count2++) {
                    /** The first letter of each word in the tag value is converted to uppercase */
                    $tag_value  = ucwords($temp_arr[$count2]);
                    /** If the parsed tag does not exist */
                    if (!in_array($tag_value, $tag_list)) {
                        /** The parsed tag is added to the tag list */
                        $tag_list         []= $tag_value;
                        /** The tag link */
                        $link              = "/articles/tag/";
                        $link              .= Config::GetComponent("viewarticlepage")->GetSlug($tag_value);
                        /** The formatted tag list parameters are updated */
                        $tag_list_params  []= array("text" => $tag_value, "link" => $link);
                    }
                }
            }
            /** The tag list is sorted */
            usort(
                $tag_list_params,
                function ($a, $b) {
                    /** The two strings are compared */
                    return  strcmp($a['text'], $b['text']);
                }
            );
            
            /** The number of tas in one column */
            $tags_in_col                  = (int) ceil(count($tag_list_params)/2);
            /** The tag list is divided into two chunks */
            $array_parts                  = array_chunk($tag_list_params, $tags_in_col);
            /** Array chunk is converted to html list items */
            $tag_values['tag_list1']      = Config::GetComponent("templateengine")->Generate(
                                                "category",
                                                $array_parts[0]
                                            );
            /** Array chunk is converted to html list items */
            $tag_values['tag_list2']      = Config::GetComponent("templateengine")->Generate(
                                                "category",
                                                $array_parts[1]
                                            );
        }
        /** If no categories were found */
        else {
            /** The tag values are set to empty */
            $tag_values['tag_list1']      = "";
            $tag_values['tag_list2']      = "";
        }
        
        /** The category list box is generated */
        $box_html                         = Config::GetComponent("templateengine")->Generate(
                                                "category_box",
                                                $tag_values
                                            );
                                                               
        return $box_html;        
	}
}
