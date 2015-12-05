<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('autotoc');
require_once cot_incfile('autotoc','plug','resources');

/**
 * Generated TOC structure array
 * @param string $text Source HTML text
 * @param array $toc_elements List of chapter defenition elements
 */
function generateTOC($text, $toc_elements = array())
{
	global $chapters_elem;
	if (!is_array($toc_elements)) return array();

	$chapters = array();
	$chapters_elem = array();

	foreach ($toc_elements as $level => $elem)
	{
		$elem = trim($elem);
		$headings = array();
		preg_match_all("`<$elem>(.*?)</$elem>`is", $text, $headings, PREG_OFFSET_CAPTURE);
		$headings = $headings[1];
		if (!$headings) continue;
		foreach ($headings as $heading)
		{
			$title = $heading[0];
			$chapters[$heading[1]] = array($title, $level);
			$chapters_elem[$title] = $elem;
		}
	}
	ksort($chapters); // ordering by offset

	$toc = array();
	$parents = array();
	foreach ($chapters as $chapter)
	{
		list($title, $level) = $chapter;
		switch ($level)
		{
			case 0:
				$toc[$title] = array();
				$parents[$level] = $title;
				break;
			case 1:
				$toc[$parents[0]][$title] = array();
				$parents[$level] = $title;
				break;
			default:
				$toc[$parents[0]][$parents[1]][$title] = array();
				break;
		}
	}
	return $toc;
}

/**
 * Builds TOC list and implodes anchors in source text
 * @param string $text source HTML
 * @param array $chapters Array of chapters generated with generateTOC() function
 * @param string $parents current chapter level
 * @return string
 */
function buildTOC(&$text, $chapters, $parents = '')
{
	global $chapters_elem, $out;
	$i=0;
	$toc = '';
	$toc_list = '';
	foreach($chapters as $chapter_raw => $subchapters)
	{
		$i++;
		$toc_item = '';
		$level = $parents.$i;
		$prefix = cot::$cfg['plugin']['autotoc']['ch_prefix'];
		$class =  cot::$cfg['plugin']['autotoc']['ch_class'];
		$elem = $chapters_elem[$chapter_raw];
		$params = array(
			'level' => $level,
			'elem' => $elem,
			'chapter' => strip_tags(trim($chapter_raw)),
			'class' => $class ? $class : 'anchor',
			'prefix' => $prefix ? $prefix : 'ch',
			'url' => $out['canonical_uri'] . '#' . ($prefix ? $prefix : 'ch') . $level,
		);
		$text = str_replace(
			"<$elem>$chapter_raw</$elem>",
			cot_rc('autotoc_anchor', $params),
			$text);
		if (count($subchapters) > 0)
		{
			$sublist = buildTOC($text, $subchapters, $level.'.');
		}
		$toc_item = cot_rc('autotoc_item', array_merge($params, array('sublist' => $sublist)));
		$toc_list .= $toc_item;
	}
	if ($toc_list) $toc = cot_rc('autotoc_list', array('toc_list' => $toc_list));
	return $toc;
}

$elems = explode(',', $cfg['plugin']['autotoc']['elements']);
$text = $t->vars['PAGE_TEXT'];

$toc = generateTOC($text, $elems);
if (sizeof($toc)) $t->assign('PAGE_TOC', trim(buildTOC($text, $toc)));
$t->assign('PAGE_TEXT', $text);
