<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Parts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides functions for generating the body for the comments box and comments list
 *
 * @category   Parts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class CommentsList
{    
    /**
     * It saves the comments message to database
     * It also emails the comments message to the admin
     *
     * @param string $message the message text
     * @param string $name the users name
     * @param string $url the article url where the comment was posted     
     *
     * @return json $response the function response
     *    message => string the response message
     *    result => string [success,error] the result of the function
     */
    public function AddComment(string $message, string $name, string $url) : string
	{
	    /** The date and time at which the comment was posted */
        $posted_on        = date("d-m-Y H:i:s", time());
        /** The function response */
        $response         = array(
                                "message" => "Comment successfully posted !",
                                "result" => "success",
                                "comment" => $message,
                                "name" => $name,
                                "posted_on" => $posted_on
                            );
        /** The Database object is fetched */
        $database         = Config::GetComponent("dbinit")->GetDbManagerClassObj("Database");            
        /** The subscription table name */
        $table_name       = Config::$config["general"]["mysql_table_names"]["comments"];
        /** The from email address */
        $email_from       = Config::$config["custom"]["comment_config"]["email_from"];
        /** The admin email address */
        $email_to         = Config::$config["custom"]["comment_config"]["email_to"];
        /** The subject of contact email */
        $subject          = Config::$config["custom"]["comment_config"]["email_subject"];
        /** The url is parsed */
        $temp_arr         = explode("/", $url);
        /** The article id */
        $article_id       = $temp_arr[5] ?? -1;
        /** If the article id is not given */
        if ($article_id == -1) {
            /** The article id of the most recent article is fetched */
            $article_id   = Config::GetComponent("articleslib")->GetMostRecentArticleId();
        }
        /** The url is converted to absolute url */
        $url              = $url . "#user-comments";
        
        /** The sql query for adding subscriber */       
        $sql              = "INSERT INTO " . $table_name . " (name, message, article_id, created_on) VALUES(?,?,?,?)";
        /** The query parameters */
        $query_params     = array($name, $message, $article_id, time());
        /** The row is added */
        $is_run           = $database->Execute($sql, $query_params);
        
        /** The email text */
        $text             = <<< EOT
        
        
    Hello Admin !. The following comment was posted on Pak Jiddat website:
        
    Name: $name
    Message: $message
    Url: $url
    Time: $posted_on
EOT;
        
        /** The parameters for the email object */
        $params           = array("params" => array(), "backend" => "mail");      
        /** The email is sent */
        $is_sent          = UtilitiesFramework::Factory("email", $params)->SendEmail(
                                $email_from,
                                $email_to,
                                $subject,
                                $text
                            );
        
        /** If the sql query could not be run or the email could not be sent */
        if (!$is_run || !$is_sent) {
            /** The function response */
            $response     = array(
                                "message" => "Your comment could not be posted. Please send an email to " . $email_from,
                                "result" => "error"
                            );
        }
        
        /** The response is json encoded and sent */
        return json_encode($response);
	}
	
	/**
     * It returns the article comments
     *
     * @return string $comments_html the html for the comments box and comments list
     */
    public function Generate() : string
	{
	    /** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		/** The table name for comments */
        $table_name      = Config::$config['general']['mysql_table_names']['comments'];
        /** The article url */
        $url             = Config::$config['general']['site_url'] . Config::$config['general']['request_uri'];
        /** The url is parsed */
        $temp_arr        = explode("/", $url);
        /** The article id */
        $article_id      = $temp_arr[5] ?? -1;
        /** If the article id is not given */
        if ($article_id == -1) {
            /** The article id of the most recent article is fetched */
            $article_id  = Config::GetComponent("articleslib")->GetMostRecentArticleId();
        }
        
        /** The SQL query for fetching the website content */
        $sql             = "SELECT * FROM `" . $table_name . "` WHERE article_id=?";
        /** The query parameters */
        $query_params    = array($article_id);
        /** All rows are fetched */
        $data            = $database->AllRows($sql, $query_params);
        
        /** The list of all comments */
        $comments        = array();
        /** Each comment is generated */
        for ($count = 0; is_array($data) && $count < count($data); $count++) {
            /** The date and time at which the comment was posted */
            $posted_on   = date("d-m-Y H:i", (int) $data[$count]['created_on']);
            /** The parameters for generating the comment */
            $params      = array(
                               "name" => $data[$count]['name'],
                               "message" => $data[$count]['message'],
                               "date" => $posted_on
                           );
            /** The comment html is generated */
            $comments    []= Config::GetComponent("templateengine")->Generate("comment", $params);
        }
        /** The comments array is converted to string */
        $comments         = implode("\n", $comments);
        /** The required article comments */
        $tag_values       = array("comments" => $comments);
       
        /** The comments box and comments list are generated */
        $comments_html    = Config::GetComponent("templateengine")->Generate("comments", $tag_values);
        
        return $comments_html;
	}
}
