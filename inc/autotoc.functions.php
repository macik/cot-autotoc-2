<?php

/**
 * Generates TOC structure array
 * @param string $text Source HTML text
 * @param array $toc_elements List of chapter definition elements
 * @param bool $flatlist Flag to return flat TOC list
 */
function getTOC($text, $toc_elements, $flatlist = false)
{
	if (!is_array($toc_elements)) return array();

	$chapters = array();
	$chapters_elem = array();
	$level = 0;
	foreach ($toc_elements as $k => $elem)

	{
		$elem = trim($elem);
		$headings = array();
		$pe_elem = preg_quote($elem);
		preg_match_all("`<{$pe_elem}([^>]*)>(.+?)</$pe_elem>`is", $text, $headings, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		if (!$headings) {
			unset($toc_elements[$k]);
			continue;
		}
		foreach ($headings as $heading)
		{
			$offset  = $heading[0][1];
			$attr   = $heading[1][0];
			$title  = $heading[2][0];
			$length = strlen($heading[0][0]);
			$chapters[$offset] = array(
				'heading' => $heading[0][0],
				'title'   => $title,
				'level'   => $level,
				'elem'    => $elem,
				'attr'    => $attr,
				'offset'  => $offset,
				'length'  => $length,
			);
		}
		$level++;
	}
	ksort($chapters); // ordering by offset

	$stack = $data = array();
	$deep = 0;
	$number = '';
	foreach ($chapters as $chapter)
	{
		$level = $chapter['level'];

		if ($deep == $level) {
		} elseif ($level > $deep) {
			$deep++;
			$base = $number;
		} else { // $level < $deep
			while ($deep > $level) {
				$sublist = $stack[$deep];
				unset($stack[$deep]);
				$deep--;
				$base = substr($base, 0, strrpos($base, '.'));
				$keys = array_keys($stack[$deep]);
				$last_key = array_pop($keys);
				$stack[$deep][$last_key]['sublist'] = $sublist;
			}
		}
		$count = sizeof($stack[$level])+1;
		$number = ($base ? $base . '.' : '') . $count;
		$chapter['number'] = $number;
		$data[$number] = $chapter;
		$stack[$deep][$number] = $chapter;
	}
	while ($deep > 0) {
		$sublist = $stack[$deep];
		unset($stack[$deep]);
		$deep--;
		if (is_array($stack[$deep]))
		{
			$keys = array_keys($stack[$deep]);
			$last_key = array_pop($keys);
			$stack[$deep][$last_key]['sublist'] = $sublist;
		}
	}
	return $flatlist ? $data : (is_array($stack[0]) ? $stack[0] : array());
}

/**
 * Builds TOC list and implodes anchors in source text
 * @param string $text source HTML
 * @param array $toc_data Array of TOC structure generated with getTOC() function
 * @return string
 */
function buildTOC(&$text, $toc_data)
{
	global $out;
	$toc = '';
	$toc_list = '';
	foreach($toc_data as $number => $data)
	{
		$toc_item = '';
		$prefix = cot::$cfg['plugin']['autotoc']['ch_prefix'];
		$class =  cot::$cfg['plugin']['autotoc']['ch_class'];
		$params = array(
			'title_safe' => trim(strip_tags($data['title'])),
			'class' => $class ? $class : 'anchor',
			'prefix' => $prefix ? $prefix : 'ch',
			'url' => $out['canonical_uri'] . '#' . ($prefix ? $prefix : 'ch') . $number,
		);
		$params['title_safe'] = preg_replace('/\s*[\.]$/i', '', $params['title_safe']);
		if (cot::$cfg['plugin']['autotoc']['strip_tags']) $data['title'] = $params['title_safe'];
		$tpl_data = array_merge($data, $params);

		$new_item = cot_rc('autotoc_anchor', $tpl_data);

		// adding anchors
		$pos = strpos($text, $data['heading']);
		$text = substr_replace($text, $new_item, $pos, strlen($data['heading']));

		if (count($data['sublist']) > 0)
		{
			$tpl_data['sublist'] = buildTOC($text, $data['sublist']);
		}
		$params['title_safe'] = preg_replace('/\s*[:\.]$/i', '', $params['title_safe']);
		$tpl_data['title'] = $tpl_data['title_safe'] = $params['title_safe'];
		$toc_item = cot_rc('autotoc_item', $tpl_data);
		$toc_list .= $toc_item;
	}
	if ($toc_list) $toc = cot_rc('autotoc_list', array('toc_list' => $toc_list));
	return $toc;
}
