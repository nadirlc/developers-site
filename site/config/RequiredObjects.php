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
            $db                                       = "{your-db-name}";
            /** The dsn value */
            $dsn                                      = "mysql:host=localhost;dbname=" . $db . ";charset=utf8";
        	/** The database parameters */
        	$dbparams                                 = array(
		                                                    "dsn" => $dsn,
				                                            "user_name" => "{db-user-name}",
				                                            "password" => "{db-user-password}",
				                                            "use_cache" => false,
				                                            "debug" => 2,
				                                            "app_name" => "{your-website-name}"
	                                                    );
            /** The framework database parameters */
            $fwdbparams                               = $dbparams;
        }
        /** If the application is in production mode */
        else {
            /** The database name */
            $db                                       = "your-db-name";
            /** The dsn value */
            $dsn                                      = "mysql:host=localhost;dbname=" . $db . ";charset=utf8";
            /** The database parameters */
            $dbparams                                 = array(
		                                                    "dsn" => $dsn,
				                                            "user_name" => "{db-user-name}",
				                                            "password" => "{db-user-password}",
				                                            "use_cache" => false,
				                                            "debug" => 2,
				                                            "app_name" => "{your-website-name}"
                                                        );
            /** The framework database parameters */
            $fwdbparams                               = $dbparams;
        }																    	
	   	
	   	/** The framework database object parameters */
        $config['dbinit']['parameters']            = $dbparams;		
        /** The mysql database access class is specified with parameters for the pakjiddat_com database */
        $config['frameworkdbinit']['parameters']   = $fwdbparams;

   	   	$config['articleslib']['class_name']       = '\PakJiddat\Lib\Articles';
	   	$config['toclib']['class_name']            = '\PakJiddat\Lib\Toc';
	   	   	   	
	   	$config['application']['class_name']       = '\PakJiddat\Ui\Pages\Home';
	   	$config['cliapplication']['class_name']    = '\PakJiddat\Scripts\WebsiteTools';

        $config['websitetools']['class_name']      = '\PakJiddat\Scripts\WebsiteTools';	   	
	   	
   	   	$config['page']['class_name']              = '\PakJiddat\Ui\Pages\BasePage';
	   	$config['contactpage']['class_name']       = '\PakJiddat\Ui\Pages\Contact';
	   	$config['viewarticlepage']['class_name']   = '\PakJiddat\Ui\Pages\ArticleView';
	   	$config['listarticlepage']['class_name']   = '\PakJiddat\Ui\Pages\ArticleList';
	   	$config['homepage']['class_name']          = '\PakJiddat\Ui\Pages\Home';
	   	
	   	$config['recentarticles']['class_name']    = '\PakJiddat\Ui\Parts\RecentArticles';
	   	$config['categorylist']['class_name']      = '\PakJiddat\Ui\Parts\CategoryList';
 		$config['commentslist']['class_name']      = '\PakJiddat\Ui\Parts\CommentsList';
	   	$config['tocbox']['class_name']            = '\PakJiddat\Ui\Parts\TocBox';
	   	        
        return $config;
    }
}
