<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 fixed-top shadow-sm">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('webhome') }}">
            <img src="{{url($logo->icon)}}" alt=" Logo" height="35" class="me-2"> {{--src="{{
            url('webstyle/image/logo.PNG') }}"--}}
            {{-- <span class="fw-bold fs-5">{{$logo->name}} User</span> --}}

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

            <form class="d-flex mx-lg-auto my-2 my-lg-0 w-lg-50" action="{{ route('search.product') }}" method="GET">
                <div class="input-group">
                    <input id="searchInput" name="keyword" class="form-control" type="search"
                        placeholder="Search products..." aria-label="Search" required>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="voiceSearchBtn">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-lg-auto d-flex flex-row align-items-center">
                {{-- User Profile/Login --}}
                @if(Session::has('bamaCust'))
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ url('webstyle/image/panga.PNG') }}" alt="Profile" height="30"
                                class="rounded-circle me-1">
                            <span class="d-none d-lg-inline-block">Welcome
                                {{ Session::get('bamaCust')->user_name ?? '' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="#">My Orders</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
                @php
                    $totalItems = 0;
                    if (session('cart')) {
                        foreach (session('cart') as $item) {
                            $totalItems += $item['quantity'];
                        }
                    }
                @endphp
                <li class="nav-item">
                    <a class="nav-link position-relative" href="#" onclick="openNav()">
                        <i class="fas fa-shopping-cart fs-5"></i> {{-- Font Awesome Cart Icon --}}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{$totalItems}}
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
        @if(session('cart') && count(session('cart')) > 0)

            {{-- Loop through each item in the cart --}}
            @foreach (session('cart') as $item)
                <hr style="margin-top: -8px; margin-bottom: -8px;">

                <div class="row" style="padding: 15px 10px 5px 10px;">
                    {{-- Product image --}}
                    <div class="col-sm-1">
                        <img src="{{ asset($item['image']) }}" height="40" alt="{{ $item['name'] }}">
                    </div>

                    {{-- Product info --}}
                    <div class="col-sm-6">
                        <p style="font-size: 14px; margin-left: 12px;">
                            {{ $item['name'] ?? 'Product Name' }}
                            <small class="text-muted">(Store: {{ $item['store_id'] }})</small>
                        </p>
                        <p class="header_para_34">Rs: {{ $item['price'] * $item['quantity'] }}</p>
                        <p class="header_para_34">Quantity: {{ $item['quantity'] }}</p>
                    </div>

                    {{-- Quantity controls --}}
                    <div class="col-sm-3">
                        <div class="d-flex justify-content-center align-items-center rounded p-1">
                            {{-- Decrease --}}
                            <form method="POST" action="{{ route('cart.update', [$item['product_id'], $item['store_id']]) }}"
                                class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                            </form>

                            {{-- Quantity --}}
                            <span class="px-3">{{ $item['quantity'] }}</span>

                            {{-- Increase --}}
                            <form method="POST" action="{{ route('cart.update', [$item['product_id'], $item['store_id']]) }}"
                                class="m-0">
                                @csrf
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                            </form>
                        </div>
                    </div>

                    {{-- Remove --}}
                    <div class="col-sm-2 text-end">
                        <form method="POST" action="{{ route('cart.remove', [$item['product_id'], $item['store_id']]) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-danger">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

        @else
            {{-- Empty cart message --}}
            <p class="text-center py-3 text-uppercase"><b>No items in the cart</b></p>
            <p class="text-center text-muted small mb-2">
                Please add some items to the cart, they will appear here.
            </p>
            <div class="text-center">
                <a href="{{ route('products') }}" class="btn btn-sm btn-outline-primary">Shop Now</a>
            </div>
        @endif
    </div>


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


    @if(session('cart') && count(session('cart')) > 0)
        <div class="card header_card12">
            <div class="card-body">

                <p class="header_span99">
                    Total Items : <span style="float: right;">{{ $totalItems }}</span>
                </p>

                <p class="header_span99">
                    Total Payable : <span style="float: right;">Rs {{ number_format($grandTotal, 2) }}</span>
                </p>

                <a href="{{ route('cart.checkout') }}"><button class="btn header_pay_to"> Proceed To Pay </button></a>


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


</script>


<script>
    // Voice Search (Web Speech API)
    const voiceBtn = document.getElementById('voiceSearchBtn');
    const searchInput = document.getElementById('searchInput');

    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = "en-US";

        voiceBtn.addEventListener('click', () => {
            recognition.start();
        });

        recognition.onresult = (event) => {
            searchInput.value = event.results[0][0].transcript;
        };

        recognition.onerror = (event) => {
            console.error("Voice search error:", event.error);
        };
    } else {
        voiceBtn.disabled = true; // If browser doesn’t support speech API
        voiceBtn.title = "Voice search not supported in this browser";
    }
</script>