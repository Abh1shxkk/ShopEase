<?php

namespace App\Services\Payment;

use App\Models\Order;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayService implements PaymentGatewayInterface
{
    protected Api $api;
    protected string $keyId;
    protected string $keySecret;

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key_id');
        $this->keySecret = config('services.razorpay.key_secret');
        $this->api = new Api($this->keyId, $this->keySecret);
    }

    public function createOrder(Order $order): array
    {
        $razorpayOrder = $this->api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) ($order->total * 100), // Amount in paise
            'currency' => 'INR',
            'notes' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],
        ]);

        // Save razorpay order id to our order
        $order->update(['razorpay_order_id' => $razorpayOrder->id]);

        return [
            'order_id' => $razorpayOrder->id,
            'amount' => $razorpayOrder->amount,
            'currency' => $razorpayOrder->currency,
            'key' => $this->keyId,
        ];
    }

    public function verifyPayment(array $payload): bool
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $payload['razorpay_order_id'],
                'razorpay_payment_id' => $payload['razorpay_payment_id'],
                'razorpay_signature' => $payload['razorpay_signature'],
            ]);
            return true;
        } catch (SignatureVerificationError $e) {
            return false;
        }
    }

    public function getPaymentDetails(string $paymentId): array
    {
        $payment = $this->api->payment->fetch($paymentId);
        return $payment->toArray();
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }
}
