<?php
namespace Api\Controller;
use Think\Controller;
class CategoryController extends Controller
{
    public function __construct()
    {

        $this->categoryLogic = D('Category', 'Logic');

        $this->Category = M('Category');
    }

    private $categoryLogic;
    private $Category;

    /**
     * 视频和app分类接口
     * @param int type : （可选）类型：1:视频（默认）  2:app 3:图书 4:装机分类
     * @return json : data
     */
    public function getcategories()
    {
        $type = I('post.type', '', 'int') ? I('post.type', '', 'int') : 1;
        $data = $this->categoryLogic->getCategoryList($type);
        $this->ajaxReturn($data);
    }
}