AutoTOC
=======

> Based on [original AutoTOC](https://github.com/GHengeveld/AutoTOC) plugin by [Gert Hengeveld](https://github.com/ghengeveld)

This Cotonti plugin automatically generates a Table Of Contents for any page. 
HTML heading tags are extracted and listed in a tree. The page body is modified 
to include anchors in source HTML.

Features of new AutoTOC
-----------------------

* Fixed bug with output empty TOC on some pages
* Fixed GHengeveld/AutoTOC#1 (wrong numbering on same titles)
* Now used canonical URL for TOC
* Customizing and overriding TOC display style via resource strings
* Added configurable class and prefix for TOC item
* Allow TOC elements with attributes
* Allow unlimited nesting

Installation
------------

Simply upload the plugin folder to your /plugins directory and enable the 
plugin in your Administration panel.

Configuration
-------------

The plugin has some configurable setting. You can configure the HTML tags that 
should be included in the TOC. Default value is h2,h3.
Also you can change class and prefix for TOC item.

Resource strings
----------------

As default plugin uses these strings:
```
$R['autotoc_anchor'] = '<a class="{$class}" name="{$prefix}{$number}" id="{$prefix}{$number}"></a><{$elem}{$attr}>{$number}. {$title}</{$elem}>';
$R['autotoc_item'] = '<li><a href="{$url}" title="{$title_safe}">{$number}. {$title}</a>{$sublist}</li>';
$R['autotoc_list'] = '<ol style="list-style:none;" class="page_toc">{$toc_list}</ol>';
```

It can be re-assigned by yourself to suit your needs. You can use these variables in resource strings:

* `heading` — full heading string (with all HTML markup)
* `title` — full title (with inner markup if exists)
* `title_safe` — title with stripped inner tags
* `class` — anchor class (configured in admin panel)
* `prefix` — anchor prefix (configured in admin panel)
* `url` —  fullpath url with anchor for current heading
* `elem` — HTML tag represents current heading
* `number` — heading count number on current level
