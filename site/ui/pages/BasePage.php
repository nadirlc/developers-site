<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Pages;

use \Framework\Application\Page as Page;
use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides common functions that are used to generate the application pages
 *
 * @category   Page
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class BasePage extends Page
{
    /**
     * It generates the html for the page
     *
     * @return string $page_html the html for the page
     */
    public function Generate() : string
    {        
		/** The current development mode */
		$dev_mode    = Config::$config["general"]["dev_mode"];
        /** The current url request */
        $request_url = Config::$config["general"]["request_uri"];		
		/** If the website is in development mode */
		if ($dev_mode) {
		    /** The islam companion website link */
		    $params['tag_values']['ic_link'] = "http://dev.islamcompanion.pakjiddat.pk";
		}
		else {
    		/** The islam companion website link */
		    $params['tag_values']['ic_link'] = "https://islamcompanion.pakjiddat.pk";
		}

		/** The header contents are fetched */
		$header_html = $this->GetHeader($params);
		/** The footer contents are fetched */
		$footer_html = $this->GetFooter();
		/** The html for the page body */
        $body_html   = $this->GetBody($params);			
		/** The contents for the scripts is fetched */
		$script_tags = $this->GetScripts();
        /** The template parameters for the table template */
        $tag_values           = array(
                                    "title" => $script_tags['title'],
								    "css_tags" => $script_tags['css'],
									"js_tags" => $script_tags['js'],
									"font_tags" => $script_tags['fonts'],
									"ga_code" => $script_tags['ga_code'],
									"body" => $body_html,
									"header" => $header_html,
									"footer" => $footer_html
							    );
							    
   		/** The html for the page */
        $page_html   = Config::GetComponent("templateengine")->Generate("page", $tag_values);							    

		return $page_html;
    }
    
    /**
     * It provides the html for the page footer
     *
     * @param array $params optional the parameters for generating the footer
     *
     * @return string $footer_html the html for the page footer
     */
    protected function GetFooter(?array $params = null) : string
    { 
        /** The tag values for the footer */
		$tag_values   = array("year" => date("Y"));
		/** If the parameters and tag values are given */
		if (is_array($params) && isset($params['tag_values'])) {
		    /** The tag values are overriden by the parameters */
		    $tag_values = array_merge($tag_values, $params['tag_values']);
		}
		                
        $footer_html  = Config::GetComponent("templateengine")->Generate("footer", $tag_values);
		
		return $footer_html;
    }
    
    /**
     * It provides the html for the page header
     *
     * @param array $params the parameters for generating the header
     *
     * @return string $header_html the html for the header
     */
    protected function GetHeader(?array $params = null) : string
    {
        /** The tag values for the header */
		$tag_values  = array(
		                   "article_active" => "",
              		       "research_active" => "",
		                   "ic_active" => "",
		                   "contact_active" => "",
		                   "ic_link" => "https://islamcompanion.pakjiddat.pk"
		               );
		                
        /** The current url */
		$current_url = Config::$config["general"]["request_uri"];
		/** If the request url starts with '/articles' or is '/' */
		if (strpos($current_url, "/articles") === 0 || $current_url == "/") {
		    /** The menu is marked as active */
		    $tag_values['article_active']       = "active";
		}
		/** If the request url starts with '/research' */
		else if (strpos($current_url, "/research") === 0) {
		    /** The menu is marked as active */
		    $tag_values['research_active']       = "active";
		}
		/** If the request url starts with '/contact' */
		else if (strpos($current_url, "/contact") === 0) {
		    /** The menu is marked as active */
		    $tag_values['contact_active']       = "active";
		}
		
		/** If the parameters and tag values are given */
		if (is_array($params) && isset($params['tag_values'])) {
		    /** The tag values are overriden by the parameters */
		    $tag_values = array_merge($tag_values, $params['tag_values']);
		}
		
		$header_html = Config::GetComponent("templateengine")->Generate("header", $tag_values);
		
		return $header_html;
	}
   
    /**
     * It provides the html for the page body
     *
     * @param array $params the parameters for generating the body
     *
     * @return string $body_html the html for the body
     */
    protected function GetBody(?array $params = null) : string
    {
    	$body_html = "";
    	
    	return $body_html;
    }    
    
    /**
     * It provides the code for the page scripts
     *
     * @param array $params the parameters for generating the scripts
     *
     * @return array $tag_values the script code
     *    title => string the page title
     *    css => string the css tags
     *    js => string the js tags
     *    fonts => string the font tags
     *    ga_code => string the google analytics code
     */
    protected function GetScripts(?array $params = null) : array
    {
        /** The JavaScript, CSS and Font tags are set for the current page */
        $this->UpdateScripts();
        /** The JavaScript, CSS and Font tags are generated */
        $header_tags          = parent::GetHeaderTags();

        /** If the website is in production mode */
        if (!Config::$config["general"]["dev_mode"]) {
            /** The path to the Google Analytics tracking code file */
            $file_path        = Config::$config["path"]["app_template_path"] . DIRECTORY_SEPARATOR . "base";
            $file_path        . DIRECTORY_SEPARATOR . "ga_tracking.html";
            /** The google analytics tracking code is read */
            $ga_tracking_code = UtilitiesFramework::Factory("filemanager", array())->ReadLocalFile($file_path);
        }
        /** If the website is in development mode, then tracking code is set to empty */
        else $ga_tracking_code = "";
        
        /** If the current url is "/" */
		if (Config::$config["general"]["request_uri"] == "/") {
	    	/** The template tag values */
		    $title = "Pak Jiddat - Learn and Progress";
		}
		/** If the current url is "/contact" */
		else if (Config::$config["general"]["request_uri"] == "/contact/form") {
	    	/** The template tag values */
		    $title = "Pak Jiddat - Contact Page";
		}
        else {
            /** The tag values */
            $title = Config::$config['custom']['title'];
        }
        
        /** The template parameters for the table template */
        $tag_values           = array(
                                    "title" => $title,
								    "css" => $header_tags['css'],
									"js" => $header_tags['javascript'],
									"fonts" => $header_tags['fonts'],
									"ga_code" => $ga_tracking_code
							    );

        return $tag_values;  
    }
    
    /**
     * Used to handle requests for which no matching url routing rules were found
     * It returns http status code of 404
     * It displays a custom 404 error page
     * This function may be overriden by child classes
     */
    public function HandlePageNotFound() : void
    {
        /** The current url */
		$current_url  = Config::$config["general"]["request_uri"];
		/** The current url is checked to see if it is a category page */
		$is_match     = preg_match("/\/(articles|research)\/(.+)\/1/", $current_url, $matches);
		/** If there is a match */
		if ($is_match && isset($matches[2]) && $matches[2] != "") {
		    /** The new category url */
		    $new_url  = "/articles/tag/" . $matches[2];
		    /** The user is redirected */
		    header("Location: " . $new_url);
		    /** Script ends */
		    die();
		}
		/** The current url is checked to see if it is an article page */
		$is_match     = preg_match("/\/(articles|research)\/(.+)\/(.+)/", $current_url, $matches);
		/** If there is a match */
		if ($is_match && isset($matches[3]) && $matches[3] != "") {
		    /** The article id is fetched */
		    $article_id = Config::GetComponent("articleslib")->GetArticleIdBySlug($matches[3]);
		    /** If article id was found */
		    if ($article_id != null) {
		        /** The new category url */
		        $new_url  = "/articles/view/" . $article_id . "/" . $matches[3];
		        /** The user is redirected */
		        header("Location: " . $new_url);
		        /** Script ends */
		        die();
		    }
		}
		
		/** If no alternate content could be found, then parent function is called, which displays custom 404 page */
		parent::HandlePageNotFound();
    }
    
    /**
     * It provides the html for the bottom boxes that are shown on all pages
     *
     * @return string $box_html the html for the bottom boxes
     */
    protected function GetBottomBoxes() : string
    {
        /** The category list box is generated */
        $cbox_html  = Config::GetComponent("categorylist")->Generate();
        /** The recent articles box is generated */
    	$rbox_html  = Config::GetComponent("recentarticles")->Generate();
    	/** The tag values for generating the bottom box */
        $tag_values = array("category_list" => $cbox_html, "recent_articles" => $rbox_html);
        /** The bottom box html is generated */
    	$box_html   = Config::GetComponent("templateengine")->Generate("bottom_boxes", $tag_values);
    	
    	return $box_html;
    }
    
    /**
     * It generates the article slug from the given title
     *
     * @param string $title the article title
     *
     * @return string $slug the article slug
     */
    public function GetSlug(string $title) : string
    {
        /** The article slug */
        $slug  = strtolower($title);
        $slug  = preg_replace("/[^a-zA-Z0-9]/i", "-", $slug);       
        /** The article slug is url encoded */
        $slug  = urlencode($slug);
        
        return $slug;	      
    }
}
