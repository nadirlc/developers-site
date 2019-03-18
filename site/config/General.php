<?php

declare(strict_types=1);

namespace PakJiddat\Config;

/**
 * This class general application configuration
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class General
{
    /**
     * It returns an array containing general configuration data
     *
     * @param array $parameters the application parameters
     *
     * @return array $config the custom configuration
     */
    public function GetConfig(array $parameters) : array
    {
      	/** The required application configuration */
    	$config                       = array();

        /** The path to the pear folder */
        $config['app_name']           = "Pak Jiddat";
        /** The name of the template to use */
        $config['template_library']   = "bootstrap";   
        /** Indicates if application is in development mode */
        $config['dev_mode']           = true;        
        /** Indicates that user access should be logged */
        $config['log_user_access']    = true;
        /** The default controller */
        $config['def_controller']     = "articles";
        /** The default action */
        $config['def_action']         = "view";
        
        /** If the application is in development mode */
        if ($config['dev_mode']) {
            /** The website url */        
            $config['site_url']       = "http://dev.pakjiddat.pk";
        }
        /** If the application is in production mode */
        else {
            /** The website url */        
            $config['site_url']       = "https://www.pakjiddat.pk";
        }
        
        /** The custom Contact page JavaScript file urls */                                        
        $custom_url[0]                = "{ui}/js/contact.js";
        /** The list of custom JavaScript files used by Contact form */
        $config['contact_js_files']   = array(                                                                  
                                            array("url" => $custom_url[0], "type" => "module")                     
                                        );
                                        
        /** The custom Comment page JavaScript file urls */                                        
        $custom_url[0]                = "{ui}/js/comments.js";
        /** The list of custom JavaScript files used by Comments form */
        $config['comments_js_files']  = array(                                                                  
                                            array("url" => $custom_url[0], "type" => "module")                     
                                        );                                        
        
        /** The custom JavaScript file urls */
        $custom_url[0]                = "{framework}/jquery/js/jquery-3.3.1.min.js";
        $custom_url[1]                = "{framework}/bootstrap/js/bootstrap.bundle.min.js";
        $custom_url[2]                = "{ui}/js/scroll-top.js";
        
		/** The list of custom JavaScript files */
        $config['custom_js_files'][0] = array("url" => $custom_url[0], "type" => "text/javascript");
        $config['custom_js_files'][1] = array("url" => $custom_url[1], "type" => "text/javascript");
        $config['custom_js_files'][2] = array("url" => $custom_url[2], "type" => "text/javascript");       
                                        
		/** The list of custom css files */
        $config['custom_css_files']   = array(
                                            array("url" => "{ui}/css/page.css"),
                                            array("url" => "{framework}/bootstrap/css/bootstrap.min.css"),
                                            array("url" => "{framework}/fontawesome/css/all.css")
                                        );

        /** The mysql table names */
        $config['mysql_table_names']    = array(
                                              "website_content" => "home_content",
                                              "contact" => "home_contact",
                                              "comments" => "home_comments"
                                          );
																																
        return $config;
    }
}
