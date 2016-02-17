<?php
namespace Mobile\Controller;
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
     * @param int pid : （可选）类型：1:视频（默认）  2:app
     * @return json : data
     */
    public function getcategories()
    {
        $pid = I('post.pid', '', 'int') ? I('post.pid', '', 'int') : 1;
        $data = $this->categoryLogic->getCategoryList($pid);
        $this->ajaxReturn($data);
    }
}