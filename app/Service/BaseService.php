<?php
/**
 * User: salamander
 * Date: 2016/10/26
 * Time: 9:51
 */

namespace App\Service;


class BaseService
{
    protected $container = null;

    protected $db;

    public function __construct($c) {
        $this->container = $c;
        $this->db = $c->db;
    }

}