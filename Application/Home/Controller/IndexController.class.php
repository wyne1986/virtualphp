<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class IndexController extends Controller {
	
    public function index(){
        $this->display();
    }
	
	public function login(){
		$this->display();
	}
	
	public function forget(){
		$this->display();
	}
	
	public function ssl(){
		$this->display();
	}
	
	public function logit(){
		$mod = D('Guest');
		if($mod->create($_POST,4)){
			$this->ajaxReturn(array('status'=>1,'message'=>'登录成功'));exit;
		}
		$this->ajaxReturn(array('status'=>2,'message'=>'登录失败:'.$mod->getError()));exit;
	}
	
	public function user(){
		$username = !empty(session('username')) ? session('username') : '';
		if(empty($username)){
			$this->error('请先登录',U('Index/login'),3);exit;
		}
		$mod = M('user');
		$user = $mod->where('username="'.$username.'"')->find();
		$result = $mod->field('username')->where('id>4')->select();
		$openssl = file_exists('/etc/apache2/sites-enabled/'.$username.'_ssl.conf') ? 1 : 0;
		$this->assign(array('user'=>$user,'result'=>$result,'openssl'=>$openssl,'password'=>S('passwd_'.$user['username'])));
		$this->display();
	}
	
	public function register(){
		$mod = D('Guest');
		$post = $_POST;
		if($mod->create($_POST)){
			$mod->randoms = rand(100000,999999);
			$mod->password = md5($mod->password.$mod->randoms);
			$mod->regtime = time();
			$mod->add();
			$modv = D('User');
			if($modv->create($_POST)){
				S('passwd_'.$post['username'],$post['password']);
				createhttp($post['username']);
				createdb($post['username'],$post['password']);
				$modv->dir = '/home/www/'.$post['username'];
				$modv->add();
				$this->ajaxReturn(array('status'=>1,'message'=>'注册成功'));
			}else{
				$this->ajaxReturn(array('status'=>2,'message'=>'注册失败:'.$modv->getError()));
			}
		}else{
			$this->ajaxReturn(array('status'=>2,'message'=>'注册失败:'.$mod->getError()));
		}
	}
	
	public function sendmail(){
		$qq = I('post.qq',0,'intval');
		if($qq<=0){
			$this->ajaxReturn(array('status'=>2,'message'=>'请输入正确的QQ号码!'));exit;
		}
		$mod = M('guest');
		$user = $mod->where('qq="'.$qq.'"')->find();
		if(!empty($user)){
			$this->ajaxReturn(array('status'=>2,'message'=>'QQ号码已被注册!'));exit;
		}
		$sd = S('mail_'.$qq);
		if(!empty($sd)){
			$this->ajaxReturn(array('status'=>2,'message'=>'已发送过邮件,请5分钟后再试!'));exit;
		}
		$qqmail = $qq.'@qq.com';
		$randoms = rand(100000,999999);
		$message = "
<html>
<head>
<title>".C('DOMAIN_NAME')."免费空间注册验证码</title>
</head>
<body>
<p><a href='http://www.".C('DOMAIN_SITE')."/'>".C('DOMAIN_NAME')."免费空间</a>注册验证码为:<font style='color:red;font-weight:bold'>".$randoms."</font><br/>此验证码5分钟内有效,请勿重复发送!</p>
</body>
</html>
";
		$b = SendMail($qqmail,$qq,'['.$randoms.']'.C('DOMAIN_NAME').'免费空间注册验证码',$message);
		if($b===true){
			S('mail_'.$qq,$randoms,300);
			$this->ajaxReturn(array('status'=>1,'message'=>'邮箱验证码发送成功,有效期5分钟,请登陆邮箱查看'));exit;
		}
		$this->ajaxReturn(array('status'=>2,'message'=>'邮箱验证码发送失败:'.$e));exit;
	}
	
	public function logout(){
		session('userid',NULL);
		session('username',NULL);
		session('qq',NULL);
		session('regtime',NULL);
		session('lastlogin',NULL);
		session_destroy();
		$this->success('退出成功',U('Index/login'),3);
	}
	
	public function forgetmail(){
		$qq = I('post.qq',0,'intval');
		$sd = S('forgot_'.$qq);
		if(!empty($sd)){
			$this->ajaxReturn(array('status'=>2,'message'=>'已发送过邮件,请5分钟后再试!'));exit;
		}else{
			$mod = M('guest');
			$user = $mod->where('qq="'.$qq.'"')->find();
			if(empty($user)){$this->ajaxReturn(array('status'=>2,'message'=>'用户不存在,请先注册'));exit;}
			$mod = M('guest');
			$qqmail = $qq.'@qq.com';
			$randoms = rand(100000,999999);
			$message = "
	<html>
	<head>
	<title>".C('DOMAIN_NAME')."免费空间注册验证码</title>
	</head>
	<body>
	<p><a href='http://www.".C('DOMAIN_SITE')."/'>".C('DOMAIN_NAME')."免费空间</a>密码找回验证码:<font style='color:red;font-weight:bold'>".$randoms."</font><br/>此验证码5分钟内有效,请勿重复发送!</p>
	</body>
	</html>
	";
			$b = SendMail($qqmail,$qq,'['.$randoms.']'.C('DOMAIN_NAME').'免费空间注册验证码',$message);
			if($b===true){
				S('forgot_'.$qq,$randoms,300);
				$this->ajaxReturn(array('status'=>1,'message'=>'邮箱验证码发送成功,有效期5分钟,请登陆邮箱查看'));exit;
			}
			$this->ajaxReturn(array('status'=>2,'message'=>'邮箱验证码发送失败:'.$e));exit;
		}
	}
	
	public function changepass(){
		$qq = I('post.qq',0,'intval');
		$qqverify = I('post.qqverify','');
		$password = I('post.password','');
		$repassword = I('post.repassword','');
		$qqver = S('forgot_'.$qq);
		if($qqver!=$qqverify){$this->error('验证码错误或已过期',U('Index/forget'),3);exit;}
		if(empty($password)){$this->error('密码不能为空'.$password,U('Index/forget'),3);exit;}
		if($password!=$repassword){$this->error('两次输入密码不一致',U('Index/forget'),3);exit;}
		S('forgot_'.$qq,null);
		S('passwd_'.$user['username'],$password);
		$mod = M('guest');
		$user = $mod->where('qq="'.$qq.'"')->find();
		if(empty($user)){$this->error('用户不存在,请先注册',U('Index/register'),3);exit;}
		$cache['password'] = md5($password.$user['randoms']);
		$mod->where('id='.$user['id'])->save($cache);
		$modu = M('user');
		$modu->where('username="'.$user['username'].'"')->save(array('password'=>md5($password)));		
		userpass($user['username'],$password);
		$this->success('密码修改成功,你的用户名为: '.$user['username'].',密码为: '.$password.'。<br/><a href="'.U('Index/login').'">如果长时间没跳转,请点这里</a>',U('Index/login'),8);
	}
	
	public function openssl(){
		$username = !empty(session('username')) ? session('username') : '';
		if(empty($username)){
			$this->ajaxReturn(array('status'=>2,'message'=>'请先登录'));
		}
		$msg = createhttps($username);
		if($msg===true){
			$this->ajaxReturn(array('status'=>1,'message'=>'创建成功'));
		}else{
			$this->ajaxReturn(array('status'=>2,'message'=>$msg));
		}
	}
}