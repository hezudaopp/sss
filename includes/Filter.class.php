<?php
/**
 * 封装了一个过滤器，用于过滤一些非法字符
 *
 */
class Filter {
	
	/**
	 * 将字符转成适合在html中显示的字符，并且将换行符转成<br />字符串。
	 *
	 * @param string $value
	 * @return string
	 */
	function forHtml($value) {
		$value = nl2br ( htmlentities ( $value, ENT_QUOTES, 'utf-8' ) );
		return $value;
	}
	/**
	 * 需要过滤的字符：
	 * （一定会处理的）单引号"'", 双引号'"',
	 * （选择性处理的）"%"百分号，";"分号
	 * （未处理的）"-- "(两个减号加一个空格)字符， '#'井字符；
	 * 
	 * $removePct 是remove percent sign的缩写，如果这个被设成
	 * true,那么对字符串中的百分号和分号也会处理。
	 * 
	 * @param string $value
	 * @param boolean $removePct
	 */
	function forDBInsertion($value, $removePct = false) {
		$value = mysql_escape_string ( $value );
		if ($removePct) {
			$value = ereg_replace ( '([%;])', '\\\1', $value );
		}
		
		return $value;
	}
}

?>