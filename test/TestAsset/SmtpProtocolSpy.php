<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Mail\TestAsset;

use Laminas\Mail\Protocol\Smtp;

/**
 * Test spy to use when testing SMTP protocol
 *
 * @category   Laminas
 * @package    Laminas_Mail
 * @subpackage UnitTest
 */
class SmtpProtocolSpy extends Smtp
{
    protected $connect = false;
    protected $helo;
    protected $mail;
    protected $rcptTest = array();
    protected $sess = true;

    public function connect()
    {
        $this->connect = true;
        return true;
    }

    public function helo($serverName = '127.0.0.1')
    {
        parent::helo($serverName);
        $this->helo = $serverName;
    }

    public function quit()
    {
        $this->helo = null;
        $this->rset();
    }

    public function disconnect()
    {
        $this->helo    = null;
        $this->connect = false;
        $this->rset();
    }

    public function rset()
    {
        parent::rset();
        $this->rcptTest = array();
    }

    public function mail($from)
    {
        parent::mail($from);
        $this->mail = $from;
    }

    public function rcpt($to)
    {
        $this->rcpt = true;
        $this->rcptTest[] = $to;
    }

    protected function _send($request)
    {
        // Save request to internal log
        $this->_addLog($request . self::EOL);
    }

    protected function _expect($code, $timeout = null)
    {
        return '';
    }

    /**
     * Are we connected?
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->connect;
    }

    /**
     * Get server name we opened a connection with
     *
     * @return null|string
     */
    public function getHelo()
    {
        return $this->helo;
    }

    /**
     * Get value of mail property
     *
     * @return null|string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Get recipients
     *
     * @return array
     */
    public function getRecipients()
    {
        return $this->rcptTest;
    }
}
