<?php
/**
 * User: salamander
 * Date: 2017/12/15
 * Time: 15:29
 */

namespace App\Library;


trait Pagination {
    /**
     * 渲染分页
     * @param $total
     * @param $pageSize
     * @param $page int 当前页码
     */
    protected function paginate(int $total, int $pageSize, int $page) {
        $pageCount = ceil($total / $pageSize);
        $uri = $this->container->request->getUri()->getPath();

        $getArr = $_GET;
        if(array_key_exists('page', $getArr)) {
            unset($getArr['page']);
        }
        $params = http_build_query($getArr);
        if($params) {
            $jumpUrl = "{$uri}?{$params}&";
        } else {
            $jumpUrl = "{$uri}?";
        }
        ob_start();
        include ($this->container['settings']['renderer']['template_path'] . 'pagination.html');
        $pagination = ob_get_contents();
        ob_end_clean();
        $this->container->renderer->addAttribute('pagination', $pagination);
    }
}