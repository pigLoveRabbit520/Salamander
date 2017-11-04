<?php
/**
 * User: salamander
 * Date: 2017/11/4
 * Time: 20:10
 */

namespace App\Service;


class User extends BaseService
{
    public function getUsers() {
        return $this->db->fetchAll('SELECT * FROM user');
    }
}