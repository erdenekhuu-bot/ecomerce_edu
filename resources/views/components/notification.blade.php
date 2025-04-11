@props(['notifications', 'is_linkable' => false])


@forelse($notifications as $notification)
    <li class="list-group-item d-flex justify-content-between align-items- py-3">
        <div class="media text-inherit">
            <div class="media-body">
                <p class="mb-1 text-truncate-2">
                    @if ($notification->type == 'App\Notifications\DB\OrderNotification')
                        {{ translate('Order code: ') }}
                        <a href="{{ route('orders.show', $notification->data['order_id']) }}">
                            {{ $notification->data['order_code'] }}
                        </a>
                        {{ translate(' has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}

                    @elseif ($notification->type == 'App\Notifications\DB\SellerApprovalNotification')

                        {{ translate('Your verification request has been ' . $notification->data['status']) }}

                    @elseif ($notification->type == 'App\Notifications\DB\SellerRegistrationNotification')

                        <a href="{{ route('admin.all_sellers') }}">
                           {{  $notification->data['seller_name'] }}
                        </a>
                        {{ translate(' has been applied for being a seller') }}

                    
                    @elseif ($notification->type == 'App\Notifications\DB\SellerVerificationNotification')

                        <a href="{{ $notification->data['route'] }}">
                           {{  $notification->data['shop_name'] }}
                        </a>
                        {{ translate(' has been applied for verify') }}

                    @endif
                </p>
                <small class="text-muted">
                    {{ date('F j Y, g:i a', strtotime($notification->created_at)) }}
                </small>
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item">
        <div class="py-4 text-center fs-16">
            {{ translate('No notification found') }}
        </div>
    </li>
@endforelse
