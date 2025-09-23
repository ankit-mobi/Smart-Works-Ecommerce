@extends('web.layout.app')

@section('content')

    {{-- alert handling --}}
    <br>
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

    @endif








    {{-- banners-main --}}
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @foreach ($banners_list['main_banner'] as $mbanner)
                <div class="carousel-item @if($loop->first) active @endif">
                    <img class="d-block w-100 carousel-img" src="{{asset($mbanner->banner_image)}}"
                        alt="{{$mbanner->banner_name}}">
                </div>
            @endforeach

        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div><br><br>


    {{-- Top Selling Products --}}
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-uppercase">Top Selling</h3>
            <a href="{{ route('our.products', ['type' => 1]) }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div class="row g-4">
            @foreach($top_selling->take(8) as $topsells)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Product Image --}}
                        <div class="text-center p-3 bg-light">
                            <a href="{{route('product_detail', ['id' => $topsells->product_id, 'store_id' => $topsells->store_id])}}"
                                style="text-decoration: none; color: inherit;">
                                <img src="{{ asset($topsells->product_image) }}" alt="{{ $topsells->product_name }}"
                                    class="img-fluid rounded" style="max-height: 220px; object-fit: contain;"></a>
                        </div>

                        {{-- Product Details --}}
                        <div class="card-body d-flex flex-column">
                            <a href="{{route('product_detail', ['id' => $topsells->product_id, 'store_id' => $topsells->store_id])}}"
                                style="text-decoration: none; color:inherit">
                                <h6 class="card-title text-truncate">{{ $topsells->product_name }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($topsells->description, 60) }}</p>
                            </a>

                            <div class="mt-auto">
                                {{-- Price & Discount --}}
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success">₹{{ number_format($topsells->price) }}</span>
                                    <span class="text-muted small">
                                        <del>₹{{ number_format($topsells->mrp) }}</del>
                                    </span>
                                </div>
                                @php
                                    $discount = $topsells->mrp - $topsells->price;
                                @endphp
                                @if($discount > 0)
                                    <p class="small text-danger mb-2">{{ $discount }} Rs Off</p>
                                @endif
                                {{-- Stock / Add Button --}}
                                <div>
                                    @if ($topsells->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $cartKey = $topsells->product_id . '-' . $topsells->store_id;
                                            $inCartQty = $cart[$cartKey]['quantity'] ?? 0;

                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST"
                                                    action="{{ route('cart.update', ['id' => $topsells->product_id, 'store_id' => $topsells->store_id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST"
                                                    action="{{ route('cart.update', ['id' => $topsells->product_id, 'store_id' => $topsells->store_id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST"
                                                action="{{ route('cart.add', ['id' => $topsells->product_id, 'store_id' => $topsells->store_id]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success w-100">
                                                    Add + <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="badge bg-danger w-100 py-2">Out of Stock</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            @endforeach
        </div>
    </div><br>


    {{-- Deals of the Day --}}
    @if(isset($deal_products) && !$deal_products->isEmpty())
        <div class="container-fluid my-5">
            <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
                <h3 class="text-uppercase">Deals of the Day</h3>
                <a href="{{ route('our.products', ['type' => 2]) }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>

            <div id="dealsCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($deal_products->chunk(4) as $chunkIndex => $dealChunk)
                        <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                            <div class="row g-4 mx-5">
                                @foreach($dealChunk as $deal)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card h-100 shadow-sm border-0">
                                            {{-- Image --}}
                                            <div class="text-center p-3 bg-light">
                                                <a href="{{route('product_detail', ['id' => $deal->product_id, 'store_id' => $deal->store_id])}}"
                                                    style="text-decoration: none; color: inherit;">
                                                    <img src="{{ asset($deal->product_image) }}" alt="{{ $deal->product_name }}"
                                                        class="img-fluid rounded" style="max-height: 220px; object-fit: contain;"> </a>
                                            </div>

                                            {{-- Product Details --}}
                                            <div class="card-body d-flex flex-column">
                                                <a href="{{route('product_detail', ['id' => $deal->product_id, 'store_id' => $deal->store_id])}}"
                                                    style="text-decoration: none; color: inherit;">
                                                    <h6 class="card-title text-truncate">{{ $deal->product_name }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($deal->description, 60) }}</p>
                                                </a>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-success">₹{{ number_format($deal->price) }}</span>
                                                    <span class="text-muted small">
                                                        <del>₹{{ number_format($deal->mrp) }}</del>
                                                    </span>
                                                </div>

                                                @php
                                                    $discount = $deal->mrp - $deal->price;
                                                @endphp
                                                @if($discount > 0)
                                                    <p class="small text-danger mb-2">{{ number_format($discount) }} Rs Off</p>
                                                @endif

                                                {{-- Countdown Timer --}}
                                                <div class="d-flex align-items-center text-warning small mb-2">
                                                    <i class="fa-solid fa-stopwatch me-1"></i>
                                                    <span class="countdown-timer"
                                                        data-endtime="{{ \Carbon\Carbon::parse($deal->valid_to)->timestamp }}">
                                                        Loading...
                                                    </span>
                                                </div>

                                                {{-- Stock / Add Button --}}
                                                <div class="mt-auto">
                                                    @if ($deal->stock > 0)

                                                        @php
                                                            $cart = session('cart', []);
                                                            $cartKey = $deal->product_id . '-' . $deal->store_id;
                                                            $inCartQty = $cart[$cartKey]['quantity'] ?? 0;
                                                        @endphp

                                                        @if ($inCartQty > 0)
                                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                                {{-- Decrease --}}
                                                                <form method="POST"
                                                                    action="{{ route('cart.update', ['id' => $deal->product_id, 'store_id' => $deal->store_id]) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="decrease">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                                </form>

                                                                {{-- Quantity --}}
                                                                <span class="px-2">{{ $inCartQty }}</span>

                                                                {{-- Increase --}}
                                                                <form method="POST"
                                                                    action="{{ route('cart.update', ['id' => $deal->product_id, 'store_id' => $deal->store_id]) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="increase">
                                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            {{-- Add button --}}
                                                            <form method="POST"
                                                                action="{{ route('cart.add', ['id' => $deal->product_id, 'store_id' => $deal->store_id]) }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-success w-100">
                                                                    Add + <i class="bi bi-plus-lg"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-danger w-100 py-2">Out of Stock</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Custom Small Carousel Controls --}}
                <div class="carousel-controls-container">
                    <button class="carousel-control-prev" type="button" data-bs-target="#dealsCarousel" data-bs-slide="prev">
                        <span class="custom-control-icon">&lt;</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#dealsCarousel" data-bs-slide="next">
                        <span class="custom-control-icon">&gt;</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    <br>

    {{-- banner secondary_banner--}}
    <div id="exploreCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
            <h3 class="explore-title text-uppercase">Explore</h3>
        </div>
        <div class="carousel-indicators">
            @foreach ($banners_list['secondary_banner'] as $index => $mbanner)
                <button type="button" data-bs-target="#exploreCarousel" data-bs-slide-to="{{ $index }}"
                    class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach ($banners_list['secondary_banner'] as $mbanner)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img class="d-block w-100 carousel-img" src="{{ asset($mbanner->banner_image) }}"
                        alt="{{ $mbanner->banner_name }}">
                </div>
            @endforeach
        </div>
    </div>
    <br><br>

    {{-- Just Arrived Products --}}
    @if(isset($new_prods) && !$new_prods->isEmpty())
        <div class="container-fluid my-5">
            <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
                <h3 class="text-uppercase">Just Arrived Products</h3>
                <a href="{{ route('our.products', ['type' => 3]) }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>

            <div id="arrivedCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($new_prods->chunk(4) as $chunkIndex => $new_prods)
                        <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                            <div class="row g-4 mx-5">
                                @foreach($new_prods as $new_prod)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card h-100 shadow-sm border-0">
                                            {{-- Image --}}
                                            <div class="text-center p-3 bg-light">
                                                <a href="{{route('product_detail', ['id' => $new_prod->product_id, 'store_id' => $new_prod->store_id])}}"
                                                    style="text-decoration: none; color: inherit;">
                                                    <img src="{{ asset($new_prod->product_image) }}" alt="{{ $new_prod->product_name }}"
                                                        class="img-fluid rounded" style="max-height: 220px; object-fit: contain;"></a>
                                            </div>

                                            {{-- Product Details --}}
                                            <div class="card-body d-flex flex-column">
                                                <a href="{{route('product_detail', ['id' => $new_prod->product_id, 'store_id' => $new_prod->store_id])}}"
                                                    style="text-decoration: none; color: inherit;">
                                                    <h6 class="card-title text-truncate">{{ $new_prod->product_name }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($new_prod->description, 60) }}</p>
                                                </a>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-success">₹{{ number_format($new_prod->price) }}</span>
                                                    <span class="text-muted small">
                                                        <del>₹{{ number_format($new_prod->mrp) }}</del>
                                                    </span>
                                                </div>

                                                @php
                                                    $discount = $new_prod->mrp - $new_prod->price;
                                                @endphp
                                                @if($discount > 0)
                                                    <p class="small text-danger mb-2">{{ number_format($discount) }} Rs Off</p>
                                                @endif



                                                {{-- Stock / Add Button --}}
                                                <div class="mt-auto">
                                                    @if ($new_prod->stock > 0)

                                                        @php
                                                            $cart = session('cart', []);
                                                            $cartKey = $new_prod->product_id . '-' . $new_prod->store_id;
                                                            $inCartQty = $cart[$cartKey]['quantity'] ?? 0;
                                                        @endphp

                                                        @if ($inCartQty > 0)
                                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                                {{-- Decrease --}}
                                                                <form method="POST"
                                                                    action="{{ route('cart.update', ['id' => $new_prod->product_id, 'store_id' => $new_prod->store_id]) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="decrease">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                                </form>

                                                                {{-- Quantity --}}
                                                                <span class="px-2">{{ $inCartQty }}</span>

                                                                {{-- Increase --}}
                                                                <form method="POST"
                                                                    action="{{ route('cart.update', ['id' => $new_prod->product_id, 'store_id' => $new_prod->store_id]) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="increase">
                                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            {{-- Add button --}}
                                                            <form method="POST"
                                                                action="{{ route('cart.add', ['id' => $new_prod->product_id, 'store_id' => $new_prod->store_id]) }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-success w-100">
                                                                    Add + <i class="bi bi-plus-lg"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-danger w-100 py-2">Out of Stock</span>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Custom Small Carousel Controls --}}
                <div class="carousel-controls-container">
                    <button class="carousel-control-prev" type="button" data-bs-target="#arrivedCarousel" data-bs-slide="prev">
                        <span class="custom-control-icon">&lt;</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#arrivedCarousel" data-bs-slide="next">
                        <span class="custom-control-icon">&gt;</span>
                    </button>
                </div>
            </div>
        </div>
    @endif



    {{-- Top Categories --}}
    @if(isset($top_ten_cate) && !$top_ten_cate->isEmpty())
        <div class="container-fluid my-5">
            <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
                <h3>Top Categories</h3>
                <a href="{{ route("products") }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="product-grid">
                @foreach($top_ten_cate as $cate)
                    <a href="{{route('catee', ['cat_id' => $cate->cat_id])}}" class="category-card">
                        <div class="category-image-wrapper">
                            <img src="{{ asset($cate->image) }}" alt="{{ $cate->title }}" class="category-image">
                        </div>
                        <div class="category-details">
                            <h5 class="category-name">{{ $cate->title }}</h5>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif



    <style>
        /*for banners*/
        .carousel-img {
            height: 50vh;
            object-fit: cover;
        }

        /*deal */
        /* Custom Carousel Control Styles */
        #dealsCarousel .carousel-control-prev,
        #dealsCarousel .carousel-control-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 1;
            /* Keep them always visible */
            transition: all 0.2s ease-in-out;
        }

        #dealsCarousel .carousel-control-prev:hover,
        #dealsCarousel .carousel-control-next:hover {
            background-color: #9b9696;
        }

        .custom-control-icon {
            font-size: 1.5rem;
            color: #333;
            line-height: 1;
        }

        /* just arrived */
        #arrivedCarousel .carousel-control-prev,
        #arrivedCarousel .carousel-control-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 1;
            /* Keep them always visible */
            transition: all 0.2s ease-in-out;
        }

        #arrivedCarousel .carousel-control-prev:hover,
        #arrivedCarousel .carousel-control-next:hover {
            background-color: #9b9696;
        }

        /* category-card */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        /* all category */
        .category-card {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            border: 1px solid #e9e9e9;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #fff;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .category-image-wrapper {
            width: 100%;
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .category-card:hover .category-image {
            transform: scale(1.05);
        }

        .category-details {
            padding: 15px;
            text-align: center;
        }

        .category-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin: 0 0 5px;
        }

        .category-description {
            font-size: 0.9rem;
            color: #777;
            margin: 0;
        }
    </style>

    {{-- Countdown Script --}}
    <script>
        function startCountdown() {
            document.querySelectorAll(".countdown-timer").forEach(function (timer) {
                let endTime = parseInt(timer.getAttribute("data-endtime")) * 1000;

                function updateTimer() {
                    let now = new Date().getTime();
                    let distance = endTime - now;

                    if (distance <= 0) {
                        timer.innerHTML = "Expired";
                        return;
                    }

                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    timer.innerHTML = hours + "h " + minutes + "m " + seconds + "s";
                }

                updateTimer(); // first run
                setInterval(updateTimer, 1000);
            });
        }

        document.addEventListener("DOMContentLoaded", startCountdown);
    </script>

@endsection