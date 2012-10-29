<?php

function smart_utf8_encode($value) {
	include_spip('inc/charsets');
	if (!is_array($value) && is_utf8($value)) return $value;
	else 
		if (function_exists('utf8_encode')) 
			$value = utf8_encode($value);
		else
			$value = unicode_to_utf_8(charset2unicode($value,'iso-8859-1'));
	return $value;
}

?>