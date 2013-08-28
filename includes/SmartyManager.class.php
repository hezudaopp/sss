<?php

require_once ('smarty/Smarty.class.php');

if (! defined ( 'DS' )) {
	define ( 'DS', DIRECTORY_SEPARATOR );
}
if (! defined ( '__SITE_ROOT' )) {
	define ( '__SITE_ROOT', dirname ( dirname ( __FILE__ ) ) . DS );
}

class SmartyManager {
	private static $smarty;
	
	/**
	 * 获得这个smarty类
	 *
	 * @return Smarty
	 */
	public static function getSmarty() {
		if (self::$smarty === NULL) {
			self::$smarty = new Smarty ( );
			
			self::$smarty->template_dir = __SITE_ROOT . 'template' . DS;
			self::$smarty->compile_dir = __SITE_ROOT . 'template_c' . DS;
			self::$smarty->config_dir = __SITE_ROOT . 'configs' . DS;
			self::$smarty->cache_dir = __SITE_ROOT . 'cache' . DS;
			self::$smarty->left_delimiter = '<{';
			self::$smarty->right_delimiter = '}>';
		}
		return self::$smarty;
	}
}
?>