<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoiceNumber(Order $order): string
    {
        $prefix = 'INV';
        $year = $order->created_at->format('Y');
        $month = $order->created_at->format('m');
        
        return "{$prefix}-{$year}{$month}-{$order->id}";
    }

    public function generatePdf(Order $order): \Barryvdh\DomPDF\PDF
    {
        $order->load(['user', 'items.product']);
        
        $invoiceNumber = $this->generateInvoiceNumber($order);
        
        // Get company details from settings
        $companyDetails = $this->getCompanyDetails();
        
        // Calculate tax breakdown (GST)
        $taxBreakdown = $this->calculateTaxBreakdown($order);
        
        $data = [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => $order->created_at->format('d M Y'),
            'company' => $companyDetails,
            'taxBreakdown' => $taxBreakdown,
        ];

        $pdf = Pdf::loadView('invoices.gst-invoice', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    public function download(Order $order)
    {
        $pdf = $this->generatePdf($order);
        $filename = 'invoice-' . $order->order_number . '.pdf';
        
        return $pdf->download($filename);
    }

    public function stream(Order $order)
    {
        $pdf = $this->generatePdf($order);
        $filename = 'invoice-' . $order->order_number . '.pdf';
        
        return $pdf->stream($filename);
    }

    public function saveToDisk(Order $order): string
    {
        $pdf = $this->generatePdf($order);
        $filename = 'invoices/invoice-' . $order->order_number . '.pdf';
        
        Storage::disk('public')->put($filename, $pdf->output());
        
        return $filename;
    }

    protected function getCompanyDetails(): array
    {
        return [
            'name' => SiteSetting::get('company_name', 'ShopEase'),
            'address' => SiteSetting::get('company_address', '123 Business Street'),
            'city' => SiteSetting::get('company_city', 'Mumbai'),
            'state' => SiteSetting::get('company_state', 'Maharashtra'),
            'zip' => SiteSetting::get('company_zip', '400001'),
            'country' => SiteSetting::get('company_country', 'India'),
            'phone' => SiteSetting::get('company_phone', '+91 98765 43210'),
            'email' => SiteSetting::get('company_email', 'support@shopease.com'),
            'gstin' => SiteSetting::get('company_gstin', '27AABCU9603R1ZM'),
            'pan' => SiteSetting::get('company_pan', 'AABCU9603R'),
            'logo' => SiteSetting::get('site_logo', null),
        ];
    }

    protected function calculateTaxBreakdown(Order $order): array
    {
        // GST breakdown - assuming 18% GST (9% CGST + 9% SGST for intra-state)
        $taxableAmount = $order->subtotal - ($order->discount ?? 0);
        $totalTax = $order->tax;
        
        // Check if intra-state or inter-state
        $companyState = SiteSetting::get('company_state', 'Maharashtra');
        $isIntraState = strtolower($order->shipping_state) === strtolower($companyState);
        
        if ($isIntraState) {
            // CGST + SGST
            return [
                'type' => 'intra_state',
                'taxable_amount' => $taxableAmount,
                'cgst_rate' => 9,
                'cgst_amount' => $totalTax / 2,
                'sgst_rate' => 9,
                'sgst_amount' => $totalTax / 2,
                'total_tax' => $totalTax,
            ];
        } else {
            // IGST
            return [
                'type' => 'inter_state',
                'taxable_amount' => $taxableAmount,
                'igst_rate' => 18,
                'igst_amount' => $totalTax,
                'total_tax' => $totalTax,
            ];
        }
    }
}
