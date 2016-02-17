<?php
namespace Admin\Controller;
use Think\Controller;
class ReportController extends Controller {
    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }

    public function index(){
        $this->checkPriv('3_0');
		$this->display();
    }
}