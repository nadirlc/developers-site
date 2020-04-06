<?php

declare(strict_types=1);

namespace PakJiddat\Ui\Pages;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class is used to generate the body for the article view page
 *
 * @category   Page
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class ArticleView extends BasePage
{
  	/**
     * It sets the custom JavaScript, CSS and Font files to application configuration depending on the current page
     */
    public function UpdateScripts() : void
    {
        /** The custom JavaScript files are fetched */
        $custom_javascript                              = Config::$config["general"]["custom_js_files"];                
        /** The Comments page JavaScript files are fetched */
        $comments_javascript                            = Config::$config["general"]["comments_js_files"];
        /** The Comments JavaScript files are merged with the custom JavaScript files */
        $custom_javascript                              = array_merge($custom_javascript, $comments_javascript);
        /** The custom JavaScript files are updated */
        Config::$config["general"]["custom_js_files"]   = $custom_javascript;
        /** The custom font file list is set to empty */
        Config::$config["general"]["custom_font_files"] = array();
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
        /** The current url is parsed */
		$url_parts                   = explode("/", Config::$config["general"]["request_uri"]);
        /** The id of the current article */
        $article_id                  = (isset($url_parts[2]) && $url_parts[2] == "view") ? (int) $url_parts[3] : -1;
        /** The data for the current, older and newer articles is fetched */
        $article_data                = Config::GetComponent("articleslib")->GetArticleData($article_id, "article");
        /** The article navigation bar text */
        $article_nav                 = $this->GetArticleNav($article_data);

        /** If the article was found */
        if (isset($article_data['current']['content'])) {       
            /** The article contents */
            $contents                 = ($article_data['current']['content']) ?? "";
            /** The table of contents is extracted */                                       
            $toc_data                 = Config::GetComponent("toclib")->GenerateToc($contents);
            /** The article content is updated */
            $toc                      = Config::GetComponent("tocbox")->Generate($toc_data['toc_list']);
            
            /** The tag values for generating the article body */
            $tag_values               = $article_data['current'];
            /** The article text is updated */
            $tag_values['content']    = $toc_data['updated_text'];        
       		/** The updated on value is formatted */
	        $tag_values['updated_on'] = date("d-m-Y", (int) $tag_values['updated_on']);
	        /** The article body is generated */
            $article_body             = Config::GetComponent("templateengine")->Generate(
                                           "article_body",
                                           $tag_values
                                        );

            /** The title for the page */
            Config::$config['custom']['title'] = $article_data['current']['title'];
	    }
	    /** If the article was not found */
	    else {
	        /** The article body is set to empty string */
	        $article_body                      = "";
	        /** The title for the page is set to empty string */
            Config::$config['custom']['title'] = "";
            /** The table of contents is set to empty */
            $toc                               = "";
            /** The user is redirected to the home page */
            $this->Redirect("/");
	    }
        

        /** The search box is generated */
        $sb_html                     = Config::GetComponent("templateengine")->Generate("search_box", array());
        /** The comments box is generated */
        $comments_html               = Config::GetComponent("commentslist")->Generate();
        /** The bottom boxes html is generated */
        $box_html                    = $this->GetBottomBoxes();        
        /** The required tag values for generating the view page */
        $tag_values                  = array(
                                           "article_nav" => $article_nav,
                                           "article_body" => $article_body,
                                           "toc" => $toc,
                                           "search_box" => $sb_html,
                                           "bottom_boxes" => $box_html,
                                           "comments" => $comments_html
                                       );


        /** The article body is generated */
        $article_body                = Config::GetComponent("templateengine")->Generate("view", $tag_values);
                                       
		return $article_body;
	}		
    
	/**
     * It generates the navigation links for the view article page
     *
     * @param array $article_data the data for the current article
     *    current => array the data for the current article
     *    newer => array the data for the next article
     *    older => array the data for the previous article          
     *
     * @return string $article_nav the html for the article navigation bar
     */
    private function GetArticleNav(array $article_data) : string
    {
        /** The tag values initialized */
        $tag_values                         = array();
	    /** If the newer article exists and the current article is not tagged with open source */
	    if ($article_data['newer'] != null && $article_data['current']['tags'] != "open source") {
            /** The article slug */
            $slug                           = $this->GetSlug($article_data['newer']['title']);
    	    /** The newer article url */
    	    $tag_values['next_article_url'] = "/articles/view/" . $article_data['newer']['id'] . "/" . $slug;
    	    /** The newer article button is shown */
      	    $tag_values['show_newer']       = "";
	    }
	    else {
	        /** The newer article url */
    	    $tag_values['next_article_url'] = "";
       	    /** The newer article button is hidden */
      	    $tag_values['show_newer']       = "hidden";
    	}
    	
   	    /** If the older article exists */
	    if ($article_data['older'] != null && $article_data['current']['tags'] != "open source") {	        
            /** The article slug */
            $slug                           = $this->GetSlug($article_data['older']['title']);
    	    /** The older article url */
    	    $tag_values['prev_article_url'] = "/articles/view/" . $article_data['older']['id'] . "/" . $slug;
    	    /** The older article button is shown */
      	    $tag_values['show_older']       = "";
	    }
	    else {
	        /** The older article url */
    	    $tag_values['prev_article_url'] = "";
    	    /** The older article button is hidden */
    	    $tag_values['show_older']       = "hidden";
        }
        
        /** The article list is generated */
        $article_nav     = Config::GetComponent("templateengine")->Generate("article_nav", $tag_values);
        
        return $article_nav;
    }
}
