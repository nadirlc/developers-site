<?php

declare(strict_types=1);

namespace PakJiddat\Lib;

use \Framework\Config\Config as Config;

/**
 * This class provides functions that generate the table of contents
 *
 * @category   Library
 * @author     Nadir Latif <nadir@pakjiddat.pk>
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
     *    toc_list => string the table of contents items in html format
     *    updated_text => string the updated article text
     */
    public function GenerateToc(string $article_text) : array
    {
        /** The headings are extracted from the article text */
        $headings     = $this->ExtractHeadings($article_text, 1);
        /** The headings are formatted as html list */
        $toc_list     = $this->GenerateTocList($headings);
        /** The article text is updated so the headings contain ids */
        $article_text = $this->AddIdsToHeadings($article_text, $toc_list);
        
        /** The required toc data */
        $toc_data     = array("toc_list" => $toc_list, "updated_text" => $article_text);
        
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
        preg_match_all("/<a href='#(.+)'>(.+)<\/a>/iU", $toc_list, $matches);
        /** Each link text is checked in the article text */
        for ($count = 0; $count < count($matches[2]); $count++) {
            /** The link text */
            $text                 = $matches[2][$count];
            /** The link id */
            $id                   = $matches[1][$count];
            /** The regular expression used to search for headings */
            $regex                = "/<h(\d.*)>" . $text . "<\/h\d>/iU";
            /** The replacement expression */
            $replacement          = "<h$1 id='" . $id . "'>" . $text . "</h$1>";
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
        $toc_list = "<ul>";
        /** Each heading is formatted as html list */
        foreach ($headings as $htext => $sub_headings) {
            /** The tags are stripped from the heading */
            $htext     = strip_tags($htext);
            /** The header id is generated */
            $htext_id  = str_replace(" ", "-", strtolower($htext));
            /** The header text is converted to link */
            $htext     = "<a href='#" . $htext_id . "'>" . $htext . "</a>";
            /** The heading text is enclosed in <li> tags */
            $toc_list .= "<li>" . $htext;
            /** If the sub headings are present */
            if (count(array_keys($sub_headings)) > 0) {
                /** The toc is generated from sub headings */
                $toc_list .= $this->GenerateTocList($sub_headings);
            }
            /** The <li> tag is closed */
            $toc_list .= "</li>";
        }
        /** The toc tag is closed */
        $toc_list .= "</ul>";
        
        return $toc_list;
    }
    
	/**
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
        $text          = str_replace("\n", "", $article_text);
        $text          = str_replace("\r", "", $text);

        /** The header tag */
        $tag           = "h" . $level;
        /** The required heading list */
        $heading_list  = array();
        /** The tag is extracted from the article text */
        preg_match_all("/<" . $tag . ".*>(.+)<\/" . $tag . ">/iU", $text, $matches1);
        /** If no matches were found */
        if (count($matches1[0]) == 0 && $level < 6) {
            /** The headings for the next level are extracted */
            $heading_list = $this->ExtractHeadings($text, ($level+1));
        }
        
        /** The text after each heading is extracted */
        for ($count = 0; $count < count($matches1[0]); $count++) {
            /** The extracted heading */
            $htext                = $matches1[0][$count];
            /** The next heading */
            $next_heading         = (isset($matches1[0][$count+1])) ? $matches1[0][$count+1] : "";
            /** The "/" is escaped */
            $next_heading         = str_replace("/", "\/", $next_heading);
            /** The "/" is escaped */
            $htext                = str_replace("/", "\/", $htext);
            /** The tag is extracted from the article text */
            preg_match_all("/" . $htext . "(.+)" . $next_heading . "/iU", $text, $matches2);
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
                $sub_heading_list = $this->ExtractHeadings($next_text, $next_level);
            }
            while(count($sub_heading_list) == 0 && $next_level < 6);
            /** The sub heading list is added to the main heading */
            $heading_list[$htext] = $sub_heading_list;
        }
        
        return $heading_list;
    }
}
