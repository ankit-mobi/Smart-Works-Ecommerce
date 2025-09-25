@extends('web.layout.app')

@section('content')
    <div class="container my-5">
        <h4 class="fw-bold mb-4">Order Summary</h4>
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
                        <a href="{{ route('profile') }}" class="btn btn-link btn-sm">Change</a>
                    </div>
                    <div class="card-body">
                        @if($addresses->isNotEmpty())
                            @foreach ($addresses as $address)
                                @if ($address->select_status == 1)
                                    <p class="fw-bold mb-1">{{ $address->receiver_name }}</p>
                                    <p class="fw-bold mb-1">{{ $address->receiver_phone }}</p>
                                    <p class="mb-1">
                                        {{ $address->house_no }}, {{ $address->society }}, {{ $address->landmark }} <br>
                                        {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                                    </p>
                                    <span class="badge bg-success">Selected</span>
                                @endif
                            @endforeach

                        @else
                            <p class="text-muted">No address selected. Please <a href="{{ route('profile') }}">add/select
                                    an address</a>.</p>
                        @endif
                    </div>
                </div>

                <!-- Step 3: Order Summary -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">3. Basket items </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($cart as $item)

                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                <img src="{{ asset($item['image']) }}" class="img-fluid rounded me-3"
                                    style="width: 100px; height: 100px; object-fit: cover;" alt="{{ $item['name'] }}">

                                {{-- Product info --}}
                                <div class="col-sm-12">
                                    <p style="font-size: 14px; margin-left: 12px;">{{ $item['name'] ?? 'Product Name' }}
                                    <p class="text-muted small mb-1">{{ Str::limit($item['description'], 60)}}</p>

                                    <small class="text-muted">(Store: {{ $item['store_id'] }})</small>
                                    </p>
                                    <p class="header_para_34">Rs: {{ $item['price'] * $item['quantity'] }}</p>
                                    <p class="header_para_34">Quantity: {{ $item['quantity'] }}</p>
                                </div>

                            </div>

                        @endforeach
                    </div>
                </div>


                <!-- Step 4: Choose Delivery Slot -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-light d-flex align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-calendar-event me-2"></i> 4. Choose a Delivery Slot
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row g-4 align-items-start">

                            <!-- Date Picker -->
                            <div class="col-md-6">
                                <label for="delivery_date" class="form-label fw-semibold">
                                    Select Delivery Date <span class="text-muted">(Only Next 10 days)</span>
                                </label>
                                <input type="date" name="delivery_date" id="delivery_date" class="form-control shadow-sm"
                                    min="{{ now()->toDateString() }}" max="{{ now()->addDays(9)->toDateString()}}" required>
                                @error('delivery_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Timeslot container -->
                            <div class="col-md-6" id="timeslot-container">
                                <div class="alert alert-secondary text-center py-3 mb-0">
                                    <i class="bi bi-clock me-1"></i>
                                    Please select a delivery date to see available time slots.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            
            <!-- Right Section: Price Details -->
            @php
                $totalItems = 0;
                $grandTotal = 0;

                if (session('cart')) {
                    foreach (session('cart') as $item) {
                        $totalItems += $item['quantity'];
                        $grandTotal += $item['price'] * $item['quantity'];
                    }
                }
            @endphp


            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Price Details</h5>
                    </div>

                    @if(session('cart') && count(session('cart')) > 0)
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Items : </span>
                                    <strong>{{ $totalItems}}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Delivery</span>

                                    @if($delivery_info)
                                        @if ($delivery_info->min_cart_value > $grandTotal)
                                            <strong class="text-success">{{ $delivery_info->del_charge }}</strong>
                                        @else
                                            <strong class="text-success">Free</strong>
                                        @endif
                                    @else
                                        <strong class="text-success">Delivery charges not set</strong>
                                    @endif
                                </li>


                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Payable</span>
                                    <strong>₹ {{number_format($grandTotal + $delivery_info->del_charge, 2)}}</strong>
                                </li>
                            </ul>
                            <p class="text-muted small">
                                Order confirmation will be sent to mail<strong>{{-- $user->user_email --}}</strong>
                            </p>
                             @if ($minmax)
                                 @if ($minmax->min_value > $grandTotal)
                                      <div class="alert alert-danger py-3 mb-0">Please order more than Rs {{ $minmax->min_value}}</div>
                                 @elseif($minmax->max_value < $grandTotal)
                                      <div class="alert alert-danger py-3 mb-0">Please order less than Rs {{ $minmax->max_value}} </div>
                                 @else
                                      <form action="{{ route('make.order')}}" method="POST">   {{-- route('order.place') --}}
                                       @csrf
                                      
                                       <input type="number" name="user_id" value="{{ $user->user_id ?? ''}}" hidden required>
                                       @php
                                       $selectedAddress = $addresses->firstWhere('select_status',1);
                                       @endphp
                                       @if($selectedAddress)
                                       <input type="number" name="address_id" value="{{ $selectedAddress->address_id}}" hidden required>
                                       

                                       @endif

                                       @php
                                       $orderItems = [];

                                       if(session('cart')){
                                        foreach(session('cart') as $item){
                                            $orderItems[] = [
                                                'qty'        => $item['quantity'],
                                              'product_id'   => $item['product_id'], // Assuming you have a product_id
                                              'varient_id'   => $item['varient_id'] ?? null, // Assuming you have a variant_id
                                              'store_id'     => $item['store_id'],
                                             ];
                                             }
                                            }

                                        $orderArrayJson = json_encode($orderItems);
            // Also submit the primary store_id if all items are from one store
            $primaryStoreId = $orderItems[0]['store_id'] ?? null;
                                       @endphp

                                       <input type="hidden" name="store_id" value="{{ $primaryStoreId }}" required>
        <input type="hidden" name="order_array" value="{{ $orderArrayJson }}">

        <input type="hidden" name="delivery_date" id="hidden_delivery_date" required>
<input type="hidden" name="time_slot" id="hidden_time_slot" required>
       
        
        <button type="submit" class="btn btn-warning w-100 fw-bold">Continue</button>
    </form>
@endif
                             @endif
                            
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('delivery_date').addEventListener('change', function () {
            const selected_date = this.value;
            const timeslotContainer = document.getElementById('timeslot-container');

            if (selected_date) {
                timeslotContainer.innerHTML = '<p class="text-info"> Loading time slots...</p>';

                fetch('{{ route('getSlots') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ selected_date: selected_date })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === '1') {
                            let timeslotHtml = `
                <label class="form-label fw-bold">Select a Time Slot</label>
                <div class="list-group shadow-sm rounded">
            `;

                            data.data.forEach(slot => {
                                timeslotHtml += `
                    <label class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <input type="radio" name="delivery_timeslot" value="${slot}" required class="form-check-input me-2">
                            <span class="fw-medium">${slot}</span>
                        </div>
                        <span class="badge bg-success rounded-pill">Available</span>
                    </label>
                `;
                            });

                            timeslotHtml += `</div>`;
                            timeslotContainer.innerHTML = timeslotHtml;
                        } else {
                            timeslotContainer.innerHTML = `<p class="text-danger fw-bold">${data.message}</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        timeslotContainer.innerHTML = `<p class="text-danger fw-bold">⚠ Failed to load time slots.</p>`;
                    });



            }
        });
    </script>

    <script>
    const form = document.querySelector('.col-lg-4 form'); // Select your final submission form
    
    // 1. Update the hidden delivery date field
    document.getElementById('delivery_date').addEventListener('change', function () {
        const selected_date = this.value;
        document.getElementById('hidden_delivery_date').value = selected_date; // <--- ADD THIS LINE
        // ... rest of your fetch logic ...
    });

    // 2. Add an event listener to update the hidden time slot field when a radio button is selected
    document.addEventListener('change', function(event) {
        if (event.target.name === 'delivery_timeslot') {
            document.getElementById('hidden_time_slot').value = event.target.value;
        }
    });

    // ... rest of your existing JS logic ...
</script>


@endsection