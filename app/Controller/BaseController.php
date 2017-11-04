<?php
/**
 * User: salamander
 * Date: 2016/10/9
 * Time: 13:07
 */

namespace App\Controller;

use App\Service\Common;
use Interop\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        // 兼容原来的数据库操作
        if(property_exists($this, 'db')) {
            $this->db = $this->container->db;
        }
    }

    protected function display(Request $request, Response $response, string $template, array $data = []) {
        $uri = $request->getUri();
        $data['path'] = $uri->getPath();
        return $this->container->renderer->render($response, $template, $data);
    }

}