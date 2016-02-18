<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class AdminuserLogic extends \Think\Model{
    public function __construct(){
        $this->Admin = M('Admin');
        $this->Admingroup = M('Admingroup');
    }
    private $Admin;
    private $Admingroup;

    public function getAdminGroupTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Admingroup->where($mycond)->count();
        return $num;
    }

    public function getAdminGroupList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Admingroup->where($mycond)->page($pstr)->select();
        return $data;
    }

    public function getAllAdminGroup(){
        return $this->Admingroup->select();
    }

    public function delAdminGroup($id){
        $adminudata = array('privgid'=>0);
        $cond = array('privgid'=>$id);
        $this->Admin->where($cond)->save($adminudata);
        $this->Admingroup->where('id='.$id)->delete();
        return true;
    }

    public function getUserID($email){
        if($email){
            $data = $this->Admin->where(array('email'=>$email))->find();
            return $data;
        }else{
            return false;
        }
    }

}