---
title: Utilities Framework
date: "2019-03-10"
layout: post
draft: false
path: "/posts/utilities-framework"
category: "open source"
tags:
  - "open source"
description: "The Utilities Framework is a set of PHP libraries that provide functions such as error handling, logging, emailing, fetching web pages, script profiling, database abstraction, encryption, template engine and more. It requires PHP 7.2 and above. The libraries are easy to use and can be used with custom applications and PHP Frameworks."
---

![Utilities Framework](code-editor.png)

### Introduction
The Utilities Framework is a set of PHP libraries that provide functions such as error handling, logging, emailing, fetching web pages, script profiling, database abstraction, encryption, template engine and more. It requires PHP 7.2 and above. The libraries are easy to use and can be used with custom applications and PHP Frameworks.

The Utilities Framework code is fully commented and compliant with the [PSR-2](https://www.php-fig.org/psr/psr-2/) coding guidelines.

### Features
The Utilities Framework has the following features:

1. ##### Database Management
It provides functions for working with Databases. It consists of a Query Builder, Database Cache Manager and Database Log Manager. It also provides a Database MetaQuery Runner and Database Transaction Manager, based on [PDO](http://php.net/manual/en/book.pdo.php)
2. ##### Method Validation
This allows validating method parameter values against information in the method's Doc Block comments. This feature is provided by the [Comment Manager](/articles/view/254/comment-manager) package.
3. ##### Error Management
It allows application errors to be displayed using HTML template files. The template files can be easily customized. Default template files are provided for displaying formatted error messages for the browser and the command line
4. ##### File and Folder Management
It provides functions for fetching URL contents, checking if network connection works, searching for files with a folder and copying folder contents recursively
5. ##### Email Handling
It provides functions for sending email in plain text format and HTML format with file attachments. It is based on the [Pear Mail](https://pear.php.net/package/Mail/) library and hence supports sending email using SMTP server, PHP mail function and Sendmail library    
6. ##### Log Management
It provides functions for saving and updating log data to database. It uses the PDO library for saving data and hence allows log data to be saved to all the databases that are supported by PDO
7. ##### Template Engine
It provides a template engine that allows separating the HTML layout code from the data. It also allows templates to be built recursively. This means that a template can consist of one or more templates, which can contain more templates. This allows complex website layouts to be divided in to simple layout files that are automatically combined by the template engine
8. ##### Encryption
It provides function for encryption and decryption data using the new [LibSodium library](http://php.net/manual/en/book.sodium.php), which is part of PHP >=7.2
9. ##### String Utilities
It provides functions for exporting data to RSS format, converting relative URLs to absolute, checking if string is valid JSON, HTML or Base64 encoded and more
10. ##### Profiler
It allows the memory usage and execution time to be measured between function calls
11. ##### Cache Manager
It allows data to be stored in a cache. It supports memory cache and database cache
12. ##### Authentication
It provides functions for authenticating users using HTTP digest authentication

The following screenshot shows the error message displayed by the error handler component:

[Error Handler Component](error-message.png)

The following screenshot shows the MySQL query log displayed by the error handler component as part of the error:

[MySQL Query Log](mysql-query-log.png)

### Installation
* Run the command: **composer require nadirlc/utilities-framework** (installation using Composer) **OR**
* Run the command: **git clone https://github.com/nadirlc/utilities-framework.git** (Download from [GitHub Repository](https://github.com/nadirlc/utilities-framework))

### Usage
All components of the Utilities Framework can be accessed using factory functions. To use a feature, we need to first create an object of the relevant component. For example: **UtilitiesFramework::Factory("email", $parameters);**. To send an email the following code can be used:

**Click to view example**

```
/* The Email class requires Mail and Mail_Mime pear package */
include_once (&#x22;Mail.php&#x22;);
include_once (&#x22;Mail/mime.php&#x22;);

/* Change the from and to emails to your email address */
$from_email       = &#x22;nadir@dev.pakjiddat.pk&#x22;;
$to_email         = &#x22;nadir@dev.pakjiddat.pk&#x22;;
/** The parameters for the email object */
$parameters       = array(&#x22;params&#x22; =&#x3E; &#x22;&#x22;, &#x22;backend&#x22; =&#x3E; &#x22;mail&#x22;);
/* The Email class object is fetched */
$email            = UtilitiesFramework::Factory(&#x22;email&#x22;, $parameters);
/** The email is sent */
$is_sent          = $email-&#x3E;SendEmail($from_email, $to_email, &#x22;Utilitiesframework Test&#x22;,
                        &#x22;&#x3C;h3&#x3E;test html content&#x3C;/h3&#x3E;&#x22;,
                        null,
                        array(&#x22;file-path&#x22;)
                    );
/** If the email was sent, then information message is shown */
if ($is_sent) echo &#x22;Email was successfully sent&#x22;;
else echo &#x22;Email could not be sent&#x22;;
```

### Examples
The **/examples** folder contains example usage for each component.
