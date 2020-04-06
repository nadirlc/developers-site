---
title: Configuring Apache to process html files as php
date: "2017-02-06"
layout: post
draft: false
path: "/posts/configuring-apache-to-process-html-files-as-php"
category: "server management"
tags:
  - "server management"
description: "To allow Apache web server, to process files ending with .html as php files, we need to do two things. First we need to set the content-type http header for .html files to text/html. Secondly, we need to tell the Apache web server to process html files as php files."
---

To allow Apache web server, to process files ending with .html as php files, we need to do two things. First we need to set the content-type http header for .html files to text/html. Secondly, we need to tell the Apache web server to process html files as php files.

Usually this can be achieved by adding following two lines in .htaccess:

<pre>
<b>AddHandler application/x-httpd-php .html
AddType text/html .html</b>
</pre>

The above code should work if Php is configured as an Apache module, but may not work for other configurations like FastCGI.

<p>To allow Apache to serve files ending with .html as php files, we need following code in vhost.conf file for the required virtual host:

<pre>
<b>&lt;IfModule mod_fcgid.c&gt;
    &lt;Files tilde (\.html)&gt;
        SetHandler fcgid-script
        FCGIWrapper /var/www/cgi-bin/cgi_wrapper/cgi_wrapper .html
        Options +ExecCGI
        allow from all
    &lt;/Files&gt;
&lt;/IfModule&gt;</b>
</pre>

The above code is for FastCGI configuration. It will differ for other configurations like mod_php. After editing the file we need to run following command for re-configuring the web server: **/usr/local/psa/admin/sbin/httpdmng --reconfigure-all**.

The article: [How to process files with .html extension through PHP (Linux)](https://kb.odin.com/en/115773) describes how to process .html files through PHP on Linux