<x-mail::message>
# New HoneyCrisp Invoice Created for a UCH Customer

A new invoice has been created with the following details:

## Order Details
- **Order ID:** {{ $order->id }}
- **Title:** {{ $order->title }}
- **Customer:** {{ $order->customer->name ?? 'N/A' }}
- **Date:** {{ $order->date }}
- **Total Amount:** ${{ number_format($order->total, 2) }}
- **Payment Account:** {{ $order->paymentAccount->account_number ?? 'N/A' }}

Please log in to the finance system to process this invoice.

<x-mail::button :url="url('/orders/' . $order->id)">
    View Order
</x-mail::button>

Thank you,  
{{ config('app.name') }} Team
</x-mail::message>
