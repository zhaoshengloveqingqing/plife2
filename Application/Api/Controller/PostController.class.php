<?php
namespace Api\Controller;
use Think\Controller;
class PostController extends Controller
{
    public function __construct()
    {

        $this->postLogic = D('Post', 'Logic');

        $this->Post = M('Post');
    }

    private $postLogic;
    private $Post;

    /**
     * 文章查询接口
     * @return json : data
     */
    public function getpostlists()
    {
        $data = $this->postLogic->getPostList();
        $this->ajaxReturn($data);
    }
}