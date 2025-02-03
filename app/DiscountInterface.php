<?php

namespace App;

interface DiscountInterface
{
    public function applyDiscount($orderId);
}
