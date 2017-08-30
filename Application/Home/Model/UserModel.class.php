<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
	protected $_validate = array(
		array('username','require','用户名不能为空',0,'regex',1),
		array('password','require','密码不能为空',0,'regex',1),
		array('username','','用户名已存在',0,'unique',1)
	);
		protected $_auto = array(		array('password','md5',1,'function')	);
}