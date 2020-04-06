---
title: Generating Table of Contents
date: "2019-03-16"
layout: post
draft: false
path: "/posts/generating-table-of-contents"
category: "algorithms"
tags:
  - "algorithms"
description: "Table of contents gives an overview of the article. It describes what the article contains. A quick glance at the table of contents gives a useful overview of the article. Most articles have a table of contents which contains links to the important parts of an article."
---

### Introduction
Table of contents gives an overview of the article. It describes what the article contains. A quick glance at the table of contents gives a useful overview of the article. Most articles have a table of contents which contains links to the important parts of an article.

### Basic Usage
To generate a table of contents using PHP, the following code can be used:

```
$toc = new TOC();
$toc_data = $toc->GenerateToc($article_text);
```

### How the code works
In the above code, the **$article_text** is the article for which the table of contents needs to be generated. The **$toc_data** variable is an associative array containing two entries.

The **toc_list** entry is the table of contents formatted as an unordered HTML list. The **updated_text** is the updated article text. The headings in this updated text are marked with ids, so when the user clicks on a link in the table of contents, it scrolls to the article heading

The code for generating the table of contents does three things. First it extracts the headings from the article text using regular expressions. The headings are extracted recursively. Next the script generates an unordered HTML list from the extracted headings. After that the script updates the article text, adding ids to the HTML headings

### Features
The script extracts all heading tags from h1-h6. It also adds ids to the article text. The table of contents is an unordered HTML list. Each item in the list is a link to a heading.

In the article text a heading level may be skipped. For example h5 headings can follow a h3 heading

### Limitations
The headings in the article text must be nested. So after h1, there must be a lower level heading such as **h2, h3, h4, h5, or h6**.

### Php Code
The code for the TOC class is given below:

**Click to view code**

```
&#x3C;?php

declare(strict_types=1);

namespace PakJiddat\Lib;

use \Framework\Config\Config as Config;

/**
 * This class provides functions that generate the table of contents
 *
 * @category   Library
 * @author     Nadir Latif &#x3C;nadir@pakjiddat.pk&#x3E;
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class Toc
{
    /**
     * It extracts the headings from the article text and returns the headings as a html list
     * The table of contents list contains links to article headings
     * The article text is also updated so all headings have an id
     *
     * @param string $article_text the article text
     *
     * @return array $toc_data the table of contents list and updated article text
     *    toc_list =&#x3E; string the table of contents items in html format
     *    updated_text =&#x3E; string the updated article text
     */
    public function GenerateToc(string $article_text) : array
    {
        /** The headings are extracted from the article text */
        $headings     = $this-&#x3E;ExtractHeadings($article_text, 1);
        /** The headings are formatted as html list */
        $toc_list     = $this-&#x3E;GenerateTocList($headings);
        /** The article text is updated so the headings contain ids */
        $article_text = $this-&#x3E;AddIdsToHeadings($article_text, $toc_list);

        /** The required toc data */
        $toc_data     = array(&#x22;toc_list&#x22; =&#x3E; $toc_list, &#x22;updated_text&#x22; =&#x3E; $article_text);

        return $toc_data;
    }

    /**
     * It adds ids to the article headings
     *
     * @param string $article_text the article text
     * @param string $toc_list the toc in html list format
     *
     * @return string $updated_article_text the updated article text with ids
     */
    private function AddIdsToHeadings(string $article_text, string $toc_list): string
    {
        /** The updated article text */
        $updated_article_text = $article_text;
        /** The heading ids and text are extracted from the toc */
        preg_match_all(&#x22;/&#x3C;a href=&#x27;#(.+)&#x27;&#x3E;(.+)&#x3C;\/a&#x3E;/iU&#x22;, $toc_list, $matches);
        /** Each link text is checked in the article text */
        for ($count = 0; $count &#x3C; count($matches[2]); $count++) {
            /** The link text */
            $text                 = $matches[2][$count];
            /** The link id */
            $id                   = $matches[1][$count];
            /** The regular expression used to search for headings. The special regex characters are removed from the text */
            $regex                = &#x22;/&#x3C;h(\d)( class=.+)?&#x3E;&#x22; . preg_quote($text, &#x22;/&#x22;) . &#x22;&#x3C;\/h\d&#x3E;/iU&#x22;;
            /** The replacement expression */
            $replacement          = &#x22;&#x3C;h$1 $2 id=&#x27;&#x22; . $id . &#x22;&#x27;&#x3E;&#x22; . $text . &#x22;&#x3C;/h$1&#x3E;&#x22;;
            /** The text is replaced within the article text */
            $updated_article_text = preg_replace($regex, $replacement, $updated_article_text);
        }

        return $updated_article_text;
    }

    /**
     * It formats the given headings into html format
     *
     * @param array $headings the article headings
     *
     * @return string $toc_list the headings in html format
     */
    private function GenerateTocList($headings) : string
    {
        /** The required toc list */
        $toc_list = &#x22;&#x3C;ul&#x3E;&#x22;;
        /** Each heading is formatted as html list */
        foreach ($headings as $htext =&#x3E; $sub_headings) {
            /** The tags are stripped from the heading */
            $htext     = strip_tags($htext);
            /** The header id is generated */
            $htext_id  = strtolower($htext);
            $htext_id  = htmlspecialchars($htext_id, ENT_QUOTES);
            $htext_id  = str_replace(&#x22; &#x22;, &#x22;-&#x22;, $htext_id);
            $htext_id  = str_replace(&#x22;|&#x22;, &#x22;-&#x22;, $htext_id);            
            /** The header text is converted to link */
            $htext     = &#x22;&#x3C;a href=&#x27;#&#x22; . $htext_id . &#x22;&#x27;&#x3E;&#x22; . $htext . &#x22;&#x3C;/a&#x3E;&#x22;;
            /** The heading text is enclosed in &#x3C;li&#x3E; tags */
            $toc_list .= &#x22;&#x3C;li&#x3E;&#x22; . $htext;
            /** If the sub headings are present */
            if (count(array_keys($sub_headings)) &#x3E; 0) {
                /** The toc is generated from sub headings */
                $toc_list .= $this-&#x3E;GenerateTocList($sub_headings);
            }
            /** The &#x3C;li&#x3E; tag is closed */
            $toc_list .= &#x22;&#x3C;/li&#x3E;&#x22;;
        }
        /** The toc tag is closed */
        $toc_list .= &#x22;&#x3C;/ul&#x3E;&#x22;;
        //die( $toc_list);exit;
        return $toc_list;
    }

&#x9;/**
     * It extracts the headings from the article text
     * The headings are returned as nested associative array
     * The articles should have headings organized in a nested order
     *
     * @param string $article_text the article text
     * @param int $level [1-6] the heading level
     *
     * @return array $heading_list the list of headings in the article text
     */
    private function ExtractHeadings(string $article_text, int $level = 1) : array
    {
        /** The new lines are removed from the text */
        $text          = str_replace(&#x22;\n&#x22;, &#x22;&#x22;, $article_text);
        $text          = str_replace(&#x22;\r&#x22;, &#x22;&#x22;, $text);

        /** The header tag */
        $tag           = &#x22;h&#x22; . $level;
        /** The required heading list */
        $heading_list  = array();
        /** The tag is extracted from the article text */
        preg_match_all(&#x22;/&#x3C;&#x22; . $tag . &#x22;.*&#x3E;(.+)&#x3C;\/&#x22; . $tag . &#x22;&#x3E;/iU&#x22;, $text, $matches1);
        /** If no matches were found */
        if (count($matches1[0]) == 0 &#x26;&#x26; $level &#x3C; 6) {
            /** The headings for the next level are extracted */
            $heading_list = $this-&#x3E;ExtractHeadings($text, ($level+1));
        }

        /** The text after each heading is extracted */
        for ($count = 0; $count &#x3C; count($matches1[0]); $count++) {
            /** The extracted heading */
            $htext                = $matches1[0][$count];
            /** The next heading */
            $next_heading         = (isset($matches1[0][$count+1])) ? $matches1[0][$count+1] : &#x22;&#x22;;
            /** The regular expression special characters are quoted */
            $text1                = preg_quote($htext, &#x22;/&#x22;);
            /** The regular expression special characters are quoted */
            $text2                = preg_quote($next_heading, &#x22;/&#x22;);
            /** The regular expression for extracting the text between two headings */
            $regex                = &#x22;/&#x22; . $text1 . &#x22;(.+)&#x22; . $text2 . &#x22;/iU&#x22;;
            /** The text between two headings is extracted */
            preg_match_all($regex, $text, $matches2);
            /** The next article text to check */
            $next_text            = $matches2[1][0];
            /** The list of sub headings */
            $sub_heading_list     = array();
            /** The sub heading level to check */
            $next_level           = $level;
            /** The sub headings are extracted */
            do {
                /** The next heading level is checked */
                $next_level++;
                /** The next level headings are extracted */
                $sub_heading_list = $this-&#x3E;ExtractHeadings($next_text, $next_level);
            }
            while(count($sub_heading_list) == 0 &#x26;&#x26; $next_level &#x3C; 6);
            /** The sub heading list is added to the main heading */
            $heading_list[$htext] = $sub_heading_list;
        }

        return $heading_list;
    }
}
```

#### Examples
The table of contents for the articles on this website, were generated using the **TOC class**
