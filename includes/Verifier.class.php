<?php

/**
 * 定义 Verifier 类,参考FleaPHP框架中的Verifier
 * 不支持php4,因为其中抛出了异常
 */

/**
 * Verifier 对象根据一系列验证规则对指定的数据进行验证
 * 采用singleton模式，保证verifier只有一个实例
 *
 * 验证规则由多个规则组成，每个规则用于验证一个字段。
 *
 * 每个规则可以包含下列基本属性：
 * name:            字段名
 * type:            字段类型
 * simpleType:      简单字段类型
 * maxLength:       最大长度
 * notNull:         是否不允许保存 NULL 值
 * notEmpty:        是否不允许为空字符串
 * binary:          是否是二进制数据
 * unsigned:        是否是无符号数值
 * hasDefault:      是否有默认值
 * defaultValue:    默认值
 *
 * 如果 notNull 为 true，而 hasDefault 为 false，则表示该字段必须有内容。
 *
 * simpleType 属性可以有下列值：
 *  C        - 长度小于等于 250 的字符串
 *  X        - 长度大于 250 的字符串
 *  B        - 二进制数据
 *  N        - 数值或者浮点数
 *  D        - 日期
 *  T        - TimeStamp
 *  L        - 逻辑布尔值
 *  I        - 整数
 *  R        - 自动增量或计数器
 *
 * 基本属性和利用 SDBO::metaColumns() 方法取得的字段信息完全一致。
 * 因此可以直接将 metaColumns() 的返回结果作为验证规则。
 *
 * 为了获得更强的验证能力，还可以使用下列扩展属性：
 *
 * complexType:     复杂字段类型
 * min:             最小值（仅用于数值型字段）
 * max:             最大值（仅用于数值型字段）
 * minLength:       最小长度（仅用于字符型和文本型字段）
 * maxLength:       最大长度（仅用于字符行和文本型字段）
 *
 * 对于 complexType 属性，可以有下列值：
 * NUMBER     - 数值（整数、浮点数）
 * INT        - 整数
 * ASCII      - ASCII 字符串（所有编码小于等于 127 的字符）
 * EMAIL      - Email 地址
 * DATE       - 日期（所有 GNU Date Input Formats，例如 yyyy/mm/dd、yyyy-mm-dd）
 * TIME       - 时间（所有 GNU Date Input Formats，例如 hh:mm:ss）
 * IPv4       - IPv4 地址（格式为 a.b.c.h）
 * OCTAL      - 八进制数值
 * BINARY     - 二进制数值
 * HEX        - 十六进制数值
 * DOMAIN     - Internet 域名
 * ANY        - 任意类型
 * STRING     - 字符串（等同于任意类型）
 * ALPHANUM   - 文字和数字（26个字母和0－9）
 * ALPHA      - 文字（26个字母）
 * ALPHANUMX  - 26个字母、10个数字以及 _ 符号
 *
 * 在客户端可以通过 verifier.js 提供的验证服务对数据进行验证，
 * 但客户端验证仅提供有限的验证能力。包括：
 *
 * notNull, hasDefault, min, max, minLength, maxLength
 *
 */
class Verifier {
	/**
	 * 持有Verifier这个singleton类的实例
	 * 如果已经被实例化的话
	 *
	 * @var Verifier
	 */
	private static $verifier = null;
	
	/**
	 * 私有构造函数
	 */
	private function Verifier() {
	}
	
	/**
	 * 获得Verifier的实例
	 *
	 * @return Verifier
	 */
	public static function getInstance() {
		if (self::$verifier == null) {
			self::$verifier = new Verifier ( );
			//$this->verifier = new Verifier();
		}
		return self::$verifier;
	}
	
	/**
	 * 检查所有字段，返回验证结果
	 *
	 * @param array $data
	 * @param array $rules
	 * @param boolean $skipEmpty
	 *
	 * @return array
	 */
	public function checkAll(& $data, & $rules, $skipEmpty = true) {
		$result = array ();
		foreach ( $rules as $rule ) {
			$name = $rule ['name'];
			if ((! isset ( $data [$name] ) || empty ( $data [$name] )) && $skipEmpty) {
				continue;
			}
			do {
				// 如果 notNull 为 true，而 hasDefault 为 false，则表示该字段必须有内容
				if (isset ( $rule ['notNull'] )) {
					if ($rule ['notNull']) {
						if (isset ( $rule ['hasDefault'] ) && $rule ['hasDefault']) {
							break;
						}
						if (isset ( $rule ['simpleType'] ) && $rule ['simpleType'] == 'R') {
							break;
						}
						if (! isset ( $data [$name] ) || $data [$name] === null) {
							$result [$name] = array ('check' => 'notNull', 'rule' => $rule );
							break;
						}
					} else {
						if (! isset ( $data [$name] ) || $data [$name] === null || $data [$name] === '') {
							break;
						}
					}
				}
				if (isset ( $rule ['notEmpty'] )) {
					if ($rule ['notEmpty']) {
						if (isset ( $rule ['hasDefault'] ) && $rule ['hasDefault']) {
							break;
						}
						if (isset ( $rule ['simpleType'] ) && $rule ['simpleType'] == 'R') {
							break;
						}
						if (! isset ( $data [$name] ) || $data [$name] == '') {
							$result [$name] = array ('check' => 'notEmpty', 'rule' => $rule );
							break;
						}
					} else {
						if (! isset ( $data [$name] ) || $data [$name] == '') {
							break;
						}
					}
				}
				
				$ret = $this->check ( $data [$name], $rule );
				if ($ret !== true) {
					$result [$name] = array ('check' => $ret, 'rule' => $rule );
				}
			} while ( false );
		}
		return $result;
	}
	
	/**
	 * 用指定规则验证值，验证通过返回 ture，否则返回没有通过的验证规则名
	 *
	 * @param mixed $value
	 * @param array $rule
	 *
	 * @return boolean
	 */
	public function check($value, & $rule) {
		// 首先使用 simpleType 验证值（如果 simpleType 属性存在）
		$checkLength = false;
		$checkMinMax = false;
		$ret = 'simpleType';
		if (isset ( $rule ['simpleType'] )) {
			switch ($rule ['simpleType']) {
				case 'C' : // 长度小于等于 250 的字符串
					if (strlen ( $value ) > 250) {
						return $ret;
					}
					$checkLength = true;
					break;
				case 'N' : // 数值或者浮点数
					if (! is_numeric ( $value )) {
						return $ret;
					}
					$checkMinMax = true;
					break;
				case 'D' : // 日期
					$test = @strtotime ( $value );
					if ($test === false || $test === - 1) {
						return $ret;
					}
					break;
				case 'I' : // 整数
					if (! is_numeric ( $value )) {
						return $ret;
					}
					if (intval ( $value ) != $value) {
						return $ret;
					}
					$checkMinMax = true;
					break;
				case 'X' : // 长度大于 250 的字符串
				case 'B' : // 二进制数据
					$checkLength = true;
					break;
				case 'T' : // TimeStamp
				case 'L' : // 逻辑布尔值
					break;
				case 'R' : // 自动增量或计数器
					$checkMinMax = true;
					break;
				default :
			}
		} else {
			$checkLength = true;
			$checkMinMax = true;
		}
		
		// 接着使用 complexType 验证值（如果 complexType 属性存在）
		$ret = 'complexType';
		if (isset ( $rule ['complexType'] )) {
			$func = 'is' . $rule ['complexType'];
			if (! method_exists ( $this, $func )) {
				throw new InvalidArgumentsException ( '$rule[\'complexType\']:' . $rule ['complexType'] );
				return null;
			}
			if (! $this->{$func} ( $value )) {
				return $ret;
			}
		}
		
		// min/max/minLength/maxLength 验证
		if ($checkMinMax) {
			$ret = 'min';
			if (isset ( $rule ['min'] ) && $value < $rule ['min']) {
				return $ret;
			}
			$ret = 'max';
			if (isset ( $rule ['max'] ) && $value > $rule ['max']) {
				return $ret;
			}
		}
		$ret = 'length';
		if ($checkLength) {
			$ret = 'minLength';
			if (isset ( $rule ['minLength'] ) && $rule ['minLength'] > 0 && strlen ( $value ) < $rule ['minLength']) {
				return $ret;
			}
			$ret = 'maxLength';
			if (isset ( $rule ['maxLength'] ) && $rule ['maxLength'] > 0 && strlen ( $value ) > $rule ['maxLength']) {
				return $ret;
			}
		}
		$ret = null;
		
		return true;
	}
	
	/**
	 * 数字
	 * 
	 * @return boolean
	 */
	public function isNUMBER($value) {
		return is_numeric ( $value );
	}
	
	/**
	 * 整数
	 * 
	 * @return boolean
	 */
	public function isINT($value) {
		return strlen ( intval ( $value ) ) == strlen ( $value ) && is_numeric ( $value );
	}
	
	/**
	 * ASCII 字符串（所有编码小于等于 127 的字符）
	 */
	public function isASCII($value) {
		$count = preg_match_all ( '/[\x20-\x7f]/', $value, $ar );
		return $count == strlen ( $value );
	}
	
	/**
	 * Email 地址
	 */
	public function isEMAIL($value) {
		return preg_match ( '/^[A-Za-z0-9]+([._\-\+]*[A-Za-z0-9]+)*@([A-Za-z0-9]+[-A-Za-z0-9]*[A-Za-z0-9]+\.)+[A-Za-z0-9]+$/', $value ) != 0;
	}
	
	/**
	 * 日期（所有 GNU Date Input Formats，例如 yyyy/mm/dd、yyyy-mm-dd）
	 */
	public function isDATE($value) {
		$test = @strtotime ( $value );
		return $test !== - 1 && $test !== false;
	}
	
	/**
	 * 时间（所有 GNU Date Input Formats，例如 hh:mm:ss）
	 */
	public function isTIME($value) {
		$test = strtotime ( $value );
		return $test !== - 1 && $test !== false;
	}
	
	/**
	 * IPv4 地址（格式为 a.b.c.h）
	 */
	public function isIPv4($value) {
		$test = ip2long ( $value );
		return $test !== - 1 && $test !== false;
	}
	
	/**
	 * 八进制数值
	 */
	public function isOCTAL($value) {
		return preg_match ( '/0[0-7]+/', $value ) != 0;
	}
	
	/**
	 * 二进制数值
	 */
	public function isBINARY($value) {
		return preg_match ( '/[01]+/', $value ) != 0;
	}
	
	/**
	 * 十六进制数值
	 */
	public function isHEX($value) {
		return preg_match ( '/[0-9a-f]+/i', $value ) != 0;
	}
	
	/**
	 * Internet 域名
	 */
	public function isDOMAIN($value) {
		return preg_match ( '/[a-z0-9\.]+/i', $value ) != 0;
	}
	
	/**
	 * 任意类型
	 */
	public function isANY($value) {
		return true;
	}
	
	/**
	 * 字符串（等同于任意类型）
	 */
	public function isSTRING($value) {
		return true;
	}
	
	/**
	 * 文字和数字（26个字母和0－9）
	 */
	public function isALPHANUM($value) {
		return ctype_alnum ( $value );
	}
	
	/**
	 * 文字（26个字母）
	 */
	public function isALPHA($value) {
		return ctype_alpha ( $value );
	}
	
	/**
	 * 26个字母及10个数字
	 */
	public function isALPHANUMX($value) {
		return preg_match ( '/^[A-Za-z0-9_]+$/', $value ) != 0;
	}
}
