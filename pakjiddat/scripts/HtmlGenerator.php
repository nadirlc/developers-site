<?php

declare(strict_types=1);

namespace PakJiddat\Scripts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides functions that generate html files
 *
 * @category   Scripts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class HtmlGenerator extends \Framework\Application\CommandLine
{
    /**
     * It converts each web page in database to html and saves the result to data folder
     *
     * It generates list of all html pages
     * It then saves the result to data/html folder
     *
     * @return string $response the function response
     */
    public function GenerateHtml() : string
    {
        /** The list of all articles is fetched */
        $articles         = $this->GetArticles();

        /** The path to the data folder */
        $data_folder      = Config::$config['path']['app_path'] . DIRECTORY_SEPARATOR . "data";
        /** The path to the html folder*/
        $html_folder      = $data_folder . DIRECTORY_SEPARATOR . "html";

        /** Each article is saved */
        for ($count = 0; $count < count($articles); $count++) {
            /** The article slug */
            $slug       = $articles[$count]["slug"];
            /** The article contents */
            $content    = $articles[$count]["content"];

            /** The name of the html file */
            $file       = $html_folder . DIRECTORY_SEPARATOR . $slug . ".html";
            /** The file is created */
            UtilitiesFramework::Factory("filemanager")->WriteLocalFile($content, $file);

            /** The status text is shown */
            echo "\n" . ($count + 1) . ". Saved file " . $slug . ".html";

            /** Execution is paused for 1 sec */
            sleep(1);
        }


        /** The function response */
        $response    = "\n\n" . count($articles) . " articles were successfully saved to data/html folder\n\n";

        return $response;
    }

    /**
     * It fetches all the articles
     *
     * @return array $articles the list of all articles is fetched
     */
    private function GetArticles() : array
    {
        /** The list of all articles */
        $articles        = array();
        /** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		    /** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];

        /** The SQL query for fetching the website content */
        $sql             = "SELECT * FROM `" . $table_name . "` ORDER BY created_on DESC";
        /** All rows are fetched */
        $data            = $database->AllRows($sql);

        /** If data was found */
        if (is_array($data)) {
            /** Each article is checked */
            for ($count = 0; $count < count($data); $count++) {
                /** The article */
                $article         = $data[$count];
                /** The article content */
                $content         = ($article['content']);
                /** The article title */
                $title           = ($article['title']);
                /** The article slug */
                $slug            = Config::GetComponent("viewarticlepage")->GetSlug($title);

                /** The article list is updated */
                $articles       []= array("content" => $content, "slug" => $slug);
            }
        }

        return $articles;
    }
}
