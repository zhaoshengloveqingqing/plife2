<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class IndexFunctionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->functionLogic = D('IndexFunction', 'Logic');
        $this->Function = M('Function');
    }

    private $functionLogic;
    private $Function;

    private function checkPriv($priv)
    {
        $adminid = session('adminid');
        if (empty($adminid)) $this->redirect('Adminuser/login', 0);
        if (!session('issuper')) {
            if (!empty($priv) && !in_array($priv, session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }

    public function indexfunctionmgr(){
        $this->checkPriv('1_3_2');
        $p = getCurPage();

        $res = $this->functionLogic->getFunctionList(array(),$p);

        $this->total = $this->functionLogic->getFunctionTotal();
        $show = constructAdminPage($this->total);
        $this->data = $res;
        $this->assign('page',$show);
        $this->display();
    }

    public function addindexfunction(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['title'] = I('post.title');
            $newdata['sort'] = I('post.sort');
            $newdata['description'] = I('post.description');
            $newdata['create_date'] = date("Y-m-d H:i:s");
            $ret = $this->Function->add($newdata);
            if($ret){
                $this->redirect('IndexFunction/indexfunctionmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("IndexFunction/indexfunctionedit");
        }
    }

    public function editindexfunction(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['title'] = I('post.title');
            $newdata['sort'] = I('post.sort');
            $newdata['description'] = I('post.description');
            $ret = $this->Function->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('IndexFunction/indexfunctionmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->error('编辑数据失败');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->functionLogic->getFunctionById($id);
            $this->data = $data;
            $this->display("IndexFunction/indexfunctionedit");
        }
    }

    public function delindexfunction(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");
            $this->Function->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
}