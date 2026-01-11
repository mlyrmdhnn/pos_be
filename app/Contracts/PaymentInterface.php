<?php

namespace App\Contracts;

interface PaymentInterface {
    public function pay($payment);
}