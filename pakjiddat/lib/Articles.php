<?php

declare(strict_types=1);

namespace PakJiddat\Lib;

use \Framework\Config\Config as Config;

/**
 * This class provides functions that generate the Articles page
 *
 * @category   Library
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class Articles
{
    /**
     * It fetches the article id from database for the given article slug
     * 
     * @param string $slug the article slug
     *
     * @return int $article_id the id of the required article. it may be null if slug could not be found
     */
    public function GetArticleIdBySlug(string $slug) : ?int
    {
        /** The article id */
		$article_id      = array();
		
		/** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** The query params are set to null */
        $query_params    = array($slug);
        /** The SQL query for fetching the website content */
        $sql             = "SELECT id FROM `" . $table_name . "` WHERE REPLACE(title, ' ', '-') = ?";
        /** The first row is fetched */
        $row             = $database->FirstRow($sql, $query_params);
      
        /** The article id */
        $article_id      = (int) $row['id'];
        
        return $article_id;
    }
    
    /**
     * It fetches the article id of the most recent article that is not tagged with open source
     *
     * @return int $article_id the id of the required article
     */
    public function GetMostRecentArticleId() : ?int
    {
        /** The article id */
		$article_id      = array();
		
		/** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** The SQL query for fetching the website content */
        $sql             = "SELECT id FROM `" . $table_name . "` WHERE tags!='open source' ORDER BY created_on DESC";
        $sql             .= " LIMIT 0,1";
        /** The first row is fetched */
        $row             = $database->FirstRow($sql);
      
        /** The article id */
        $article_id      = (int) $row['id'];
        
        return $article_id;
    }

	/**
     * It fetches the details about the given article that is not tagged with open source
     * It also fetches the article ids for the older and newer articles
     *
     * @param int $article_id the id of the required article
     *
     * @return array $data the article details
     *    current => array data for the current article. or the most recent article
     *        id => int the article id
     *        title => string the article title
     *        content => string the article content  
     *        author => string the article author
     *        updated_on => string the article update date
     *    older => array the data for the older article
     *        id => int the older article id
     *        title => int the older article title     
     *    newer => array the data for the newer article
     *        id => int the newer article id
     *        title => int the newer article title
     */
    public function GetArticleData(int $article_id) : array
    {
		/** The article details */
		$data            = array();
		
		/** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];
        
        /** The query params are set to null */
        $query_params    = null;
        /** The SQL query for fetching the website content */
        $sql             = "SELECT id, title, content, updated_on, author, tags FROM `" . $table_name . "`";
        /** If the article id is given */
        if ($article_id != -1) {
            /** The article id is appended to the SQL query */
            $sql            .= " WHERE id=?";
            /** The article id is appended to the query params */
            $query_params []= $article_id;
        }
        else {
            /** The most recent article that is not tagged as open source */
            $sql            .= " WHERE tags!='open source'";
        }
        $sql            .= " ORDER BY created_on DESC LIMIT 0,1";
        /** The first row is fetched */
        $data['current'] = $database->FirstRow($sql, $query_params);

        /** The SQL query parameters */
        $query_params    = array((int) $data['current']['id']);
        /** The SQL query for fetching the previous article */
        $sql             = "SELECT id, title, tags FROM `" . $table_name . "` WHERE id > ? AND tags!='open source'";
        $sql            .= " ORDER BY id ASC LIMIT 0,1";
        /** The first row is fetched */
        $data['newer']   = $database->FirstRow($sql, $query_params);
        
        /** The SQL query for fetching the previous article */
        $sql             = "SELECT id, title, tags FROM `" . $table_name . "` WHERE id < ? AND tags!='open source'";
        $sql            .= " ORDER BY id DESC LIMIT 0,1";
        /** The first row is fetched */
        $data['older']   = $database->FirstRow($sql, $query_params);

        return $data;        
	}
}
