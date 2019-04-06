<?php

declare(strict_types=1);

namespace PakJiddat\Config;

/**
 * This class provides test application configuration
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class Test
{
    /**
     * Used to return the configuration
     *
     * It returns an array containing testing configuration data
     *
     * @param array $parameters the application parameters
     *
     * @return array $config the custom configuration
     */
    public function GetConfig(array $parameters) : array
    {
      	/** The required application configuration */
    	$config                               = array();
    	/** The test mode is set */
    	$config['test_mode']                  = false;
    	/** The test type is set */
       	$config['test_type']                  = "ui";
       	/** Indicates if the ui test data should be saved */
       	$config['save_ui_test_data']          = false;
       	/** The list of broken links to ignore */
       	$config['ignore_links']               = array(
       	                                            "https://www.scl.com/insights/blog/google-chrome-prefetchprerender-inflating-web-analytics-stats/",
       	                                            "https://www.mantisbt.org/",
       	                                            "http://php.net/manual/en/function.array-rand.php",
       	                                            "https://html5.validator.nu/",
       	                                            "https://validator.w3.org/nu/",
       	                                            "https://www.lisenet.com/2014/automate-clamav-to-perform-daily-system-scan-and-send-email-notifications-on-linux/",
       	                                            "https://www.drupal.org/",
       	                                            "https://tools.ietf.org/html/rfc7489",
       	                                            "https://www.emailsecuritygrader.com",
       	                                            "https://forum.pfsense.org/index.php?topic=77493.msg422407#msg422407",
       	                                            "https://validator.nu/",
       	                                            "https://cran.r-project.org/bin/linux/debian/#secure-apt",
       	                                            "https://docs.nextcloud.com/server/12/admin_manual/installation/index.html",
       	                                            "http://sourcode.net/sh-1-node-permission-denied/",
       	                                            "https://editoria.pub/about-us/",
       	                                            "https://kb.odin.com/en/115773",
       	                                            "http://www.nmonitoring.com"
       	                                        );
    	
        return $config;
    }
}
