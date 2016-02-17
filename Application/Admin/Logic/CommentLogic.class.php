<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class CommentLogic extends \Think\Model{
    public function __construct(){
        $this->Comment = M('Comment');
    }
    private $Comment;

    public function getCommentTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Comment->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getCommentList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Comment->where($mycond)->where('isdel is null')->page($pstr)->order('creatime asc')->select();
        return $data;
    }

    public function getCommentById($id){
        if($id){
            $data = $this->Comment->getById($id);
            return $data;
        }else{
            return false;
        }
    }
}
