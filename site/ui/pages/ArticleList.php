<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Pages;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class is used to generate the body for the article list and search pages
 *
 * @category   Page
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class ArticleList extends BasePage
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
        /** The current url is parsed */
		$url_parts   = explode("/", Config::$config["general"]["request_uri"]);
		
		/** The text to search */
        $text        =  "";
        /** The description text */
        $description = "";
		/** If the articles need to be fetched by tag or by search text */
		if (isset($url_parts[2]) && ($url_parts[2] == "tag" || $url_parts[2] == "search")) {
            /** The text to search */
            $text =  $url_parts[3];
            /** The text is formatted */
            $text = trim(ucwords(str_replace("-", " ", $text)));
        }
        
        /** If the article tags need to be searched */
        if ($url_parts[2] == "tag") {
            /** The description text */
            $description = " Articles tagged with '" . $text . "'";
        }
        /** If the article content need to be searched */
        else if ($url_parts[2] == "search") {
            /** The description text */
            $description = " Articles containing the text " . $text;
        }
        
        /** The dbinit object is fetched */
        $dbinit      = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database    = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name  = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** If the tags need to be searched */
        if ($url_parts[2] == "tag") {
            /** The SQL query for fetching all articles under the given tag */
            $sql          = "SELECT * FROM `" . $table_name . "` WHERE tags LIKE ? ";
            /** The SQL query parameters */
            $query_params = array("%" . $text . "%");
        }
        /** If the website content need to be searched */
        else if ($url_parts[2] == "search") {
            /** The SQL query for fetching all articles under the given tag */
            $sql          = "SELECT * FROM `" . $table_name . "` WHERE content LIKE ? OR title LIKE ?";
            /** The SQL query parameters */
            $query_params = array("%" . $text . "%", "%" . $text . "%");
        }
        
        $sql             .= "ORDER BY id DESC";
        /** All rows are fetched */
        $rows            = $database->AllRows($sql, $query_params);

        /** The tag values used to generate the article list */
        $list_tag_values = array();
        /** The article list */
        $article_list    = "";
        
        /** If articles were found */
        if (is_array($rows)) {
            /** Each row is checked */
            for ($count = 0; $count < count($rows); $count++) {
                /** The article slug */
	            $slug              = $this->GetSlug($rows[$count]['title']);
        	    /** The article url */
        	    $article_url       = "/articles/view/" . $rows[$count]['id'] . "/" . $slug;
        	    /** The date of update */
                $updated_on        = date("d-m-Y", (int) $rows[$count]['updated_on']);
                /** The tag values are updated */
                $list_tag_values  []= array(
                                     "text" => $rows[$count]['title'],
                                     "link" => $article_url,
                                     "updated_on" => $updated_on
                                 );
            }                        
            /** The article list is generated */
            $article_list      = Config::GetComponent("templateengine")->Generate("article", $list_tag_values);
        }
        
        /** The title for the page */
        Config::$config['custom']['title'] = $text;
        
        /** The search box is generated */
        $sb_html    = Config::GetComponent("templateengine")->Generate("search_box", array());
        /** The bottom boxes html is generated */
        $box_html   = $this->GetBottomBoxes();
        /** The tag values used to generate the article list and search pages */
        $tag_values = array(
                          "description" => $description,
                          "articles" => $article_list,
                          "search_box" => $sb_html,
                          "bottom_boxes" => $box_html
                      );
        
        /** The article list is generated */
        $page_html  = Config::GetComponent("templateengine")->Generate("list", $tag_values);
        
        return $page_html;
	}
}
