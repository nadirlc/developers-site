<?php

declare(strict_types=1);

namespace PakJiddat\Scripts;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides functions that generate markdown files
 *
 * @category   Scripts
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class MarkdownGenerator extends \Framework\Application\CommandLine
{
    /**
     * It converts each web page in database to markdown and saves the result to data folder
     *
     * It first generates list of all html pages
     * It then converts each html page to markdown and saves the result to data folder
     *
     * @return string $response the function response
     */
    public function GeneratorMarkdown() : string
    {
        /** The list of all articles is fetched */
        $articles         = $this->GetArticles();

        /** The path to the data folder */
        $data_folder      = Config::$config['path']['app_path'] . DIRECTORY_SEPARATOR . "data";
        /** The path to the markdown folder*/
        $markdown_folder  = $data_folder . DIRECTORY_SEPARATOR . "markdown";

        /** Each article is saved to a separate folder */
        for ($count = 0; $count < count($articles); $count++) {
            /** The article update date */
            $updated_on = $articles[$count]["date"];
            /** The article content */
            $content    = $articles[$count]["content"];
            /** The article title */
            $title      = str_replace(" ", "-", $articles[$count]["title"]);
            $title      = str_replace("/", "-", $title);

            /** The name of the article folder */
            $folder     = $markdown_folder . DIRECTORY_SEPARATOR . date("Y-d-m") . "---" . $title;
            /** If the folder exists, then loop continues */
            if (is_dir($folder))
              continue;

            /** The folder is created */
            mkdir($folder);
            /** The name of the markdown file */
            $file       = $folder . DIRECTORY_SEPARATOR . "index.md";
            /** The file is created */
            UtilitiesFramework::Factory("filemanager")->WriteLocalFile($content, $file);

            /** The status text is shown */
            echo "\n" . ($count + 1) . ". Converted file " . $title;

            /** Execution is paused for 1 sec */
            sleep(1);
        }


        /** The function response */
        $response    = "\n\n" . count($articles) . " urls were successfully written to data/markdown folder\n\n";

        return $response;
    }

    /**
     * It generates meta data from the given article data
     *
     * It generates and returns the article meta data
     * The meta data contains the title, slug, update date,
     * layout, draft, path, category, description and tags
     *
     * @param array $article the article in html format
     *
     * @return string $meta_data the article meta data
     */
    private function GetPageMetaData(array $article) : string
    {
        /** The article title */
        $title      = $article["title"];
        /** The updated date */
        $updated_on = date('Y-m-d', (int) $article["updated_on"]);
        /** The list of article tags */
        $tags       = explode(",", $article["tags"]);
        /** The article slug */
        $slug       = $article["slug"];
        /** The page category */
        $category   = $tags[0];
        /** The tags list to be used in the page meta data */
        $tag_list   = "";
        /** Each tag is checked */
        for ($count = 0; $count < count($tags); $count++) {
          /** The tag list is updated */
          $tag_list .= '  - "' . $tags[$count] . '"' . "\n";
        }
        /** The trailing \n is removed */
        $tag_list           = trim($tag_list, "\n");
        /** The new lines are removed */
        $article["content"] = str_replace("\r", "", $article["content"]);
        $article["content"] = str_replace("\n", "", $article["content"]);

        /** The first paragraph is extracted */
        preg_match("/<p>(.+)<\/p>/iU", $article["content"], $matches);
        /** The page description is set to the first paragraph */
        $description = $matches[1];
        /** The required page meta data */
        $meta_data   = <<< EOT
---
title: $title
date: "$updated_on"
layout: post
draft: false
path: "/posts/$slug"
category: "$category"
tags:
$tag_list
description: "$description"
---
EOT;

        return $meta_data;
    }

    /**
     * It converts the given article to markdown
     *
     * It converts the html tags in the given article to markdown
     * It also adds meta data to the start of the article
     *
     * @param string $article the article in html format
     *
     * @return string $markdown the converted markdown
     */
    private function ConvertToMarkDown(string $article) : string
    {
        /** The new lines are removed */
        //$article     = str_replace("\r", "", $article);
        //$article     = str_replace("\n", "", $article);

        /** The converted markdown */
        $markdown    = "";
        /** The pattern to match */
        $pattern     = array(
                        "/<h3>(.+)<\/h3>/iU",
                        "/<h4>(.+)<\/h4>/iU",
                        "/<h5>(.+)<\/h5>/iU",
                        "/<p>[\r\n]+/iU",
                        "/[\r\n]+<\/p>/iU",
                        "/<p.*>(.+)<\/p>/iU",
                        "/<a href=[\"'](.+)[\"']>(.+)<\/a>/iU",
                        "/<b>(.+)<\/b>/iU",
                        "/<img src=\"(.+)\" alt=\"(.+)\"\s?\/>/iU",
                        "/<li>(.+)<\/li>/iU",
                        "/<pre><b>[\r\n]+(.+)[\r\n]+<\/b><\/pre>/iU",
                        "/<[ou]l>/iU",
                        "/<\/[ou]l>/iU"
        );
        /** The replacement text */
        $replacement = array(
                        "### $1",
                        "#### $1",
                        "##### $1",
                        "<p>",
                        "<\p>",
                        "$1",
                        "[$2]($1)",
                        "**$1**",
                        "![$2]($1)",
                        "* $1",
                        "```\n$1\n```",
                        "",
                        ""
        );

        /** The given text is updated */
        $markdown   = preg_replace($pattern, $replacement, $article);

        return $markdown;
    }

    /**
     * It fetches all the articles
     *
     * @return array $articles the list of all articles is fetched
     */
    private function GetArticles() : array
    {
        /** The list of all articles */
        $articles        = array();
        /** The dbinit object is fetched */
        $dbinit          = Config::GetComponent("dbinit");
        /** The Database class object is fetched */
        $database        = $dbinit->GetDbManagerClassObj("Database");
		    /** The table name for website content */
        $table_name      = Config::$config['general']['mysql_table_names']['website_content'];

        /** The SQL query for fetching the website content */
        $sql             = "SELECT * FROM `" . $table_name . "` ORDER BY created_on DESC";
        /** All rows are fetched */
        $data            = $database->AllRows($sql);

        /** If data was found */
        if (is_array($data)) {
            /** Each article is checked */
            for ($count = 0; $count < count($data); $count++) {
                /** The article */
                $article         = $data[$count];
                /** The article title */
                $title           = ($article['title']);
                /** The article content */
                $content         = ($article['content']);
                /** The article update date */
                $updated_on      = ($article['updated_on']);
                /** The article tags*/
                $tags            = ($article['tags']);
                /** The article slug */
                $slug            = Config::GetComponent("viewarticlepage")->GetSlug($title);
                $article['slug'] = $slug;

                /** The article meta data is generated */
                $meta_data       = $this->GetPageMetaData($article);
                /** The article is converted to markdown */
                $markdown        = $this->ConvertToMarkDown($content);
                /** The meta data is prepended to the markdown */
                $markdown        = $meta_data . "\n\n" . $markdown;

                /** The article list is updated */
                $articles       []= array("content" => $markdown, "date" => $updated_on, "title" => $title);
            }
        }

        return $articles;
    }
}
