<?php
namespace Home\Model;
use Think\Model;
class GuestModel extends Model {
	
	protected $_validate = array(
		array('username','require','用户名不能为空',0,'regex',1),
		array('password','require','密码不能为空',0,'regex',1),
		array('repassword','password','两次密码不一致',0,'confirm',1),
		array('qq,qqverify','checkVerify','',1,'callback',1),
		array('username','','用户名已存在',0,'unique',1),
		array('qq','','QQ号码已存在',0,'unique',1),
		
		array('username','checkUsername','',0,'callback',3),
		array('username','require','用户名不能为空',1,'regex',4),
		array('password','require','密码不能为空',1,'regex',4),
		array('username,password','checkLogin','',1,'callback',4)
	);
	
	public function checkUsername($params){
		$b = preg_match('#^[a-zA-Z0-9]{6,15}$#', $params);
		if(!$b){
			$this->error = '用户名格式不正确(应为6到15位字母数字)';
		}else if(in_array($params,array('admin','test','demaxi','ahakan','mysql','vsftpd','root','www-data','www'))){
			$this->error = '该用户名禁止注册,请取其他名称';
		}else{
			return true;
		}
	}
	
	public function checkLogin($params){
		$mod = M('guest');
		$user = $mod->where('username="'.$params['username'].'"')->find();
		if(empty($user)){
			$this->error = '用户不存在';
		}else if($user['password']!=md5($params['password'].$user['randoms'])){
			$this->error = '密码错误';
		}else{
			session('userid',$user['id']);
			session('username',$user['username']);
			session('qq',$user['qq']);
			session('regtime',$user['regtime']);
			session('lastlogin',$user['lastlogin']);
			$mod = M('guest');
			$cache['lastlogin'] = time();
			$mod->where('id='.$user['id'])->save($cache);
			return true;
		}
	}
	
	public function checkVerify($params){
		$qqver = S('mail_'.$params['qq']);
		if(empty($params['qqverify'])){
			$this->error = '请输入邮箱验证码';
		}else if($qqver != trim($params['qqverify'])){
			$this->error = '验证码错误或已过期';
		}else{
			return true;
		}
	}
	
}