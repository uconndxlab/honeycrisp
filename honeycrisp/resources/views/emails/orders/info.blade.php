<x-mail::message>
# Your Order Has Been Updated

Hi {{ $order->customer->name }},

The {{ $facility }} has sent you a copy of your order.

### Order Details:
- **Order ID:** {{ $order->id }}
- **Order Title:** {{ $order->title }}
- **Order Total:** ${{ number_format($order->total, 2) }}
- **Order Date:** {{ $order->date }}

You can view the full details of your order by clicking the button below.

<x-mail::button :url="url('/orders/' . $order->id)">
View Your Order
</x-mail::button>

If you have any questions, please feel free to contact us.

Thanks,  
The {{ $facility }} Team
</x-mail::message>
