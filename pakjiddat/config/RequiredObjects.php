<?php

declare(strict_types=1);

namespace PakJiddat\Config;

/**
 * This class provides required objects application configuration
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class RequiredObjects
{
    /**
     * It returns an array containing requiredobjects configuration data
     *
     * @param array $parameters the application parameters
     *
     * @return array $config the custom configuration
     */
    public function GetConfig(array $parameters) : array
    {
      	/** The required application configuration */
    	$config                                    = array();

        /** If the application is in development mode */
        if ($parameters['dev_mode']) {
            /** The database name */
            $db                                       = "pakjiddat_home";
            /** The dsn value */
            $dsn                                      = "mysql:host=localhost;dbname=" . $db . ";charset=utf8";
        	  /** The database parameters */
          	$dbparams                                 = array(
  		                                                    "dsn" => $dsn,
      				                                            "user_name" => "nadir",
      				                                            "password" => "kcW5eFSCbPXb#7LHvUGG8T8",
      				                                            "use_cache" => false,
      				                                            "debug" => 2,
      				                                            "app_name" => "Pak Jiddat"
  	                                                    );
            /** The framework database parameters */
            $fwdbparams                               = $dbparams;
        }
        /** If the application is in production mode */
        else {
            /** The dsn value */
            $dsn                                      = "mysql:host=localhost;dbname=pakjidda_home;charset=utf8";
            /** The database parameters */
            $dbparams                                 = array(
                                                            "dsn" => $dsn,
        				                                            "user_name" => "pakjidda_home",
        				                                            "password" => "jZtjH1@NjbgBXOqYmQ123fVH",
        				                                            "debug" => 2,
        				                                            "use_cache" => false,
        				                                            "app_name" => "Pak Jiddat"
                                                        );
            /** The framework database parameters */
            $fwdbparams                               = $dbparams;
        }

	   	  /** The framework database object parameters */
        $config['dbinit']['parameters']               = $dbparams;
        /** The mysql database access class is specified with parameters for the pakjiddat_com database */
        $config['frameworkdbinit']['parameters']      = $fwdbparams;

   	   	$config['articleslib']['class_name']          = '\PakJiddat\Lib\Articles';
	   	  $config['toclib']['class_name']               = '\PakJiddat\Lib\Toc';

	   	  $config['application']['class_name']          = '\PakJiddat\Ui\Pages\Home';
	   	  $config['cliapplication']['class_name']       = '\PakJiddat\Scripts\SiteMapGenerator';

        $config['sitemapgenerator']['class_name']     = '\PakJiddat\Scripts\SiteMapGenerator';
        $config['markdowngenerator']['class_name']    = '\PakJiddat\Scripts\MarkdownGenerator';
        $config['htmlgenerator']['class_name']        = '\PakJiddat\Scripts\HtmlGenerator';        

   	   	$config['page']['class_name']                 = '\PakJiddat\Ui\Pages\BasePage';
	   	  $config['contactpage']['class_name']          = '\PakJiddat\Ui\Pages\Contact';
	   	  $config['viewarticlepage']['class_name']      = '\PakJiddat\Ui\Pages\ArticleView';
	   	  $config['listarticlepage']['class_name']      = '\PakJiddat\Ui\Pages\ArticleList';
	   	  $config['homepage']['class_name']             = '\PakJiddat\Ui\Pages\Home';

	   	  $config['recentarticles']['class_name']       = '\PakJiddat\Ui\Parts\RecentArticles';
	   	  $config['categorylist']['class_name']         = '\PakJiddat\Ui\Parts\CategoryList';
 		    $config['commentslist']['class_name']         = '\PakJiddat\Ui\Parts\CommentsList';
	   	  $config['tocbox']['class_name']               = '\PakJiddat\Ui\Parts\TocBox';

        return $config;
    }
}
