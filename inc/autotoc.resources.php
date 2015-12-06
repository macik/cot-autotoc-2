<?php

$R['autotoc_anchor'] = '<a class="{$class}" name="{$prefix}{$number}" id="{$prefix}{$number}"></a><{$elem}{$attr}>{$number}. {$title}</{$elem}>';
$R['autotoc_item'] = '<li><a href="{$url}" title="{$title_safe}">{$number}. {$title}</a>{$sublist}</li>';
$R['autotoc_list'] = '<ol style="list-style:none;" class="page_toc">{$toc_list}</ol>';