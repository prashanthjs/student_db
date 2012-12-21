<?php

class Core_Constants
{

    const SUPER_POWER_ADMIN = 1;

    const POWER_ADMIN = 2;

    const ADMIN = 3;

    const SURVEYOR = 4;

    const SURVEY_GOOD_TO_GO = 1;

    const SURVEY_CANCELLED = 2;

    const SURVEY_MISSING_INFORMATION = 3;

    const SURVEY_PENDING_LANDLORD_INFORMATION = 4;

    const SURVEY_AWAITING = 5;

    const PRIORITY_ATP = 1;

    const PRIORITY_PG = 2;

    const PRIORITY_FREE_ATP = 6;

    const PRIORITY_SPG = 5;

    const INSULATION_CAVITY = 1;

    const INSULATION_LOFT = 2;

    const INSULATION_DUAL = 3;

    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 2;
    
    /*
     * PAYMENT
     */
    
    const PAYMENT_PAID = 1;
    
    const PAYMENT_PENDING = 2;
    
    const PAYMENT_CANCELLED = 3;
    
    

    public static function getUser ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }
        return false;
    }

    public static function isSuperPowerAdmin ()
    {
        $user = static::getUser();
        if ($user && ($user->role->id == self::SUPER_POWER_ADMIN)) {
            return true;
        }
        return false;
    }

    public static function isPowerAdmin ()
    {
        $user = static::getUser();
        if ($user && ($user->role->id == self::POWER_ADMIN)) {
            return true;
        }
        return false;
    }

    public static function isAdmin ()
    {
        $user = static::getUser();
        if ($user && ($user->role->id == self::ADMIN)) {
            return true;
        }
        return false;
    }

    public static function isSurveyor ()
    {
        $user = static::getUser();
        if ($user && ($user->role->id == self::SURVEYOR)) {
            return true;
        }
        return false;
    }

}
