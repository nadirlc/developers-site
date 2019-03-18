<?php

declare(strict_types=1);

namespace PakJiddat\Config;

/**
 * This class provides custom application configuration
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class Custom
{
    /**
     * It returns an array containing general configuration data
     *
     * @param array $parameters the application parameters
     *
     * @return array $configuration the custom configuration
     */
    public function GetConfig(array $parameters) : array
    {
      	/** The required application configuration */
    	$config                               = array();    	
                	           
        /** The configuration for contact form */
    	$config['contact_config']             = array(
                	                                "email_subject" => "Contact request sent from Pak Jiddat website",
                	                                "email_from" => "nadir@pakjiddat.pk",
                	                                "email_to" => "nadir@pakjiddat.pk"
                	                            );
                	                            
        /** The configuration for comment form */
    	$config['comment_config']             = array(
                	                                "email_subject" => "Comment posted on Pak Jiddat website",
                	                                "email_from" => "nadir@pakjiddat.pk",
                	                                "email_to" => "nadir@pakjiddat.pk"
                	                            );                	                            
                	                            
        /** If the application is in development mode */
        if ($parameters['dev_mode']) {
        	/** The email address from which to send the email */
        	$config['contact_config']['email_from']      = "nadir@dev.pakjiddat.pk";
            /** The email address to which email should be sent, notifying of new message */
        	$config['contact_config']['email_to']        = "nadir@dev.pakjiddat.pk";
            /** The email address from which to send the email */
        	$config['comment_config']['email_from']      = "nadir@dev.pakjiddat.pk";
            /** The email address to which email should be sent, notifying of new comment */
        	$config['comment_config']['email_to']        = "nadir@dev.pakjiddat.pk";        	
    	}
    	                	                                            	                            
        return $config;
    }
}
