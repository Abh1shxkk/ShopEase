<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function createOrder(Order $order): array;
    public function verifyPayment(array $payload): bool;
    public function getPaymentDetails(string $paymentId): array;
}
