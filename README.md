<p><img src="https://pakjiddat.netlify.app/static/c079a165e6b60b8d38bff043b331a962/8c557/pakjiddat-website.png" alt="Pak Jiddat Website"/></p>

<h3>Introduction</h3>
<p>The <b>"Developers Site"</b> project is a simple website that displays articles. It is suitable for developers who need a platform for publishing their work. A working example of the website is the: <a href='https://www.pakjiddat.pk/'>Pak Jiddat website</a></p>

<h3>Development of the website</h3>
<p>The website was developed by following the <a href='http://theleanstartup.com/principles'>Lean Startup principles</a>. The book <a href='https://www.packtpub.com/business/understanding-software'>Understanding Software</a> was an invaluable guide in the development of the website.</p>

<h3>Features</h3>
<div>
<ul>
<li>The website allows publishing articles</li>
<li>Table of contents are auto generated for articles with headings</li>
<li>Comments can be posted to articles</li>
<li>Contact form can be used to send a message to the website owner</li>
<li>The website has a search box for searching articles</li>
<li>List of recent articles and categories is auto generated</li>
<li>The source code is modular and easy to extend with new features</li>
<li>The website frontend is based on <a href='https://getbootstrap.com/'>Twitter Bootstrap</a> and <a href='https://jquery.com/'>JQuery</a></li>
<li>The website frontend is valid HTML 5 and has been tested with the <a href='https://validator.nu/'>validator.nu</a> tool</li>
<li>The article structure is based on <a href='https://www.w3schools.com/html/html5_semantic_elements.asp'>Semantic Elements</a> and is SEO friendly</li>
<li>The website is mobile friendly</li>
<li>The website has good browser response time</li>
<li>Google Analytics tracking code can be easily added for keeping track of website visitors</li>
<li>The website provides scripts for generating Site Maps and for testing the website pages for broken links and HTML 5 validation errors</li>
<li>HTML pages can be exported as markdown files</li>
</ul>
</div>

<h3>Limitations</h3>
<div>
<ul>
<li>The website does not have a backend. Article content has to be added directly to database using a database management tool such as <a href='https://www.phpmyadmin.net/'>PhpMyAdmin</a></li>
</ul>
</div>

<h3>Requirements</h3>
<p>The "Developers Site" is based on Php and MySQL. It uses the <a href='https://getbootstrap.com/'>Twitter Bootstrap</a> and <a href='https://jquery.com/'>JQuery</a> Frontend libraries. The website uses the <a href='https://pear.php.net/package/Mail/'>Mail</a> and <a href='https://pear.php.net/package/Mail_Mime/'>Mail Mime</a> pear packages for sending email. These packages should be installed on your server.</p>

<p>Installing the website on your own server requires Php 7.2 and above. The website source code is based on the <a href='https://www.pakjiddat.pk/articles/view/258/pak-php-framework'>Pak Php Framework</a>. The source code is fully commented and compliant with the <a href='https://www.php-fig.org/psr/psr-2/'>PSR-2 coding guidelines</a>. The source code for the website is modular and easy to extend with new features.</p>

<h3>Site Map Generation</h3>
<p>A Site Map for the website pages can be auto generated by running the command: <b>php index.php  --application="pakjiddat" --action="Generate Site Map";</b>. This creates a file sitemap.txt in the data folder. The script also saves all urls to the <b>pakphp_test_data</b>, which allows each url to be checked for broken links and HTML 5 compatibility.</p>

<h3>Markdown file Generation</h3>
<p>The website content stored in database can be exported as markdown files by running the command: <b>php index.php --application="pakjiddat" --action="Generate Markdown";</b>. This creates Markdown files in the data/markdown folder.</p>

<h3>Html file Generation</h3>
<p>The website content stored in database can be exported as HTML files by running the command: <b>php index.php --application="pakjiddat" --action="Generate Html";</b>. This creates HTML files in the data/html folder.</p>

<h3>Testing</h3>
<p>All pages on the "Developers Site" website can checked for broken links and validated using the <a href='https://validator.nu/'>validator.nu</a> tool. To test the website pages, first enter the list of urls to test in the database table: <b>pakphp_test_data</b>. The table may be auto populated by setting the variable: <b>save_ui_test_data</b> to <b>true</b> in <b>pakjiddat/config/Test.php</b> file. Alternately the Site Map script may be run, which saves all Site Map urls to the database table. Next run the command: <b>php index.php  --application="pakjiddat" --action="Unit Test";</b>. This will start the testing of each page listed in the database table.</p>

<h3>Installation</h3>
<p>The following steps can be used to install the "Developers Site" project on your own server:</p>
<div>
  <ul>
    <li>Download the <a href='https://github.com/nadirlc/developers-site/archive/master.zip'>source code</a> from GitHub</li>
    <li>Move the source code to the document root of a virtual host</li>
    <li>Create a database and import the contents of the file: <b>pakjiddat/data/developers-site.sql</b> to the database. Note down the credentials used for connecting to the database</li>
    <li>Enter the database credentials in the file <b>pakjiddat/config/RequiredObjects.php</b></li>
    <li>In the file: <b>pakjiddat/Config.php</b>, on <b>line 41</b> enter the domain names that will be used to access the website</li>
    <li>Customize the following variables in the file: <b>pakjiddat/config/General.php</b>. <b>$config['app_name'], $config['dev_mode'] and $config['site_url']</b></li>
    <li>Customize the variables in the file: <b>pakjiddat/config/Custom.php</b>. The comments explain what each variable is used for</li>
    <li>Set the <b>$config['pear_folder_path']</b> variable in the file: <b>pakjiddat/config/Path.php</b>. The variable should be set to the path of the <a href='https://pear.php.net/'>pear</a> installation.</li>
    <li>Visit the website in a browser</li>
    <li>Enter the website articles in the database table: <b>home_content</b>. The article should start showing on the website</li>
    <li>The layout and text of the website can be edited in html files inside the folder: <b>pakjiddat/ui/html</b>. For example to edit the website header, the file: <b>pakjiddat/ui/html/base/header.html</b> needs to be edited</li>
  </ul>
</div>
