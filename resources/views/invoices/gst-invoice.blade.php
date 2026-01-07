<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tax Invoice - {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .invoice-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .invoice-title {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
        }
        .invoice-label {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 2px;
        }
        .invoice-number {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .info-box:first-child {
            padding-left: 0;
        }
        .info-box:last-child {
            padding-right: 0;
        }
        .info-box-inner {
            background: #f8f8f8;
            padding: 12px;
            border-left: 3px solid #1a1a1a;
        }
        .info-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }
        .info-value strong {
            font-size: 12px;
            display: block;
            margin-bottom: 3px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th {
            background: #1a1a1a;
            color: #fff;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.items th:last-child,
        table.items td:last-child {
            text-align: right;
        }
        table.items td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        table.items tr:nth-child(even) {
            background: #fafafa;
        }
        .item-name {
            font-weight: 600;
            color: #1a1a1a;
        }
        .item-sku {
            font-size: 9px;
            color: #999;
            margin-top: 2px;
        }
        .summary-section {
            display: table;
            width: 100%;
        }
        .tax-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .totals {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 10px;
        }
        .totals-table td:last-child {
            text-align: right;
        }
        .totals-table .subtotal td {
            border-top: 1px solid #eee;
        }
        .totals-table .total td {
            background: #1a1a1a;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            padding: 10px;
        }
        .tax-box {
            background: #f8f8f8;
            padding: 12px;
            font-size: 10px;
        }
        .tax-box-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
        }
        .tax-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        .tax-row span {
            display: table-cell;
        }
        .tax-row span:last-child {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .footer-content {
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .footer-right {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: bottom;
        }
        .terms {
            font-size: 9px;
            color: #666;
            line-height: 1.6;
        }
        .terms-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .signature-box {
            margin-top: 20px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 150px;
            margin-left: auto;
            padding-top: 5px;
            font-size: 9px;
            color: #666;
        }
        .gstin-badge {
            display: inline-block;
            background: #1a1a1a;
            color: #fff;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }
        .payment-status {
            display: inline-block;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0,0,0,0.03);
            font-weight: bold;
            letter-spacing: 10px;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">TAX INVOICE</div>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <div class="company-name">{{ $company['name'] }}</div>
                    <div class="company-details">
                        {{ $company['address'] }}<br>
                        {{ $company['city'] }}, {{ $company['state'] }} - {{ $company['zip'] }}<br>
                        Phone: {{ $company['phone'] }} | Email: {{ $company['email'] }}
                    </div>
                    <div class="gstin-badge">GSTIN: {{ $company['gstin'] }}</div>
                </div>
                <div class="invoice-title">
                    <div class="invoice-label">TAX INVOICE</div>
                    <div class="invoice-number">
                        <strong>Invoice #:</strong> {{ $invoiceNumber }}<br>
                        <strong>Date:</strong> {{ $invoiceDate }}<br>
                        <strong>Order #:</strong> {{ $order->order_number }}
                    </div>
                    <div class="payment-status {{ $order->payment_status === 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ $order->payment_status === 'paid' ? 'PAID' : 'PAYMENT PENDING' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill To / Ship To -->
        <div class="info-section">
            <div class="info-box">
                <div class="info-box-inner">
                    <div class="info-label">Bill To</div>
                    <div class="info-value">
                        <strong>{{ $order->shipping_name }}</strong>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}<br>
                        Phone: {{ $order->shipping_phone }}<br>
                        Email: {{ $order->shipping_email }}
                    </div>
                </div>
            </div>
            <div class="info-box">
                <div class="info-box-inner">
                    <div class="info-label">Ship To</div>
                    <div class="info-value">
                        <strong>{{ $order->shipping_name }}</strong>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}<br>
                        Phone: {{ $order->shipping_phone }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 40%;">Item Description</th>
                    <th style="width: 12%;">HSN/SAC</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Unit Price</th>
                    <th style="width: 18%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $item->product_name }}</div>
                        @if($item->variant_info)
                        <div class="item-sku">{{ $item->variant_info }}</div>
                        @endif
                    </td>
                    <td>{{ $item->hsn_code ?? '6201' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="tax-info">
                <div class="tax-box">
                    <div class="tax-box-title">Tax Breakdown (GST @ 18%)</div>
                    <div class="tax-row">
                        <span>Taxable Amount:</span>
                        <span>₹{{ number_format($taxBreakdown['taxable_amount'], 2) }}</span>
                    </div>
                    @if($taxBreakdown['type'] === 'intra_state')
                    <div class="tax-row">
                        <span>CGST @ {{ $taxBreakdown['cgst_rate'] }}%:</span>
                        <span>₹{{ number_format($taxBreakdown['cgst_amount'], 2) }}</span>
                    </div>
                    <div class="tax-row">
                        <span>SGST @ {{ $taxBreakdown['sgst_rate'] }}%:</span>
                        <span>₹{{ number_format($taxBreakdown['sgst_amount'], 2) }}</span>
                    </div>
                    @else
                    <div class="tax-row">
                        <span>IGST @ {{ $taxBreakdown['igst_rate'] }}%:</span>
                        <span>₹{{ number_format($taxBreakdown['igst_amount'], 2) }}</span>
                    </div>
                    @endif
                    <div class="tax-row" style="border-top: 1px solid #ddd; padding-top: 5px; margin-top: 5px; font-weight: bold;">
                        <span>Total Tax:</span>
                        <span>₹{{ number_format($taxBreakdown['total_tax'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="totals">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal:</td>
                        <td>₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if(isset($order->discount) && $order->discount > 0)
                    <tr>
                        <td>Discount{{ $order->coupon_code ? ' ('.$order->coupon_code.')' : '' }}:</td>
                        <td>-₹{{ number_format($order->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Tax (GST 18%):</td>
                        <td>₹{{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td>{{ $order->shipping > 0 ? '₹'.number_format($order->shipping, 2) : 'FREE' }}</td>
                    </tr>
                    <tr class="total">
                        <td>Grand Total:</td>
                        <td>₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <div class="terms">
                        <div class="terms-title">Terms & Conditions:</div>
                        1. Goods once sold will not be taken back or exchanged.<br>
                        2. All disputes are subject to {{ $company['city'] }} jurisdiction only.<br>
                        3. E. & O.E. (Errors and Omissions Excepted)<br>
                        4. This is a computer-generated invoice and does not require a signature.
                    </div>
                </div>
                <div class="footer-right">
                    <div class="signature-box">
                        <div class="signature-line">Authorized Signatory</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
