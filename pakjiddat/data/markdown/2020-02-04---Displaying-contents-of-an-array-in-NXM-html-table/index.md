---
title: Displaying contents of an array in NXM html table
date: "2018-03-24"
layout: post
draft: false
path: "/posts/displaying-contents-of-an-array-in-nxm-html-table"
category: "php"
tags:
  - "php"
  - "algorithms"
description: "The following code allows displaying the contents of an array containing X number of elements in a html table. The html table contains N rows and M columns. The values of X, N and M can be configured:"
---

The following code allows displaying the contents of an array containing X number of elements in a html table. The html table contains N rows and M columns. The values of X, N and M can be configured:

```
&lt;?php

/** The column counter */
$column_counter = 0;
/** The row counter */
$row_counter    = 0;
/** The total counter */
$total_counter  = 0;
/** The maximum number of columns to show */
$max_columns    = 3;
/** The maximum number of rows */
$max_rows       = 10;
/** A two dimensional array for storing the data */
$data           = array();

/** The sample data */
$model          = array_fill(0, 25, &quot;test&quot;);

/** Convert the $model array into a two dimensional array */
foreach($model as $d) {
	/** The data is added to array */
	$data[$row_counter][$column_counter] = $d;

	/** The total counter is increased by 1 */
	$total_counter++;
	/** The row counter is increased by 1 */
	$row_counter++;

	/** If the total counter is divisible by $max_rows, then column counter is increased by 1 and row counter is set to 0 */
	if ($total_counter % $max_rows == 0) {
		$column_counter++;
		$row_counter=0;
	}
}

/** The start table tag */
echo &quot;&lt;table&gt;&quot;;
/** Display the two dimensional data in html table. Each row is checked */
for ($count1 = 0; $count1 &lt; $max_rows; $count1++) {
	/** The row start tag is displayed */
	echo &quot;&lt;tr&gt;\n&quot;;
	for ($count2 = 0; $count2 &lt; $max_columns; $count2++) {
		/** If the column does not exist, then empty column is shown */
		$column_data   = (isset($data[$count1][$count2])) ? $data[$count1][$count2] : &quot;&nbsp;&quot;;
		/** The column data is shown */
		echo &quot;&lt;td&gt;&quot; . $column_data . &quot;&lt;/td&gt;\n&quot;;
	}
	/** The row end tag is displayed */
	echo &quot;&lt;/tr&gt;&quot;;
}
/** The end table tag */
echo &quot;&lt;/table&gt;&quot;;

?&gt;
```

See the discussion [3 column layout - how to have a limit in each column](https://stackoverflow.com/questions/49441466/3-column-layout-how-to-have-a-limit-in-each-column) for more information about how to display contents of an array in a HTML table
