<?php
/**
 * User: salamander
 * Date: 2016/10/9
 * Time: 13:07
 */

namespace App\Controller;

use App\Library\Pagination;
use Interop\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Http\Response as Response;



class BaseController
{
    protected $container;

    use Pagination;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function display(Request $request, Response $response, string $template, array $data = []) {
        $uri = $request->getUri();
        $data['path'] = $uri->getPath();
        return $this->container->renderer->render($response, $template, $data);
    }

    protected function error(Request $request, Response $response, $error) {
        return $this->display($request, $response, 'error.html', [
            'error' => $error,
        ]);
    }

}