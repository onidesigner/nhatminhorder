<?php

namespace App;

use App\OrderService;
use App\Service;
use App\ServiceBuying;
use App\ServiceChecking;
use App\ServiceWoodCrating;

class ServiceFeeBase {
    public function __construct($className) {
        if(class_exists($className)) {
            return new $className();
        }

        return null;
    }
}