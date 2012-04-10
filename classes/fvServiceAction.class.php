<?php

class fvServiceAction extends fvAction {
    function __construct() {
        parent::__construct(fvSite::$Layoult);
    }

    function executeIndex() {
        return self::$FV_NO_LAYOULT;
    }
}