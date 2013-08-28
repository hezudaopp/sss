<?php
require_once('MyException.class.php');
/**
 * 调用未实现的方法导致的错误
 *
 */
class UnimplementedMethodException extends MyException {
	public function __construct($appMsg){
		$msg = "调用了未实现的函数：".$appMsg;
		parent::__construct($msg);
	}
}


class InvalidArgumentsException extends MyException {
	public function __construct($appMsg){
		$msg = "无效的参数：$appMsg";
		parent::__construct($msg);
	}
}

class DispatchingUnreachable extends MyException {
	public function __construct($className){
		$msg = '使用未定义的类错误：'.$className;
		parent::__construct($msg);
	}
}

class DispatchingReset extends MyException {
	public function __construct($className, $methodName){
		$msg = $className.'类中没有这个方法：'.$methodName;
		parent::__construct($msg);
	}
}

/**
 * 用户自定义的类型
 *
 */
class CustomException extends MyException {
	/**
	 * 构造函数
	 *
	 * @param String $msg
	 */
	public function __construct($msg){
		$this->msg = $msg;
		parent::__construct($msg);
	}

	public function __toString(){
		return $this->msg;
	}
}

/**
 * 上传了系统不允许上传的类型的文件
 *
 */
class FileTypeForbiddenException extends MyException {
	/**
	 * 构造函数
	 *
	 * @param String $filename  当前上传的这个错误的类型的文件
	 */
	public function __construct($filename){
		$this->errMsg = '“'.$filename.'” 不是excel表格！';
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

/**
 * 处理所有文件上传错误
 *
 */
class FileUploadError extends MyException {
	/**
	 * 构造函数
	 *
	 * @param String $filename
	 * @param int $errCode	文件上传时的错误代码
	 */
	public function __construct($filename, $errCode){
		$this->filename = $filename;
		$this->errCode = $errCode;
		$errMsgArray = array(
			UPLOAD_ERR_OK => '文件上传成功',
			UPLOAD_ERR_INI_SIZE => '上传文件太大,超过服务器端的限制',
			UPLOAD_ERR_FORM_SIZE => '上传文件太大，超过了客户端的限制',
			UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
			UPLOAD_ERR_NO_FILE => '没有文件被上传',
			UPLOAD_ERR_NO_TMP_DIR => '找不到存放临时文件的目录，请确认你的临时文件目录(默认情况下是c:\windows\tmp)有空闲空间',
			UPLOAD_ERR_CANT_WRITE => '文件写入失败'
			);
		$this->errMsg = $errMsgArray[$this->errCode];
	}
	public function __toString(){
		return '错误代码：'.$this->errCode.',错误原因：'.$this->errMsg;
	}
}

/**
 * 上传的这个文件已经在数据库中
 *
 */
class UploadSameFileException extends MyException {
	/**
	 * 构造函数
	 *
	 * @param String $filename
	 * @param String $oldUploadedDate	原来的上传的那个文件的上传日期
	 */
	public function __construct($filename, $oldUploadedDate){
		$this->errMsg = '这个文件已经上传过：'.$filename.'(上传日期：'.$oldUploadedDate.');';
		parent::__construct($this->errMsg);
	}
	public function __toString(){
		return $this->errMsg;
	}
}

/**
 * 文件中缺少某些相关的列
 *
 */
class LackSomeColumn extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename 当前的文件名
	 * @param string $lackedColumn  缺少的列
	 */
	public function __construct($lackedColumn){
		$this->errMsg = '这个文件中缺少这些列：'.$lackedColumn;
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

/**
 * 文件中某些列不能为空
 *
 */
class existNullColumn extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename 当前的文件名
	 * @param string $lackedColumn  缺少的列
	 */
	public function __construct($nullColumn){
		$this->errMsg = '这个文件中这些列对应的行不能为空：'.$nullColumn;
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

/**
 * 文件中的数据船号和分段号数据和材料代码相对应
 *
 */
class existCollisionColumn extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename 当前的文件名
	 * @param string $lackedColumn  缺少的列
	 */
	public function __construct($collisionMaterial){
		$this->errMsg = '这个文件中这些材料代码的数据和船号或分段号有冲突：'.$collisionMaterial;
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}


/**
 * 文件中的某行数据在数据库中不存在
 *
 */
class notInDBColumn extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename 当前的文件名
	 * @param string $lackedColumn  缺少的列
	 */
	public function __construct($notInDBMaterials){
		$this->errMsg = '这个文件中这些材料代码对应的行的数据在数据库中不存在：'.$notInDBMaterials;
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}


/**
 * 文件中多余的某些相关的列
 *
 */
class RedundantColumn extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename 当前的文件名
	 * @param string $redundant  多余的列
	 */
	public function __construct($redundant){
		$this->errMsg = '这个文件中多了这些列：'.$redundant;
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

/**
 * 文件中不相同的列
 *
 */
class ExistDifferentColumns extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename 当前的文件名
	 * @param string $redundant  多余的列
	 */
	public function __construct($result,$type){
		if($type == "main"){
			$this->errMsg = "总表上传文件的格式必须是:
			['序号','材料代码','批次','生产厂家','船级', '材质','厚', '宽', '长', '数量', '单重', '重量','订单号','订单子项号','受订单价','购单号','备注']; 
			这个文件中这些列需要做修改(可能是这些列所在的列数不符)：'.$result.'";
		}
		elseif ($type == 'ruku'){
			$this->errMsg = "入库上传文件的格式必须是:
			['日期', '车号', '材料代码','船级', '材质','厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','库存地','备注']<br/>
			这个文件中这些列需要做修改(可能是这些列所在的列数不符)：'.$result.'";
		}
		elseif($type == 'chuku'){
			$this->errMsg = "出库上传文件的格式必须是:
			['日期', '车号', '材料代码','船级', '材质','厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','库存地','目的地','备注']<br/>
			这个文件中这些列需要做修改(可能是这些列所在的列数不符)：'.$result.'";
		}
		elseif ($type == 'sale'){
			$this->errMsg = "销售上传文件的格式必须是:
			['日期', '车号', '材料代码','船级', '材质','厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','目的地','备注']<br/>
			这个文件中这些列需要做修改(可能是这些列所在的列数不符)：'.$result.'";
		}
		elseif($type == 'fachuan'){
			$this->errMsg = "发船上传文件的格式必须是:
			['日期', '船号', '材料代码','船级', '材质','厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','库存地','目的地','备注']<br/>
			这个文件中这些列需要做修改(可能是这些列所在的列数不符)：'.$result.'";
		}
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}


/**
 * 如果文件中某些元数据错误，导致对文件无法正确读取，会抛出这个错误。
 *
 */
class ExcelMetaDataError extends MyException {
	public function __construct(){
		$this->errMsg = '当前文件中的某些元数据有错误，程序无法进行正确读取。解决方式是：将其中的数据复制到另一个新建的excel表格中，然后重新上传。';
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

/**
 * 出现未知的列========这个应该没那么智能，所以这个会永远也用不到
 * + 2008/8/29 by xombat 但现在在SSS_ExcelReader.class.php中的checkFirstRow()用到了
 *
 */
class UnknownColumnIndex extends MyException {
	/**
	 * 构造函数
	 *
	 * @param string $filename
	 * @param string $wrongIndex
	 */
	public function __construct($wrongIndex){
		$this->errMsg = '这个文件中有未知的列：'.$wrongIndex;
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

class InvalidDateFormat extends MyException {
	public function __construct($dateval){
		$this->errMsg = '这个日期值格式不正确：'.$dateval;
		parent::__construct($this->errMsg);
	}
	public function __toString(){
		return $this->errMsg;
	}
}

class InvalidSizeSearchKeyword extends MyException {
	public function __construct($keyword){
		$this->errMsg = '您输入的这个关键字：'.$keyword.'格式错误。按尺寸查询时在搜索框中按“厚，宽，长”（无引号，中间以逗号","分开）的格式输入，如果要只按照厚度或者宽度查询，可以使用高级查询';
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

class InvalidfaDateSearchKeyword extends MyException {
	public function __construct($keyword){
		$this->errMsg = '您输入的这个关键字：'.$keyword.'格式错误。按发车、发船日期查询时在搜索框中按“查询起始时间，查询结束时间”（无引号，中间以逗号分开）的格式输入，其中时间要以这样的格式：年/月/日，比如查询从2007年9月1号到2008年1月30号发车发船的条目，输入：2007/09/01,2008/01/30 或者 2007-09-10,2008-01-30 或 09/01/2007,01/30/2008（其中小于10的月份十位上也可以不写0）';
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

class InvalidUploadTimeSearchKeyword extends MyException{
	public function __construct($keyword){
		$this->errMsg = '您输入的这个关键字：'.$keyword.'格式错误。按上传日期查询时在搜索框中按“查询起始时间,查询结束时间”（无引号，中间以逗号分开）的格式输入，其中时间以这样的格式：年/月/日 时:分:秒，比如查询从2007年9月1号 下午两点到2008年1月30号 上午7点 上传的的条目，输入：2007/09/01 14:00:00, 2008/01/30 07:00:00 或者 2007-09-10 14:00:00 ,2008-01-30 07:00:00 或 09/01/2007 14:00:00 , 01/30/2008 07:00:00（其中小于10的月份十位上也可以不写0）';
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}


/**
 * 这个异常用在这个时候抛出：上传发车或者发传文件的时候，程序检测到当前上传的材料代码
 * 在以前上传的总表中找不到对应的材料代码。
 * 这个异常导致上传失败。
 *
 */
class HaveNotUploadedMaterialCode extends MyException {
	public function __construct($array){
		$this->errMsg = '当前文件中有些材料代码还从未上传过，程序猜测当前文件中可能有错误，因此拒绝上传。
		其中有个材料代码是："'.join('" , "', $array).'".';
		parent::__construct($this->errMsg);
	}

	public function __toString(){
		return $this->errMsg;
	}
}

class MaterialCharacterAndOrderNumbersConfilicts extends MyException {
	public function __construct($array){
		$this->errMsg = '有些汉字材料代码，在一个文件中船级材质厚宽长一样但是订单号或订单子项号不一样,这一项是：' . join(' | ', $array);
		parent::__construct($this->errMsg);
	}
	public function __toString(){
		return $this->errMsg;
	}
}

class DataTooLongForDBInsertion extends MyException {
	public function __construct($data, $maxSize){
		$this->errMsg = "数据太长无法插入。数据为：$data, 最大长度为：$maxSize";
		parent::__construct($this->errMsg);
	}
	public function __toString(){
		return $this->errMsg;
	}
}
?>