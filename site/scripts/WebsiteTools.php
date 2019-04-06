<?php

declare(strict_types=1);

namespace PakJiddat\Scripts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides functions that implement website tools
 *
 * @category   Scripts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class WebsiteTools extends \Framework\Application\CommandLine
{
    /**
     * It generates a simple sitemap and saves it to the data folder
     *
     * It first generates list of all category urls.
     * It then generates list of all article urls.
     * It merges the two url lists and saves the result to data/sitemap.txt
     * It also imports the site map to the pakphp_test_data database table
     *
     * @return string $response the function response
     */
    public function GenerateSiteMap() : string
    {
        /** The list of all category urls is fetched */
        $cat_urls      = $this->GetCategoryUrls();
        /** The list of all article urls is fetched */
        $art_urls      = $this->GetArticleUrls();
        /** The url lists are merged */
        $url_list      = array_merge($cat_urls, $art_urls);
        /** The file contents */
        $file_contents = implode("\n", $url_list);
        
        /** The path to the data folder */
        $data_folder = Config::$config['path']['app_path'] . DIRECTORY_SEPARATOR . "data";
        /** The path to the site maps file */
        $file_path   = $data_folder . DIRECTORY_SEPARATOR . "sitemap.txt";
        /** The file is written */
        UtilitiesFramework::Factory("filemanager")->WriteLocalFile($file_contents, $file_path);
        /** The urls are saved to database */
        $this->SaveUrlsToDb($url_list);
        
        $response    = "\n" . count($url_list) . " urls were successfully written to data/sitemap.txt";
        $response    .= "\nThe urls were also saved to the table pakphp_test_data\n\n";
        
        return $response;
    }
    
    /**
     * It saves the given url list to pakphp_test_data table
     *
     * @param array $url_list the list of urls to save
     */
    private function SaveUrlsToDb(array $url_list) : void
    {
        /** The dbinit object is fetched */
        $dbinit            = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database          = $dbinit->GetDbManagerClassObj("Database");        
        /** The DbMetaQueryRunner class object is fetched */
        $dbmetaqueryrunner = $dbinit->GetDbManagerClassObj("DbMetaQueryRunner");
		/** The table name for website content */
        $table_name        = Config::$config['general']['mysql_table_names']['test_data'];
        /** The table is truncated */
        $dbmetaqueryrunner->TruncateTable($table_name);
        
        /** Each url is saved to table */
        for ($count = 0; $count < count($url_list); $count++) {
            /** The url to save */
            $url    = $url_list[$count];
            /** The sql parameter values */
            $params = array($url, 0, '[]', "Pak Jiddat", time());
            /** The SQL query for adding the url */
            $sql    = "INSERT INTO `" . $table_name . "`";
            $sql    .= " (url, is_checked, params, app_name, created_on) VALUES (?,?,?,?,?)";
            /** The row is added */
            $database->Execute($sql, $params);
        }
    }

    /**
     * It fetches all the article urls
     *
     * @return array $url_list the list of all article urls
     */
    private function GetArticleUrls() : array
    {
        /** The list of all article urls */
        $url_list        = array();
        /** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
        /** The site url */
        $site_url        = Config::$config['general']['site_url'];
		/** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** The SQL query for fetching the website content */
        $sql             = "SELECT id, title, updated_on FROM `" . $table_name . "` ORDER BY created_on DESC";
        /** All rows are fetched */
        $data            = $database->AllRows($sql);

        /** If categories were found */
        if (is_array($data)) {
            /** Each article is checked */
            for ($count = 0; $count < count($data); $count++) {
                /** The article title */
                $title         = ($data[$count]['title']);
                /** The article link */
                $link          = $site_url . "/articles/view/" . $data[$count]['id'] . "/";
                $link         .= Config::GetComponent("viewarticlepage")->GetSlug($title);
                /** The url list is updated */
                $url_list     []= $link;
            }
        }
        
        return $url_list;
    }
        
    /**
     * It fetches all the category urls
     *
     * @return array $url_list the list of all category urls
     */
    private function GetCategoryUrls() : array
    {
        /** The list of all category urls */
        $url_list        = array();
        /** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];
        /** The site url */
        $site_url        = Config::$config['general']['site_url'];
                
        /** The SQL query for fetching the website content */
        $sql             = "SELECT DISTINCT tags FROM `" . $table_name . "`";
        /** All rows are fetched */
        $data            = $database->AllRows($sql);
        
        /** The tag list is initialized */
        $tag_list        = array();
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
                        $link             = $site_url . "/articles/tag/";
                        $link             .= Config::GetComponent("viewarticlepage")->GetSlug($tag_value);
                        /** The url list is updated */
                        $url_list         []= $link;
                    }
                }
            }
        }
        
        return $url_list;
    }
}
