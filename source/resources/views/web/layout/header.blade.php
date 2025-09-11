<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 fixed-top shadow-sm">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('webhome') }}">
            <img src="{{url($logo->icon)}}" alt=" Logo" height="35" class="me-2">  {{--src="{{ url('webstyle/image/logo.PNG') }}"--}}
            {{-- <span class="fw-bold fs-5">{{$logo->name}} User</span>  --}}
          
        </a>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('webhome') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products') }}">Our Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('webabout') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('contact') }}">Contact Us</a>
                </li>
            </ul>

            <form class="d-flex mx-lg-auto my-2 my-lg-0 w-lg-50">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> {{-- Font Awesome Search Icon --}}
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-lg-auto d-flex flex-row align-items-center">
                {{-- User Profile/Login --}}
                @if(Session::has('bamaCust'))
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ url('webstyle/image/panga.PNG') }}" alt="Profile" height="30" class="rounded-circle me-1">
                        <span class="d-none d-lg-inline-block">Welcome {{ Session::get('bamaCust')->user_name ?? '' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">My Profile</a></li>
                        <li><a class="dropdown-item" href="#">My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('userlogout') }}">Logout</a></li>
                    </ul>
                </li>
                @else
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('userLogin') }}">
                        <i class="fas fa-user-circle fs-5"></i> {{-- Font Awesome User Icon --}}
                        <span class="d-none d-lg-inline-block ms-1">Sign In</span>
                    </a>
                </li>
                @endif

                {{-- Cart --}}
                <li class="nav-item">
                    <a class="nav-link position-relative" href="#" onclick="openNav()">
                        <i class="fas fa-shopping-cart fs-5"></i> {{-- Font Awesome Cart Icon --}}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            10
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="mySidepanel" class="sidepanel shadow-lg">
    <h5 class="sidenav_h5">
        My Cart 
        <span class="sidenav_span" onclick="closeNav()">
            
            <i class="fas fa-times" style="font-size: 15px; color: black;"></i>
        </span>
    </h5>

    <div class="header_overflow">
        {{-- Check if the cart is not empty --}}
        @if (!empty($items) && count($items) > 0)
            @php
                $subtotal = 0;
                $taxTotal = 0; // Assuming a tax rate for calculation
                $shippingTotal = 0;
            @endphp
            
            {{-- Loop through each item in the cart --}}
            @foreach ($items as $item)
                @php
                    // Calculate totals
                    $subtotal += $item->line_total;
                    // Add your tax logic here, e.g., 5%
                    $taxTotal += $item->line_total * 0.05; 
                @endphp

                <hr style="margin-top: -8px; margin-bottom: -8px;">

                <div class="row" style="padding: 15px 10px 5px 10px;">
                    <div class="col-sm-1">
                        {{-- Assuming 'varient_image' is a column in your database --}}
                        <img src="{{ asset( $item->varient_image) }}" height="40">                      
                    </div>

                    <div class="col-sm-4">
                        {{-- You'll need to join with product table to get the name --}}
                        <p style="font-size: 14px; margin-left: 12px;">{{ $item->product_name ?? 'Product Name' }}</p>
                        <p class="header_para_34">Pack Size: N/A | Quantity: {{ $item->qty }}</p>
                    </div>

                    <div class="col-sm-3">
                        <div class="btn-group btn-group-sm but_div3" role="group" aria-label="...">
                            <button class="but_minus3"><i class="fas fa-minus"></i></button>
                            <button class="but_one3"><input type="number" style="border: 0; width: 29px; outline: none;" value="{{ $item->qty }}" name="qty"></button>
                            <button class="but_plus3"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <p class="cart_para">${{ number_format($item->line_total, 2) }}</p>
                    </div>

                    <div class="col-sm-2">
                        <i class="fas fa-times-circle cart_font text-danger" onclick="remove_cart_item()"></i>
                    </div>
                </div>
            @endforeach

        @else
            {{-- Display message if the cart is empty --}}
            <p style="text-align: center; padding: 20px;">Your cart is empty.</p>
        @endif
    </div>

    @if (!empty($items) && count($items) > 0)
        <div class="card header_card12">
            <div class="card-body">
                @php
                    // Assuming a fixed shipping fee for this example
                    $shippingTotal = 5.00; 
                    $grandTotal = $subtotal + $taxTotal + $shippingTotal;
                @endphp

                <p class="header_para454">
                    Sub Total <span style="float: right;">${{ number_format($subtotal, 2) }}</span>
                </p>

                <p class="header_para454">
                    Tax <span style="float: right;">${{ number_format($taxTotal, 2) }}</span>
                </p>

                <p class="header_span99">
                    Net Payable <span style="float: right;">${{ number_format($grandTotal, 2) }}</span>
                </p>

                <a href="{{ route('cart.checkout') }}" style="width: 45%; display: inline-block;">
                    <button class="btn header_viewbag">View Bag</button>
                </a>
                <button class="btn header_pay_to">Proceed To pay</button>

            </div>
        </div>
    @endif
</div>

<script>
    function openNav() {
        document.getElementById("mySidepanel").style.width = "435px";
    }



    function closeNav() {
        document.getElementById("mySidepanel").style.width = "0";
    }

    function remove_cart_item(){
        // remove cart item
    //  const productId = element.getAttribute('data-product-id');

    //  remove_from_db(productId);
    }

</script>

<script>
$(document).ready(function() {
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault(); // Stop the default page reload

        let varientId = $(this).data('varient-id');
        let qty = $(this).data('qty');
        let url = "{{ route('cart.add') }}";
        let csrfToken = "{{ csrf_token() }}";

        // Show a loading indicator (optional)
        $(this).text('Adding...').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: csrfToken,
                varient_id: varientId,
                qty: qty
            },
            success: function(response) {
        
                // You can update a cart counter here
                $('#cart-count').text(response.cart_count);
            
                // Restore the button state
                $('.add-to-cart-btn').text('Add to Cart').prop('disabled', false);
            },
            error: function(xhr) {
                // Handle any errors
                console.error(xhr.responseText);
                $('.add-to-cart-btn').text('Add to Cart').prop('disabled', false);
                alert('An error occurred!');
            }
        });
    });
});
</script>

