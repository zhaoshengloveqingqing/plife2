<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class SystemController extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->AdminuserLogic =  D('Adminuser','Logic');
        $this->Admin =  M('Admin');
        $this->Admingroup =  M('Admingroup');
     }

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }

    private $AdminuserLogic ;
    private $Admin ;
    private $Admingroup ;

    public function rolemgr()
    {
        $this->checkPriv('9_1_1');
        $p = getCurPage();
        $res = $this->AdminuserLogic->getAdminGroupList(array(),$p);
        $this->data = $res;
        $this->total = $this->AdminuserLogic->getAdminGroupTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addrole()
    {
        $this->checkPriv('9_1_2');
        $this->assign('errcode', '0');
        $this->assign('act', 'add');
        if (I('post.act') == 'add') {
            $groupname = I('post.groupname');
            $cond = array('groupname' => $groupname);
            $ginfo = $this->Admingroup->where($cond)->select();
            if ($ginfo) {
                $this->assign('errcode', '1');  // 用户角色已存在
                $this->data = I('post.');
                $this->display('System/roleedit');
            } else {
                $newdata = array();
                $newdata['groupname'] = I('post.groupname');
                $newdata['groupdesc'] = I('post.groupdesc');
                $this->Admingroup->add($newdata);
                $this->redirect('System/rolemgr');
            }
        } else {
            $this->display("System/roleedit");
        }
    }

    public function editrole()
    {
        $this->checkPriv('9_1_3');
        $this->assign('act', 'edit');
        $this->assign('errcode', '0');
        if (I('post.act') == 'edit') {
            $groupname = I('post.groupname');
            $id = I('post.id', '', 'int');
            $cond = array();
            $cond['groupname'] = $groupname;
            $cond['id'] = array('neq', $id);
            $ret = $this->Admingroup->where($cond)->find();
            if ($ret) {
                $this->assign('errcode', '1'); // 已经有同名用户角色
                $this->data = I('post.');
                $this->display("System/roleedit");
            } else {
                $newdata = array();
                $newdata['groupname'] = $groupname;
                $newdata['groupdesc'] = I('post.groupdesc');
                $this->Admingroup->where('id=' . $id)->save($newdata);
                $this->redirect('System/rolemgr');
            }
        } else {
            $id = I('get.id', '', 'int');
            if ($id) {
                $this->data = $this->Admingroup->getById($id);
                $this->display("System/roleedit");
            } else {
                $this->error('无效记录');
            }
        }
    }

    public function delrole()
    {
        $this->checkPriv('9_1_4');
        $id=I('get.id','','int');
        if($id){
            $this->AdminuserLogic->delAdminGroup($id);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('没有该记录');
        }
    }

    public function rolepriv()
    {
        $this->checkPriv('9_1_5');
        $this->assign('act','priv');
        $id = I('get.id','','int');
        if(I('post.act')=='priv'){
            $id = I('post.id','','int');
            $privs = I('post.priv');
            if($privs){
                $privstr = implode(',',$privs);
                $save = array('priv'=>$privstr);
                $this->Admingroup->where('id='.$id)->save($save);
                $this->redirect('System/rolemgr');
            }else{
                $this->assign('errcode','1');
            }
        }else{
            $groupinfo = $this->Admingroup->getById($id);
            $privs = explode(',',$groupinfo['priv']);
            unset($groupinfo['priv']);
            foreach($privs as $v){
                $groupinfo['priv'][$v] = 'checked';
            }
            $this->assign('groupinfo',$groupinfo);
        }
        $this->assign('id',$id);
        $this->display();
    }

    public function adminusermgr()
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $backurl = pathinfo($url)['dirname'].'/'.'usersmgr';
        $this->checkPriv('9_2_1');
        $cond = array('Admin.issuper' => 0);
        $Admin = D('AdminuserView');
        $p = getCurPage();
        $this->total = (int)$Admin->where($cond)->count();
        $show = constructAdminPage($this->total);
        $this->assign('page', $show);
        $pstr = $p . ',' . C('ADMIN_REC_PER_PAGE');
        $data = $Admin->page($pstr)->where($cond)->order('id desc')->select();
        foreach ($data as &$d) {
            if (!$d['groupid'] || empty($d['groupname'])) {
                $d['groupname'] = '暂未分配';
            }
        }
        $this->data = $data;
        $agl = D('Adminuser','Logic');
        $this->assign('backurl',$backurl);
        $this->assign('admgrp',$agl->getAllAdminGroup());
        $this->display();
    }

    public function addadminuser()
    {
        $this->checkPriv('9_2_2');
        $this->assign('errcode', '0');
        $this->assign('act', 'add');
        if (I('post.act') == 'add') {
            $username = I('post.username');
            $cond = array('username' => $username);
            $uinfo = $this->Admin->where($cond)->select();
            if ($uinfo) {
                $this->assign('errcode', '1');  // 用户已存在
                $this->data = I('post.');
                $this->display('System/adminuseredit');
            } else {
                $newdata = array();
                $newdata['username'] = I('post.username');
                $newdata['nickname'] = I('post.nickname');
                $newdata['salt'] = getsalt();
                $newdata['password'] = TransPassUseSalt(I('post.password'), $newdata['salt']);
                $this->Admin->add($newdata);
                $this->redirect('System/adminusermgr');
            }
        } else {
            $this->display("System/adminuseredit");
        }
    }

    public function editadminuser()
    {
        $this->checkPriv('9_2_3');
        $this->assign('act', 'edit');
        $this->assign('errcode', '0');
        if (I('post.act') == 'edit') {
            $username = I('post.username');
            $id = I('post.id', '', 'int');
            $cond = array();
            $cond['username'] = $username;
            $cond['uid'] = array('neq', $id);
            $ret = $this->Admin->where($cond)->find();
            if ($ret) {
                $this->assign('errcode', '1'); // 已经有同名用户
                $this->data = I('post.');
                $this->display("System/adminuseredit");
            } else {
                $newdata = array();
                $newdata['username'] = $username;
                $newdata['nickname'] = I('post.nickname');
                $npw = trim(I('post.password'));
                if ($npw) {
                    $newdata['salt'] = getsalt();
                    $newdata['password'] = TransPassUseSalt($npw, $newdata['salt']);
                }
                $this->Admin->where('uid=' . $id)->save($newdata);
                $this->redirect('System/adminusermgr');
            }
        } else {
            $id = I('get.id', '', 'int');
            if ($id) {
                $this->data = $this->Admin->getByUid($id);
                $this->display("System/adminuseredit");
            } else {
                $this->error('无效记录');
            }
        }
    }

    public function chgadminuserstatus()
    {
        $this->checkPriv('9_2_4');
        $id = I('get.id','','int');
        if ($id && I('get.status') != '') {
            $newdata['status'] = I('get.status','','int');
            $this->Admin->where('uid=' . $id)->save($newdata);
        }
        $from = I('server.HTTP_REFERER');
        redirect($from);
    }

    public function chgadminusergrp(){
        $this->checkPriv('9_2_5');
        $selgrpid = I('post.selgroup','','int');
        $adminid = I('post.chgadmid','','int');
        if($selgrpid && $adminid){
            $newdata['privgid'] = $selgrpid;
            $this->Admin->where('uid='.$adminid)->save($newdata);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('异常错误');
        }
    }

    //morganzhao
    public function usersmgr(){
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $token = $_GET['token'];
        $data = $this->publicDecrypt($token);
        $object = new \Curl\Curl\Curl();
        $object->get("http://user.pinet.co/api/get_user_data", array('token' =>$token));
        if($object->http_status_code == 200) {
            $response = json_decode($object->response);
        }
        $newdata = array();
        $newdata['username'] = $response->username;
        $newdata['nickname'] = $response->last_name.$response->first_name;
        $newdata['uid'] = $response->uid;
        $newdata['salt'] = getsalt();
        $newdata['password'] = TransPassUseSalt($response->username, $newdata['salt']);
        $this->Admin->add($newdata);
        $this->redirect('System/adminusermgr');
        print_r($response);
    }

    public function get_default($arr, $key, $default = '') {
        if(is_object($arr))
            return isset($arr->$key)? $arr->$key: $default;
        if(is_array($arr))
            return isset($arr[$key])? $arr[$key]: $default;
        return $default;
    }
    public function publicDecrypt($message) {
        $this->keyPath = 'application/config/encryptor.key';
        $this->publicKeyPath = 'application/config/encryptor_public.key';
        $this->iv = mcrypt_create_iv(32, MCRYPT_DEV_URANDOM);

        if($this->keyPath && file_exists($this->keyPath)) {
            $this->privateKey = file_get_contents($this->keyPath);
        }
        else {
            $this->logger->debug('No private key or public set for this encryptor.');
        }

        if($this->publicKeyPath && file_exists($this->publicKeyPath)) {
            $this->publicKey = file_get_contents($this->publicKeyPath);
        }
        else {
            $this->logger->debug('No public key or public set for this encryptor.');
        }
        $key = $this->get_default($this, 'publicKey');
        if($key) {
            $ret = '';
            if(openssl_public_decrypt(base64_decode($message), $ret, $key)) {
                return $ret;
            }
        }
        return false;
    }

    public function logout(){
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $back_url = 'http://user.pinet.co/api/logout?appid=4000&template=user&callback='.pathinfo($url)['dirname'].'/adminusermgr.html';
        session(null);
        header('Location: '.$back_url.'');

    }
}