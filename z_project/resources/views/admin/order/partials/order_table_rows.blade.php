     <tbody>
                                                @foreach ($orders as $key => $order)
                                                
                                                 @php
                                                    // EXACT MATCH WITH DB click_url
                                                    $orderUrl = '/orderitem/details/' . $order->id;
                                                
                                                    // Key format must match users_notification keyBy()
                                                    $notifyKey = $order->user_id . '_' . $orderUrl;
                                                
                                                    // Get notification if exists
                                                    $notification = $notifications[$notifyKey] ?? null;
                                                @endphp
                                                                                                <tr>
                                                    <td class="text-center">
                                                        @php
                                                            $showInvoiceLink = true;
                                                            foreach ($order->deliveryStatuses as $deliveryStatus) {
                                                                if ($deliveryStatus['status'] === 'pending' || $deliveryStatus['status'] === 'cancelled' || $deliveryStatus['status'] === 'hold'  ) {
                                                                    $showInvoiceLink = false;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp

                                                        @if ($showInvoiceLink)
                                                            <a class="text-dark font-11" href="{{ route('generateInvoiceAndDeliveryCharges.list',['id' => $order->id]) }}" onclick="window.open(this.href,'_blank','width=800,height=600'); return false;">{{ $order->invoice_id }}</a>
                                                        @else
                                                            No Invoice
                                                        @endif
                                                    </td>

                                                     <td class="text-center">
  @if (!empty($order->deliveryStatuses))
    @php
        $status = $order->deliveryStatuses[0]['status'] ?? null;
    @endphp

    @if ($order->orderItemsExist && ($status === 'pending' || $status === 'hold'))
        <a class="text-dark font-11"
           href="{{ $orderUrl }}?notification_id={{ $notification->id ?? '' }}">
            {{ $order->order_id }}
        </a>
    @else
        {{ $order->order_id }}
    @endif
@endif
</td>
                                                   <td>
                                                    @php
                                                     $outletName = $users->firstWhere('id', $order->outlet_id);
                                                 @endphp
    <a href="{{ route('order.detailsid', ['id' => $order->outlet_id]) }}"
       class="text-dark"
       style="display:block; max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
       title="{{ $outletName ? $outletName->name : '' }}">
        {{ $outletName ? $outletName->name : 'Unknown Customer' }}
    </a>
</td>
                                                   <td>
    <a href="{{ route('order.detailsid', ['id' => $order->outlet_id]) }}"
       class="text-dark"
       style="display:block; max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
       title="{{ $outletName ? $outletName->outlet_name : '' }}">
        {{ $outletName ? $outletName->outlet_name : 'Unknown Outlet' }}
    </a>
</td>

                                                    <td class="text-center">{{ $order->delivery_date }}</td>
                                                   <td class="text-center">
@foreach ($order->deliveryStatuses as $deliveryStatus)

  @php
    $class = 'status-progress'; // default

    if ($deliveryStatus['status'] == 'pending') {
        $class = 'status-pending';
    } elseif ($deliveryStatus['status'] == 'in_progress') {
        $class = 'status-progress';
    } elseif ($deliveryStatus['status'] == 'delivered') {
        $class = 'status-delivered';
    }
@endphp

<span class="{{ $class }}">
    {{ $deliveryStatus['status'] == 'ready_for_dispatch' ? 'Dispatched' : $deliveryStatus['status'] }}
</span>

@endforeach
</td>
                        
                                                    <td class="text-center"> ₹ {{ $order->total_discount_value }}</td>
                                                    <td class="text-center">{{ $order->payment_method }}</td>
                                                  
                                                    
                                                        <td class="text-center">
    <div class="payment-status-text">
        {{ $order->payment_status ?? '-' }}
    </div>

    @if ($order->payment_status !== 'paid')
        <div class="payment-action">
            <a href="{{ route('order.edit', ['id' => $order->id, 'from' => 'orders']) }}" 
               class="btn-update">
                Update
            </a>
        </div>
    @endif
</td>

                                                    <td class="text-center">
                                                   <div class="date-box">
                                                    <div class="date">
                                                     {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}
                                                   </div>
                                                  <div class="time">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                                  </div>
                                                     </div>
                                                    </td>
                                                   <td class="text-center">

                                                        @php
                                                            $paymentTerm = \App\Models\OutletPaymentTerm::where('user_id', $order->outlet_id)
                                                                            ->where('is_active', 1)
                                                                            ->first();
                                                    
                                                            $hasNewPaymentTerm = $paymentTerm ? true : false;
                                                        @endphp
                                                    
                                                        @if($order->payment_status !== 'paid')
                                                    
                                                            @php
                                                                $today = \Carbon\Carbon::now();
                                                    
                                                                /* ------------------------------------
                                                                    CASE 1: NEW CUSTOM PAYMENT TERM FOUND
                                                                -------------------------------------*/
                                                                if ($hasNewPaymentTerm) {
                                                    
                                                                    $parts = [];
                                                    
                                                                    if (!empty($paymentTerm->from_range)) $parts[] = (int) $paymentTerm->from_range;
                                                                    if (!empty($paymentTerm->to_range))   $parts[] = (int) $paymentTerm->to_range;
                                                                    if (!empty($paymentTerm->days))       $parts[] = (int) $paymentTerm->days;
                                                    
                                                                    $dueDay = array_sum($parts); 
                                                    
                                                                    // Build due date by day of month
                                                                    $deliveryDate = \Carbon\Carbon::parse($order->delivery_date);
                                                                    $dueDate = $deliveryDate->copy()->addDays($dueDay);
                                                    
                                                                    $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);
                                                    
                                                                } else {
                                                    
                                                                    $userDueLimit = $order->user->due_days_limit ?? 0;
                                                                    $deliveryDate = \Carbon\Carbon::parse($order->delivery_date);
                                                    
                                                                    $dueDate = $deliveryDate->copy()->addDays($userDueLimit);
                                                    
                                                                    $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);
                                                                }
                                                    
                                                                /* ------------------------------------
                                                                    Choose text + color
                                                                -------------------------------------*/
                                                                if ($daysDifference < 0) {
                                                                    $daysText = 'Overdue by ' . abs($daysDifference) . ' days';
                                                                    $color = 'red';
                                                    
                                                                } elseif ($daysDifference > 0) {
                                                                    $daysText = 'Due in ' . $daysDifference . ' days';
                                                                    $color = $daysDifference <= 3 ? 'red' : 'orange';
                                                    
                                                                } else {
                                                                    $daysText = 'Today';
                                                                    $color = 'green';
                                                                }
                                                            @endphp
                                                    
                                                          
                                                            <div class="due-box">
    
    <div class="due-text 
        {{ $color == 'red' ? 'due-red' : ($color == 'orange' ? 'due-orange' : 'due-green') }}">
        
        @if ($daysDifference < 0)
            Overdue by {{ abs($daysDifference) }} days
        @elseif ($daysDifference == 0)
            Due Today
        @else
            Due in {{ $daysDifference }} days
        @endif
    </div>

    <div class="due-date">
        ({{ $dueDate->format('Y-m-d') }})
    </div>

</div>
                                                    
                                                        @else
                                                    
                                                            <span class="status-paid">Already Paid</span>
                                                    
                                                        @endif
                                                    
                                                    </td>

                                             
                                                
                                                 <td class="text-center">
                                               

                                                  @if(hasPermission('order.cancel_invoice.view'))
                                                   @if (!collect($order->deliveryStatuses)->contains('status', 'delivered'))
                                                       <button 
                                                    class="btn-cancel-icon"
                                                    onclick="cancelOrder('{{ $order->id }}')"
                                                    title="Cancel Order">
                                                    ✖
                                                   </button>
                                                    @endif
                                                    @else
                                                  <p class="">Permission Required</p>
                                                    @endif
                                                     </td>


                                                


                                                </tr>
                                                @endforeach
                                            </tbody>