<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Invoice') }} #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .header td {
            vertical-align: top;
        }
        .title {
            font-size: 24px;
            font-bold: true;
            color: #e11d48;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .rtl .items-table th, .rtl .items-table td {
            text-align: right;
        }
        .summary {
            width: 100%;
            margin-top: 20px;
        }
        .summary td {
            padding: 5px 0;
        }
        .total-row {
            font-size: 16px;
            font-weight: bold;
            color: #e11d48;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="title">
                    {{ __('INVOICE') }}
                </td>
                <td style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}">
                    <strong>#{{ $order->order_code }}</strong><br>
                    {{ __('Date:') }} {{ $order->created_at->format('Y-m-d') }}<br>
                    {{ __('Status:') }} {{ __($order->status) }}
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td>
                    <strong>{{ __('Customer Details') }}</strong><br>
                    {{ $order->name ?? $order->customer?->name ?? __('Guest') }}<br>
                    {{ $order->email ?? $order->customer?->email ?? '' }}<br>
                    {{ $order->phone ?? $order->customer?->phone ?? '' }}<br>
                    {{ $order->address ?? '' }}
                </td>
                <td style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}">
                    <strong>{{ __('Payment Info') }}</strong><br>
                    {{ __('Method:') }} {{ __($order->payment_method ?? 'Cash') }}<br>
                    {{ __('Status:') }} {{ __($order->payment_status ?? 'Pending') }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th style="bottom-align: center">{{ __('Qty') }}</th>
                    <th style="text-align: right">{{ __('Unit Price') }}</th>
                    <th style="text-align: right">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            {{ app()->getLocale() === 'ar' ? ($item->product->name_ar ?? $item->product->name) : ($item->product->name_en ?? $item->product->name) }}
                            @if($item->color) <small>({{ $item->color }})</small> @endif
                            @if($item->size) <small>({{ $item->size }})</small> @endif
                        </td>
                        <td style="text-align: center">{{ $item->quantity }}</td>
                        <td style="text-align: right">{{ number_format($item->unit_price, 2) }}</td>
                        <td style="text-align: right">{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary" style="margin-left: {{ app()->getLocale() === 'ar' ? '0' : 'auto' }}; margin-right: {{ app()->getLocale() === 'ar' ? 'auto' : '0' }}; width: 250px;">
            <tr>
                <td>{{ __('Subtotal') }}</td>
                <td style="text-align: right">{{ number_format($order->items->sum('total'), 2) }}</td>
            </tr>
            <tr>
                <td>{{ __('Shipping') }}</td>
                <td style="text-align: right">{{ number_format($order->shipping_cost ?? $order->shipping ?? 0, 2) }}</td>
            </tr>
            @if($order->discount > 0)
            <tr>
                <td>{{ __('Discount') }}</td>
                <td style="text-align: right">-{{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>{{ __('Total') }}</td>
                <td style="text-align: right">{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            {{ config('app.name') }} - {{ __('Thank you for your business!') }}
        </div>
    </div>
</body>
</html>
