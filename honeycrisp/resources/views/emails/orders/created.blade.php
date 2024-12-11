            <x-mail::message>
# A New {{ $facility }} Order Has Been Created #

Hi {{ $customer->name }},

We're excited to let you know that your new order has been successfully created! Here's a quick summary:

## Order Details
- Order ID: {{ $orderId }}
- Title: {{ $orderTitle }}
- Date: {{ $orderDate }}
- Total Amount: ${{ number_format($orderTotal, 2) }}

<x-mail::button :url="route('orders.show', $orderId)">
View Order
</x-mail::button>

## Assigned Users
The following users are associated with this order:
@foreach ($users as $user)

- {{ $user->name }} ({{ $user->email }})

@endforeach

If you have any questions or need assistance, feel free to reach out to us.

Thanks for choosing our services!


</x-mail::message>
