<?php
// 通用项目密码设置方法
function TransPassUseSalt($pstr,$salt){
   return md5($pstr.md5($salt));
}
// 通用生成4位干扰码
function getsalt(){
	$feed = 'ab4sjc1uid3pzfd5kr8nw59o6207';
	$feedlen = strlen($feed);
	$ret = '';
	for($i=0; $i<4; $i++){
		$r = mt_rand(1, $feedlen)-1;
		$ret .=  substr($feed,$r,1);
	}
	return $ret;
}
// 通用生成6位纯数字短信验证码
function getsmscode(){
	$feed = '0123456789';
	$feedlen = strlen($feed);
	$ret = '';
	for($i=0; $i<6; $i++){
		$r = mt_rand(1, $feedlen)-1;
		$ret .=  substr($feed,$r,1);
	}
	return $ret;
}
// 通用生成8位唯一编码
function getunicode(){
	$feed = 'abcdef3gh4ij5kmn7pq6rstu8vw9xy';
	$feedlen = strlen($feed);
	$ret = '';
	for($i=0; $i<8; $i++){
		$r = mt_rand(1, $feedlen)-1;
		$ret .=  substr($feed,$r,1);
	}
	return $ret;
}

// 通用返回上一页操作
function comeFrom(){
	$from = I('server.HTTP_REFERER');
    redirect($from);
}
// 获取汉字首字母函数
function getFirstChar($s0){
    $firstchar_ord=ord(strtoupper($s0{0}));
    if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0};
	$s=iconv("UTF-8","gb2312", $s0);
    // $s=$s0;
    $asc=ord($s{0})*256+ord($s{1})-65536;
    if($asc>=-20319 and $asc<=-20284)return "A";
    if($asc>=-20283 and $asc<=-19776)return "B";
    if($asc>=-19775 and $asc<=-19219)return "C";
    if($asc>=-19218 and $asc<=-18711)return "D";
    if($asc>=-18710 and $asc<=-18527)return "E";
    if($asc>=-18526 and $asc<=-18240)return "F";
    if($asc>=-18239 and $asc<=-17923)return "G";
    if($asc>=-17922 and $asc<=-17418)return "H";
    if($asc>=-17417 and $asc<=-16475)return "J";
    if($asc>=-16474 and $asc<=-16213)return "K";
    if($asc>=-16212 and $asc<=-15641)return "L";
    if($asc>=-15640 and $asc<=-15166)return "M";
    if($asc>=-15165 and $asc<=-14923)return "N";
    if($asc>=-14922 and $asc<=-14915)return "O";
    if($asc>=-14914 and $asc<=-14631)return "P";
    if($asc>=-14630 and $asc<=-14150)return "Q";
    if($asc>=-14149 and $asc<=-14091)return "R";
    if($asc>=-14090 and $asc<=-13319)return "S";
    if($asc>=-13318 and $asc<=-12839)return "T";
    if($asc>=-12838 and $asc<=-12557)return "W";
    if($asc>=-12556 and $asc<=-11848)return "X";
    if($asc>=-11847 and $asc<=-11056)return "Y";
    if($asc>=-11055 and $asc<=-10247)return "Z";
    return null;
}
// 格式化后台分页样式
function formatAdminPagebk(&$page){
    $page->setConfig('first','<span class="glyphicon glyphicon-step-backward" title="首页"></span>');
    $page->setConfig('prev','<span class="glyphicon glyphicon-chevron-left" title="上一页"></span>');
    $page->setConfig('next','<span class="glyphicon glyphicon-chevron-right" title="下一页"></span>');
    $page->setConfig('last','<span class="glyphicon glyphicon-step-forward" title="末页"></span>');
    $page->setConfig('theme',"<ul class='pagination'><li>%FIRST%</li><li>%UP_PAGE%</li><li>%LINK_PAGE%</li><li>%DOWN_PAGE%</li><li>%END%</li><li><a> %HEADER%  %NOW_PAGE%/%TOTAL_PAGE% 页</a></li></ul>");
}
// 格式化后台分页样式
function formatAdminPage(&$page){
    $page->setConfig('first','<span class="glyphicon glyphicon-step-backward" title="首页"></span>');
    $page->setConfig('prev','上一页');
    $page->setConfig('next','下一页');
    $page->setConfig('last','<span class="glyphicon glyphicon-step-forward" title="末页"></span>');
    $page->setConfig('theme',"<ul class='pagination pagination-xs m-top-none pull-right'><li>%UP_PAGE%</li><li>%LINK_PAGE%</li><li>%DOWN_PAGE%</li><li><a> %NOW_PAGE%/%TOTAL_PAGE% 页</a></li></ul>");
}
// 构建后台分页
function constructAdminPage($total){
    import("Vendor.Page");
    $page = new \Page($total,C('ADMIN_REC_PER_PAGE'));
    formatAdminPage($page);
    $show       = $page->show();
    return $show;
}
// 获取后台分页数
function getCurPage(){
	$p=1;
    if(I('get.p')){
        $tmp = I('get.p','','int');
        if($tmp>1 && $tmp<C('ADMIN_MAX_PAGE')){
            $p=$tmp;
        }
    }
	return $p;
}
// 获取前台分页数
function getHomeCurPage(){
	$p=1;
    if(I('get.p')){
        $tmp = I('get.p','','int');
        if($tmp>1 && $tmp<C('HOME_MAX_PAGE')){
            $p=$tmp;
        }
    }
	return $p;
}

// 无限分级情况下建立子目录
function mkdirs($dir)  {
	if(!is_dir($dir)){
		mkdir($dir, 0777, true);
	 }
	 return true;  
}

// 验证是否手机号码
function  checkMobileValidity($mobilephone){
	$exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|17[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]{8}$/";
	if(preg_match($exp,$mobilephone)){
		return true;
	}else{return false;}
}

// 验证是否有效跳转地址
function validRedirectUrl($urlstr){
	$from = urldecode($urlstr);
	if(empty($from)){return false;}
	else{
		$exlink = parse_url($from);
		if($exlink['host'] == I('server.HTTP_HOST')){
			return $from;
		}else{ return false; }
	}
}

// 返回安全过滤的数据机制
function safeInspec($input){
	return htmlspecialchars($input);	
}
// 返回安全过滤格式化保留小数点后两位的Money数据
function getMoney($input){
	return  number_format($input,2,'.','');
}
// 用户密码验证
//本项目定义为6-15位
//不能包含空格
function validUserpass($inputpass){
    $pos = stripos($inputpass,' ');
    if ($pos !== false) {
        return false;
    }
    $len = strlen($inputpass);
    if($len >15 || $len<6){
        return false;
    }
    return true;
}

// 生成随机uuid
function genUuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

// 检测密码强度
function passStrengthDetect($pwd){
	if( strlen($pwd) < 8 ) { return false;}  // 不能小于8位
	if( !preg_match("#[0-9]+#", $pwd) ) {return false;} // 必须包含至少一位数字
	if( !preg_match("#[a-z]+#", $pwd) ) {return false;} // 必须包含至少一位小写字母
	if( !preg_match("#[A-Z]+#", $pwd) ) {return false;} // 必须包含至少一位大写字母
	return true;
}

/**
 * 获取排序后的分类
 * @param  [type]  $data  [description]
 * @param  integer $pid   [description]
 * @param  string  $html  [description]
 * @param  integer $level [description]
 * @return [type]         [description]
 */
function getSortedCategory($data,$pid=0,$html="|---",$level=0)
{
    $temp = array();
    foreach ($data as $k => $v) {
        if($v['pid'] == $pid){

            $str = str_repeat($html, $level);
            $v['html'] = $str;
            $temp[] = $v;

            $temp = array_merge($temp,getSortedCategory($data,$v['id'],'|---',$level+1));
        }

    }
    return $temp;
}

/**
 * 邮件发送函数
 */
function sendMail($to, $title, $content) {

    vendor('PHPMailer.class#phpmailer');
    $mail = new \PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
    $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to,"尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return($mail->Send());
}
?>