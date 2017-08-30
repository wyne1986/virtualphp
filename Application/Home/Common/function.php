<?php
/*
created by wyne
2015.5.30
QQ:366659539
*/
	
	function isEmail($str) {
        if (!$str) {
            return false;
        }
        return preg_match('#[a-z0-9&\-_.]+@[\w\-_]+([\w\-.]+)?\.[\w\-]+#is', $str) ? true : false;
    }
    /**
     * 正则表达式验证网址
     *
     * @param string $str    所要验证的网址
     * @return boolean
     */
    function isUrl($str) {
        if (!$str) {
            return false;
        }
        return preg_match('#(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?#i', $str) ? true : false;
    }
    /**
     * 验证字符串中是否含有汉字
     *
     * @param integer $string    所要验证的字符串。注：字符串编码仅支持UTF-8
     * @return boolean
     */
    function isChineseCharacter($string) {
        if (!$string) {
            return false;
        }
        return preg_match('~[\x{4e00}-\x{9fa5}]+~u', $string) ? true : false;
    }
    /**
     * 验证字符串中是否含有非法字符
     *
     * @param string $string    待验证的字符串
     * @return boolean
     */
    function isInvalidStr($string) {
        if (!$string) {
            return false;
        }
        return preg_match('#[!#$%^&*(){}~`"\';:?+=<>/\[\]]+#', $string) ? true : false;
    }
    /**
     * 用正则表达式验证邮证编码
     *
     * @param integer $num    所要验证的邮政编码
     * @return boolean
     */
    function isPostNum($num) {
        if (!$num) {
            return false;
        }
        return preg_match('#^[1-9][0-9]{5}$#', $num) ? true : false;
    }
    /**
     * 正则表达式验证身份证号码
     *
     * @param integer $num    所要验证的身份证号码
     * @return boolean
     */
    function isPersonalCard($num) {
        if (!$num) {
            return false;
        }
        return preg_match('#^[\d]{15}$|^[\d]{18}$#', $num) ? true : false;
    }
    /**
     * 正则表达式验证IP地址, 注:仅限IPv4
     *
     * @param string $str    所要验证的IP地址
     * @return boolean
     */
    function isIp($str) {
        if (!$str) {
            return false;
        }
        if (!preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $str)) {
            return false;
        }
        $ipArray = explode('.', $str);
        //真实的ip地址每个数字不能大于255（0-255）
        return ($ipArray[0]<=255 && $ipArray[1]<=255 && $ipArray[2]<=255 && $ipArray[3]<=255) ? true : false;
    }
    /**
     * 用正则表达式验证出版物的ISBN号
     *
     * @param integer $str    所要验证的ISBN号,通常是由13位数字构成
     * @return boolean
     */
    function isBookIsbn($str) {
        if (!$str) {
            return false;
        }
        return preg_match('#^978[\d]{10}$|^978-[\d]{10}$#', $str) ? true : false;
    }
    /**
     * 用正则表达式验证手机号码(中国大陆区)
     * @param integer $num    所要验证的手机号
     * @return boolean
     */
    function isMobile($num) {
        if (!$num) {
            return false;
        }
        return preg_match('#^1[\d]{10}$#', $num) ? true : false;
    }
    /**
     * 检查字符串是否为空
     *
     * @access public
     * @param string $string 字符串内容
     * @return boolean
     */
    function isMust($string = null) {
        //参数分析
        if (is_null($string)) {
            return false;
        }
        return empty($string) ? false : true;
    }
    /**
     * 检查字符串长度
     *
     * @access public
     * @param string $string 字符串内容
     * @param integer $min 最小的字符串数
     * @param integer $max 最大的字符串数
     */
    function isLength($string = null, $min = 0, $max = 255) {
        //参数分析
        if (is_null($string)) {
            return false;
        }
        //获取字符串长度
        $length = strlen(trim($string));
        return (($length >= (int)$min) && ($length <= (int)$max)) ? true : false;
    }
	
	//邮件发送方法
    function SendMail($to,$name,$subject='',$body='',$attachment=null){
    	Vendor('PHPMailer.PHPMailerAutoload');
		$config = C('EMAIL_CONFIG');
    	$mail = new PHPMailer(); //PHPMailer对象
    	$mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    	$mail->IsSMTP(); // 设定使用SMTP服务
    	$mail->SMTPDebug = 0; // 关闭SMTP调试功能  1 = errors and messages  2 = messages only
    	$mail->SMTPAuth = true; // 启用 SMTP 验证功能
    	$mail->SMTPSecure = $config['smtp_ssl']; // 使用安全协议 ssl\tcp
    	$mail->Host = $config['smtp_host']; // SMTP 服务器
    	$mail->Port = $config['smtp_port']; // SMTP服务器的端口号
    	$mail->Username = $config['smtp_user']; // SMTP服务器用户名
    	$mail->Password = $config['smtp_pass']; // SMTP服务器密码
    	$mail->SetFrom($config['from_email'], $config['from_name']);
    	$replyEmail = $config['reply_email']?$config['reply_email']:$config['from_email'];
    	$replyName = $config['reply_name']?$config['reply_name']:$config['from_name'];
    	$mail->AddReplyTo($replyEmail, $replyName);
    	$mail->Subject = $subject;
    	$mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
    	$mail->MsgHTML($body);
    	$mail->AddAddress($to, $name);
    	if(is_array($attachment)){ // 添加附件
    		foreach ($attachment as $file){
    			is_file($file) && $mail->AddAttachment($file);
    		}
    	}
    	return $mail->Send() ? true : $mail->ErrorInfo;
    }
	
	//Ubuntu设置域名
	function createhttp($user){
		mkdir('/home/www/'.$user,0777,true);
		exec('sudo /bin/chown www-data:www-data -R /home/www/'.$user);
		file_put_contents('/home/www/'.$user.'.conf','<VirtualHost '.C('DOMAIN_IP').'>
	ServerName '.$user.'.demaxi.cn
	ServerAlias '.$user.'.'.C('DOMAIN_SITE').'
	DocumentRoot /home/www/'.$user.'
	ErrorLog ${APACHE_LOG_DIR}.error.log
	CustomLog ${APACHE_LOG_DIR}.access.log combined
</VirtualHost>');
		exec('sudo /bin/mv /home/www/'.$user.'.conf /etc/apache2/sites-enabled/'.$user.'.conf');
		exec('sudo /bin/chown root:root -R /etc/apache2/sites-enabled/'.$user.'.conf');
		exec('sudo /etc/init.d/apache2 reload');
	}
	
	function createdb($user,$password){
		$mod = M();
		$mod->execute('CREATE DATABASE IF NOT EXISTS `'.$user.'` DEFAULT CHARSET utf8 COLLATE utf8_general_ci');
		$mod->execute('CREATE USER "'.$user.'"@"%" IDENTIFIED BY "'.$password.'"');
		$mod->execute('GRANT ALL PRIVILEGES ON `'.$user.'`.* TO "'.$user.'"@"%"');
		$mod->execute('FLUSH PRIVILEGES');
	}
	
	//创建https
	function createhttps($user){
		mkdir('/home/www/'.$user.'/pem/',0777,true);
		exec('sudo /bin/chown www-data:www-data -R /home/www/'.$user.'/pem/');
		$csr = '/home/www/'.$user.'/pem/root_bundle.crt';
		$crt = '/home/www/'.$user.'/pem/'.$user.'.'.C('DOMAIN_SITE').'.crt';
		$key = '/home/www/'.$user.'/pem/'.$user.'.'.C('DOMAIN_SITE').'.key';
		
		if(!file_exists($key) || !file_exists($crt) || !file_exists($csr)){
			
			return '请先上传证书文件,并确保证书文件名正确';
			
		}else if(!openssl_x509_parse(file_get_contents($csr)) || !openssl_x509_check_private_key(file_get_contents($crt),file_get_contents($key))){
			return '证书验证失败,请确认证书文件正确';
		}else{
				
			file_put_contents('/home/www/'.$user.'/'.$user.'_ssl.conf','<VirtualHost '.C('DOMAIN_IP').':443>
			ServerName '.$user.'.'.C('DOMAIN_SITE').'
			ServerAlias '.$user.'.'.C('DOMAIN_SITE').'
			DocumentRoot /home/www/'.$user.'
			SSLEngine on
			SSLCertificateFile /home/www/'.$user.'/pem/'.$user.'.'.C('DOMAIN_SITE').'.crt
			SSLCertificateKeyFile /home/www/'.$user.'/pem/'.$user.'.'.C('DOMAIN_SITE').'.key
			SSLCertificateChainFile /home/www/'.$user.'/pem/root_bundle.crt
			ErrorLog ${APACHE_LOG_DIR}.error.log
			CustomLog ${APACHE_LOG_DIR}.access.log combined
		</VirtualHost>');
			exec('sudo /bin/mv /home/www/'.$user.'/'.$user.'_ssl.conf /etc/apache2/sites-enabled/'.$user.'_ssl.conf');
			exec('sudo /bin/chown root:root -R /etc/apache2/sites-enabled/'.$user.'_ssl.conf');
			exec('sudo /etc/init.d/apache2 reload');
			return true;
		}
	}
	
	//chang dbuser password
	function userpass($username,$password){
		$mod = M();
		$mod->execute('UPDATE `user` SET `password`=PASSWORD("'.$password.'") WHERE `User`="'.$username.'" AND `Host`="%"');
		$mod->execute('FLUSH PRIVILEGES');
	}