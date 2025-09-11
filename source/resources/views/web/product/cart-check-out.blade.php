@extends('web.layout.app')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4">Checkout</h2>
    <div class="row g-4">

        <!-- Left Section -->
        <div class="col-lg-8">
            <!-- Step 1: Login -->
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0">1. Login</h5>
                    <a href="{{ route('profile') }}" class="btn btn-link btn-sm">Change</a>
                </div>
                <div class="card-body">
                    @if(Session::has('bamaCust'))  
               
                        <p class="mb-1"><strong>{{ $user->user_name }}</strong></p> 
                        <p class="text-muted mb-0">{{ $user->user_email }} | {{ $user->user_phone }}</p>
                    @else
                        <p>Please <a href="">login</a> to continue checkout.</p> {{--{{ route('login') }}--}}
                    @endif
                </div>
            </div>

            <!-- Step 2: Delivery Address -->
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0">2. Delivery Address</h5>
                    <a href="{{ route('address.index') }}" class="btn btn-link btn-sm">Change</a>
                </div>
                <div class="card-body">
                    @if($addresses=null)
                        <p class="fw-bold mb-1">{{ $address->receiver_name }} ({{ $address->receiver_phone }})</p>
                        <p class="mb-1">
                            {{ $address->house_no }}, {{ $address->society }}, {{ $address->landmark }} <br>
                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                        </p>
                        <span class="badge bg-success">Selected</span>
                    @else
                        <p class="text-muted">No address selected. Please <a href="{{ route('address.index') }}">add/select an address</a>.</p>
                    @endif
                </div>
            </div>

            <!-- Step 3: Order Summary -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">3. Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach ($items as $item)
                        
                    <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                        <img src="{{ asset($item->varient_image) }}" class="img-fluid rounded me-3" style="width: 100px; height: 100px; object-fit: cover;" alt="{{ $item->product_name }}">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $item->product_name }}</h6>
                            <p class="text-muted small mb-1">{{ $item->description }}</p>
                            <p class="fw-bold mb-0">₹ {{ number_format($item->line_total, 2)}}</p>
                        </div>
                    </div>

                    @endforeach
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Price (1 item)</span>
                            <strong>₹{{-- number_format($product->base_price, 2) --}}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Delivery Charges</span>
                            <strong class="text-success">Free</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong>₹{{-- number_format($product->base_price, 2) --}}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Section: Price Details -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Price Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Price (1 item)</span>
                            <strong>₹{{-- number_format($product->base_price, 2) --}}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Delivery</span>
                            <strong class="text-success">Free</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Payable</span>
                            <strong>₹{{-- number_format($product->base_price, 2) --}}</strong>
                        </li>
                    </ul>
                    <p class="text-muted small">
                        Order confirmation will be sent to <strong>{{-- $user->user_email --}}</strong>
                    </p>
                    <form action="{{-- route('order.place') --}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100 fw-bold">Continue</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


