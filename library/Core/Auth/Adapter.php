<?php

class Core_Auth_Adapter implements Zend_Auth_Adapter_Interface {
    const NOT_FOUND_MESSAGE = "Invalid Username or Password";
    const BAD_PW_MESSAGE = "Password is invalid";


    /**
     *
     * @var Model_User
     */
    protected $user;
    /**
     *
     * @var string
     */
    protected $username;
    /**
     *
     * @var string
     */
    protected $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate() {
        try {

        	$this->user = Login_Model_Login::authenticate($this->username, $this->password);

        } catch (Exception $e) {
    
//             if ($e->getMessage() == Admin_Model_Login::WRONG_PW)
                 return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::NOT_FOUND_MESSAGE);
//             if ($e->getMessage() == Admin_Model_Login::NOT_FOUND)
//                 return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::NOT_FOUND_MESSAGE);
        }
        return $this->result(Zend_Auth_Result::SUCCESS);
    }

    private function result($code, $messages = array()) {
        if (!is_array($messages)) {
            $messages = array($messages);
        }

        return new Zend_Auth_Result(
                $code,
                $this->user,
                $messages
        );
    }

}

