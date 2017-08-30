<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'ftp',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
	'EMAIL_CONFIG'			=>	array(
			'smtp_ssl' => 'ssl', // 使用安全协议 ssl\tcp
			'smtp_host' => "smtp.163.com", // SMTP 服务器
			'smtp_port' => "465", // SMTP服务器的端口号
			'smtp_user' => "outomo2012@163.com", // SMTP服务器用户名
			'smtp_pass' => "yxy19890120", // SMTP服务器密码
			'from_email' => "outomo2012@163.com", //发件箱
			'from_name' => "德马西", //发件人名
			'reply_email' => "outomo2012@163.com",	//回复邮件箱地址
			'reply_name' => "德马西",	//回复邮件人名
	),
	'URL_MODEL' => 1,	// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
	'DOMAIN_NAME'	=>	'德马西',
	'DOMAIN_SITE'	=>	'demaxi.cn',	//主机配置域名及泛域名
	'DOMAIN_IP'	=>	'192.168.1.194'		//主机IP地址
);
