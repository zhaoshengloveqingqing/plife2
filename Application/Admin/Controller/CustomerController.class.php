<?php
namespace Admin\Controller;
use Think\Controller;
class CustomerController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->CustomerLogic =  D('Customer','Logic');
        $this->Customer =  M('Customer');
    }
    private $Customer ;
    private $CustomerLogic ;

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }


    public function customermgr(){
        $this->checkPriv('2_1');
        $p = getCurPage();
        $res = $this->CustomerLogic->getCustomerList(array(),$p);
        $this->data = $res;
        $this->total = $this->CustomerLogic->getCustomerTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addcustomer(){
        $this->checkPriv('2_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['address'] = I('post.address');
            $newdata['contact'] = I('post.contact');
            $newdata['contactinfo'] = I('post.contactinfo');
            $ret = $this->Customer->add($newdata);
            if($ret){
                $this->redirect('Customer/customermgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Customer/customeredit");
        }
    }

    public function editcustomer(){
        $this->checkPriv('2_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['address'] = I('post.address');
            $newdata['contact'] = I('post.contact');
            $newdata['contactinfo'] = I('post.contactinfo');
            $ret = $this->Customer->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Customer/customermgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Customer/customeredit');
            }
        }else{
            $id = I('get.id','','int');
            if($id){
                $this->data = $this->Customer->getById($id);
                $this->display("Customer/customeredit");
            }else{
                $this->error('没有该记录');
            }

        }

    }
    public function delcustomer(){
        $this->checkPriv('2_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Customer->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
    public function dayreport(){
        $this->display();
    }
    public function monthreport(){
        $this->display();
    }
    public function quarterreport(){
        $this->display();
    }
    public function dataupdate(){
        $this->display();
    }
}