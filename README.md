AutoTOC
=======

> Based on [original AutoTOC](https://github.com/GHengeveld/AutoTOC) plugin by [Gert Hengeveld](https://github.com/ghengeveld)

This Cotonti plugin automatically generates a Table Of Contents for any page. 
HTML heading tags are extracted and listed in a tree. The page body is modified 
to include anchors in source HTML.

Features on new AutoTOC
-----------------------

* Fixed bug with output empty TOC on some pages
* Now used canonical URL for TOC
* Customizing and overriding TOC display style via resource strings
* Added configurable class and prefix for TOC item

Installation
------------

Simply upload the plugin folder to your /plugins directory and enable the 
plugin in your Administration panel.

Configuration
-------------

The plugin has some configurable setting. You can configure the HTML tags that 
should be included in the TOC. Default value is h2,h3.
Also you can change class and prefix for TOC item.