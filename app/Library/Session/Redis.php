<?php
/**
 * User: salamander
 * Date: 2016/11/26
 * Time: 17:03
 * 存储格式改为JSON
 */

namespace App\Library\Session;


class Redis implements \SessionHandlerInterface
{
    private $redis = null;
    const SESSION_LOCK_PREFIX = 'session_lock_';
    const SESSION_PREFIX = 'whois_sessions:';
    private $liftTime = null;

    /**
     * Close the session
     * @since 5.4.0
     */
    public function close()
    {
        return true;
    }

    /**
     * Destroy a session
     * @return boolean
     * @since 5.4.0
     */
    public function destroy($session_id)
    {
        $this->redis->delete(self::SESSION_PREFIX . $session_id); // 释放session数据
        $this->redis->delete(self::SESSION_LOCK_PREFIX . $session_id); // 释放锁
        return true;
    }

    /**
     * Cleanup old sessions
     * @return boolean
     * @since 5.4.0
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * Initialize session
     * @since 5.4.0
     */
    public function open($save_path, $sessionName)
    {
        // 过期时间
        $this->liftTime = ini_get('session.gc_maxlifetime');
        $this->redis = new \Redis();
        $this->redis->pconnect('127.0.0.1', 6379);
        return true;
    }

    /**
     * Read session data
     * @since 5.4.0
     */
    public function read($session_id)
    {
        while( $this->redis->get(self::SESSION_LOCK_PREFIX . $session_id) ); // 阻塞
        $this->redis->incr(self::SESSION_LOCK_PREFIX . $session_id);// 模拟加锁
        // 判断存在记录
        $data = $this->redis->get(self::SESSION_PREFIX . $session_id);
        if(!$data) {
           return '';
        }
        $_SESSION = json_decode($data, true);
        return session_encode();
    }

    /**
     * Write session data
     * @since 5.4.0
     */
    public function write($session_id, $sessionData)
    {
        // note: $sessionData is not used as it has already been serialised by PHP,
        // so we use $_SESSION which is an unserialised version of $sessionData.
        $this->redis->setex(self::SESSION_PREFIX  . $session_id, $this->liftTime, json_encode($_SESSION));
        $this->redis->delete(self::SESSION_LOCK_PREFIX . $session_id); // 释放锁
        return true;
    }
}