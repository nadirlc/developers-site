---
title: Using JavaScript modules
date: "2018-11-25"
layout: post
draft: false
path: "/posts/using-javascript-modules"
category: "javascript"
tags:
  - "javascript"
description: "JavaScript Modules are an implementation of ES6 Modules. They allow a useful way to organize and reuse JavaScript code. JavaScript Modules consist of a set of features that provide module functionality."
---

JavaScript Modules are an implementation of ES6 Modules. They allow a useful way to organize and reuse JavaScript code. JavaScript Modules consist of a set of features that provide module functionality.

The **export** keyword allows exporting functions, constants, variables, class etc. For example:

```
// lib.mjs
export function Test(param1) {
    return &#x22;Hello World !&#x22;;
}
export const testdata = &#x22;Test&#x22;
```

The **import** keyword can be used to import exported functions and variables. For example:

```
// main.mjs
import Test from ./lib.mjs;
Test(&#x22;test argument&#x22;);
```

In the above example, the lib.mjs must have a relative path or it should be specified with **http(s)**.

Module files are loaded like normal JavaScript files but with the **type** attribute set to **"module"**. The following example shows how to load a module:

```
&#x3C;script type=&#x22;module&#x22; src=&#x22;main.mjs&#x22;&#x3E;&#x3C;/script&#x3E;
&#x3C;script nomodule src=&#x22;fallback.js&#x22;&#x3E;&#x3C;/script&#x3E;
```

In the above example, the **fallback.js** file is loaded only by browsers that do not understand the JavaScript module standard. Browsers that understand module standard ignore the fallback.js script.

Modules are different from normal scripts in that they are always executed in **strict mode**. HTML style comments are not supported in modules. Instead single line comments should be used. Module scripts are loaded with **Cross Origin Resource Sharing (CORS)**. This means the HTTP response that delivers the module code should include the HTTP Header: **Access-Control-Allow-Origin: *** or similar.

Another feature of JavaScript modules is that they are loaded by the browser in deferred mode by default, which means they are downloaded while the page is being parsed. JavaScript module files should have the **.mjs** file extension, but it is not required. The important thing is that the file is served with **content-type** of **text/javascript;**.

Dynamic import allows loading JavaScript modules from within scripts. The following example shows how to load JavaScript modules dynamically:

```
&#x3C;script type=&#x22;module&#x22;&#x3E;
  (async () =&#x3E; {
    const moduleSpecifier = &#x27;./lib.mjs&#x27;;
    const {repeat, shout} = await import(moduleSpecifier);
    repeat(&#x27;hello&#x27;);
    shout(&#x27;Dynamic import in action&#x27;);
  })();
&#x3C;/script&#x3E;
```

See the article [Using JavaScript modules on the web](https://developers.google.com/web/fundamentals/primers/modules) for more information about using JavaScript modules.
