<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('autotoc');
require_once cot_incfile('autotoc','plug');
require_once cot_incfile('autotoc','plug','resources');

$elems = explode(',', $cfg['plugin']['autotoc']['elements']);
$text = $t->vars['PAGE_TEXT'];

$toc = getTOC($text, $elems);
if (sizeof($toc)) {
	$t->assign('PAGE_TOC', trim(buildTOC($text, $toc)));
	$t->assign('PAGE_TEXT', $text);
}

