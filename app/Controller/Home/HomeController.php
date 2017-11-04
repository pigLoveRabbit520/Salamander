<?php
/**
 * User: salamander
 * Date: 2016/10/11
 * Time: 8:46
 */

namespace App\Controller\Home;

use App\Controller\BaseController;
use App\Library\InputFilter;
use App\Library\Upload;
use App\Service\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Tool\Validator;
use App\Tool\Sender;
use App\Tool\Chkcode;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response) {
        return $this->display($request, $response, 'index.html');
    }

    
}