<?php

declare(strict_types=1);

namespace PakJiddat;

ini_set("include_path", '/home/pakjidda/php:' . ini_get("include_path") );

/**
 * Application configuration class
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class Config extends \Framework\Config\Config
{
    /**
     * Used to determine if the application request should be handled by the current module
     *
     * It returns true if the host name contains www.pakjiddat.pk or dev.pakjiddat.pk
     * Otherwise it returns false
     *
     * @param array $parameters the application parameters
     *
     * @return boolean $is_valid indicates if the application request is valid
     */
    public static function IsValid(array $parameters) : bool
    {
    	/** The request is marked as not valid by default */
    	$is_valid     = false;
    	
    	/** If the application is being run from command line */
        if (php_sapi_name() == "cli") {
            /** If the application name is "pakjiddat" */
            if ($parameters['application'] == "pakjiddat") {
                $is_valid = true;
            }
        }
        /** If the host name is www.pakjiddat.pk or dev.pakjiddat.pk */
        else if ($_SERVER['HTTP_HOST'] == "www.pakjiddat.pk" || $_SERVER['HTTP_HOST'] == "dev.pakjiddat.pk") {
        	$is_valid = true;
        }

        return $is_valid;
    }
}
