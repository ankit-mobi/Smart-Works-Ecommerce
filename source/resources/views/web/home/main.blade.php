@extends('web.layout.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    {{-- main banners --}}
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
            <a href="{{ route('products') }}" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div class="row g-4">
            @foreach($top_selling->take(4) as $topsells)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Product Image --}}
                        <div class="text-center p-3 bg-light">
                            <img src="{{ asset($topsells->product_image) }}" alt="{{ $topsells->product_name }}"
                                class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                        </div>

                        {{-- Product Details --}}
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title text-truncate">{{ $topsells->product_name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($topsells->description, 60) }}</p>

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
                                        <button class="btn btn-sm btn-outline-success w-100">
                                            Add <i class="bi bi-plus-lg"></i>
                                        </button>
                                    @else
                                        <span class="badge bg-danger w-100 py-2">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div><br>

    {{-- Latest Three Categories --}}
    {{-- <div class="category-section-container">
        <div class="category-header">
            <h3 class="category-title">Top Categories</h3>
            <a href="{{route('products')}}" class="view-all-button">View All</a>
        </div>

        <div class="category-grid">
            @foreach($latest_category as $latest_cats)
            <a href="{{route('catee', ['cat_id' => $latest_cats->cat_id])}}" class="category-card">
                <div class="category-image-wrapper">
                    <img src="{{ asset($latest_cats->image) }}" alt="{{ $latest_cats->title }}" class="category-image">
                </div>
                <div class="category-details">
                    <h5 class="category-name">{{ $latest_cats->title }}</h5>
                    <p class="category-description">{{ $latest_cats->description }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div> --}}


    {{-- deal products --}}
    {{-- <div class="deal-header-container">
        <h3 class="deal-title-heading">Deal of the Week</h3>
        <a href="#" class="view-all-button">View All Deals</a>
    </div> --}}
    {{-- <div class="product-grid">
        @foreach ($deal_products as $deal_product)
        <div class="product-card">
            <div class="product-image-container">
                <a href="" class="img-link"> {{route('product_detail', ['id' => $deal_product->product_id])}}
                    <img class="product-image" src="{{ asset($deal_product->varient_image) }}">
                </a>
                <ul class="product-actions">
                    <li><a href="#" data-id="{{ $deal_product->varient_id }}"><i class="lar la-heart"></i></a></li>
                    <li><a href="#" data-id="{{ $deal_product->varient_id }}"><i class="lar la-plus-square"></i></a></li>
                    <li><a href="javascript:void(0)" data-id="{{ $deal_product->varient_id }}"><i
                                class="lar la-eye"></i></a></li>
                </ul>
            </div>
            <div class="product-details">
                {{-- <a href="{{ url('shop-page/category/' . Str::slug($deal_product->title)) }}"
                    class="product-category">{{ $deal_product->title }}</a> --}}
                {{-- <h4 class="product-title">
                    <a href="{{route('product_detail', ['id' => $deal_product->product_id])}}">{{
                        $deal_product->product_name }}</a>
                </h4>
                <div class="product-prices">
                    <del>₹{{ number_format($deal_product->mrp, 2) }}</del>
                    <span class="price">₹{{ number_format($deal_product->price, 2) }}</span>
                </div>
            </div>
            <div class="add-to-cart-section">

                <div class="quantity-control">
                    <button class="quantity-btn minus"><i class="las la-minus"></i></button>
                    <input type="number" name="qty" value="1" min="1" class="quantity-input">
                    <button class="quantity-btn plus"><i class="las la-plus"></i></button>
                </div>
                <button class="btn add-to-cart-btn" data-varient-id="{{ $deal_product->varient_id }}" data-qty="1">
                    Add to Cart
                </button>

            </div>
        </div>
        @endforeach
    </div> --}}


    {{-- Deals of the Day --}}
    <div class="container-fluid my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
            <h3 class="text-uppercase">Deals of the Day</h3>
            <a href="" class="btn btn-outline-primary btn-sm">View All</a>
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
                                            <img src="{{ asset($deal->product_image) }}" alt="{{ $deal->product_name }}"
                                                class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                                        </div>

                                        {{-- Product Details --}}
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title text-truncate">{{ $deal->product_name }}</h6>
                                            <p class="text-muted small mb-2">{{ Str::limit($deal->description, 60) }}</p>

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
                                                    <button class="btn btn-sm btn-outline-success w-100">
                                                        Add <i class="bi bi-plus-lg"></i>
                                                    </button>
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
    </div><br>

    {{-- secondary_banner Image Section --}}
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


    {{-- Just arrived products --}}
    {{-- <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
            <h3 class="explore-title text-uppercase">Just Arrived Products</h3>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($whatsnew as $new_prod)
                <div class="col d-flex">
                    <div class="card h-100 product-card shadow-sm border-0 w-100">
                        <a href="{{ route('product_detail', ['id' => $new_prod->product_id]) }}" class="d-block">
                            <img src="{{ asset($new_prod->product_image) }}" class="card-img-top img-fluid"
                                alt="{{ $new_prod->product_name }}">
                        </a>
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title mb-1">
                                <a href="{{ route('product_detail', ['id' => $new_prod->product_id]) }}"
                                    class="text-dark text-decoration-none">{{ $new_prod->product_name }}</a>
                            </h5>
                            <div class="mt-auto">
                                @if($new_prod->price && $new_prod->price < $new_prod->mrp)
                                    <span
                                        class="text-muted text-decoration-line-through me-2">₹{{ number_format($new_prod->mrp, 2) }}</span>
                                    <span class="h5 fw-bold text-success">₹{{ number_format($new_prod->price, 2) }}</span>
                                @else
                                    <span class="h5 fw-bold">₹{{ number_format($new_prod->mrp, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}


    <div class="container-fluid my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 mx-5">
            <h3 class="text-uppercase">Just Arrived Products</h3>
            <a href="" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div id="dealsCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                @foreach($whatsnew->chunk(4) as $chunkIndex => $dealChunk)
                    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                        <div class="row g-4 mx-5">
                            @foreach($dealChunk as $deal)
                                <div class="col-md-3 col-sm-6">
                                    <div class="card h-100 shadow-sm border-0">
                                        {{-- Image --}}
                                        <div class="text-center p-3 bg-light">
                                            <img src="{{ asset($deal->product_image) }}" alt="{{ $deal->product_name }}"
                                                class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                                        </div>

                                        {{-- Product Details --}}
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title text-truncate">{{ $deal->product_name }}</h6>
                                            <p class="text-muted small mb-2">{{ Str::limit($deal->description, 60) }}</p>

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

                                            

                                            {{-- Stock / Add Button --}}
                                            <div class="mt-auto">
                                                @if ($deal->stock > 0)
                                                    <button class="btn btn-sm btn-outline-success w-100">
                                                        Add <i class="bi bi-plus-lg"></i>
                                                    </button>
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














    {{-- products_siding --}}
    <h3>Products</h3>
    <div class="product-grid">
        @foreach ($products_siding as $product)
            <div class="product-card">
                <div class="product-image-container">
                    <a href="{{ route('product_detail', ['id' => $product->product_id]) }}" class="img-link">
                        <img class="product-image" src="{{ asset($product->varient_image) }}">
                    </a>
                    <ul class="product-actions">
                        <li><a href="#" data-id="{{ $product->varient_id }}"><i class="lar la-heart"></i></a></li>
                        <li><a href="#" data-id="{{ $product->varient_id }}"><i class="lar la-plus-square"></i></a></li>
                        <li><a href="javascript:void(0)" data-id="{{ $product->varient_id }}"><i class="lar la-eye"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="product-details">
                    {{-- <a href="{{ url('shop-page/category/' . Str::slug($product->title)) }}" class="product-category">{{
                        $product->title }}</a> --}}
                    <h4 class="product-title">
                        <a href="{{ route('product_detail', ['id' => $product->product_id]) }}">{{ $product->product_name }}</a>
                    </h4>
                    <div class="product-prices">
                        <del>₹{{ number_format($product->base_mrp, 2) }}</del>
                        <span class="price">₹{{ number_format($product->base_price, 2) }}</span>
                    </div>
                </div>
                <div class="add-to-cart-section">
                    @csrf
                    <div class="quantity-control">
                        <button class="quantity-btn minus"><i class="las la-minus"></i></button>
                        <input type="number" name="qty" value="1" min="1" class="quantity-input">
                        <button class="quantity-btn plus"><i class="las la-plus"></i></button>
                    </div>
                    <button class="btn add-to-cart-btn" data-varient-id="{{ $product->varient_id }}" data-qty="1">Add to
                        Cart</button>
                </div>
            </div>
        @endforeach
    </div>

    <br><br><br><br>
    <p class="text-center text-danger"><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores, architecto.
            Doloremque natus, atque provident officia qui numquam iure excepturi! Veniam nesciunt dolorem commodi, officiis
            repellat ratione cum sit sapiente natus!
            Ad quisquam possimus eos, odio perspiciatis illo laboriosam magni, natus consequatur beatae molestiae
            necessitatibus quae reprehenderit hic? Quod, est cupiditate! Animi odit mollitia cum laudantium maiores
            recusandae explicabo commodi accusamus.
            Cupiditate harum corporis error non aliquam ad quia, quis porro odio atque facere tenetur. Ut minus laboriosam,
            porro recusandae quibusdam labore, possimus tempora ratione eum repellendus esse error, nam doloremque?
            Praesentium ipsam enim possimus quam voluptas cupiditate quidem, ratione fugiat nobis id placeat laborum
            recusandae. Accusamus doloremque, esse temporibus quas unde nam sapiente excepturi eligendi ipsam ratione enim
            soluta obcaecati.
            Praesentium recusandae natus nihil! Expedita iure minima, totam dolores voluptatibus dolorem incidunt, ratione
            iste explicabo accusantium quis impedit laboriosam, nostrum assumenda praesentium eos delectus maiores quae ad
            architecto iusto veniam!</b></p>
    <br><br><br><br>


    {{-- Categories_siding --}}
    <h3>Categories</h3>
    <div class="product-grid">
        @foreach($cate_siding as $cats)
            <a href="{{route('catee', ['cat_id' => $cats->cat_id])}}" class="category-card">
                <div class="category-image-wrapper">
                    <img src="{{ asset($cats->image) }}" alt="{{ $cats->title }}" class="category-image">
                </div>
                <div class="category-details">
                    <h5 class="category-name">{{ $cats->title }}</h5>
                    <p class="category-description">{{ $cats->description }}</p>
                </div>
            </a>
        @endforeach
    </div>


    <style>
        /*for banners*/
        .carousel-img {
            height: 50vh;
            object-fit: cover;
        }

        /* top selling product */

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

        #dealsCarousel .carousel-control-prev {
            left: -20px;
        }

        #dealsCarousel .carousel-control-next {
            right: -20px;
        }

        #dealsCarousel .carousel-control-prev:hover,
        #dealsCarousel .carousel-control-next:hover {
            background-color: #f0f0f0;
        }

        .custom-control-icon {
            font-size: 1.5rem;
            color: #333;
            line-height: 1;
        }

        /* Hide the default Bootstrap icons */
        #dealsCarousel .carousel-control-prev-icon,
        #dealsCarousel .carousel-control-next-icon {
            display: none;
        }



        /* Custom CSS for product cards Just arrived products*/
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .product-card .card-img-top {
            height: 200px;
            /* Or your preferred fixed height */
            object-fit: cover;
            /* Ensures the image covers the container without distortion */
            padding: 1rem;
        }

        .product-card .card-body {
            padding: 1rem;
        }

        .product-card .card-title a {
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
    </style>

    <style>
        .offer-card {
            background-size: cover;
            background-position: center;
            height: 250px;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .offer-card h3 {
            font-weight: bold;
        }


        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease-in-out;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .product-card:hover .product-actions {
            opacity: 1;
        }

        .product-actions li a {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s;
        }

        .product-actions li a:hover {
            background-color: #f0f0f0;
        }

        .product-details {
            padding: 15px;
            text-align: center;
            flex-grow: 1;
        }

        .product-category {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .product-title {
            font-size: 1.2rem;
            margin: 8px 0;
        }

        .product-title a {
            text-decoration: none;
            color: #333;
            transition: color 0.2s;
        }

        .product-title a:hover {
            color: #007bff;
            /* Example hover color */
        }

        .product-prices {
            margin-top: 10px;
        }

        .product-prices del {
            color: #aaa;
            margin-right: 10px;
        }

        .product-prices .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .add-to-cart-section {
            padding: 15px;
            background-color: #f9f9f9;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
        }

        .quantity-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 16px;
            color: #555;
            transition: background-color 0.2s;
        }

        .quantity-btn:hover {
            background-color: #eee;
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: none;
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-inner-spin-button,
        .quantity-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s;
            font-weight: bold;
            text-align: center;
            flex-grow: 1;
            margin-left: 10px;
        }

        .add-to-cart-btn:hover {
            background-color: #218838;
        }

        /* deal title and add button */
        /* Container for the heading and button */
        .deal-header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 15px;
            /* Add some padding to match grid */
        }

        /* Button styling */
        .view-all-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .view-all-button:hover {
            background-color: #0056b3;
        }


        /* top category */
        .category-section-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .category-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .view-all-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.2s ease-in-out;
        }

        .view-all-button:hover {
            background-color: #0056b3;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

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


        /* banner section */
        .banner-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .banner-card a {
            display: block;
            text-decoration: none;
        }

        .banner-card img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .banner-card:hover img {
            transform: scale(1.05);
        }

        .banner-text {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .large-banner .banner-text h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }

        .small-banner .banner-text h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }

        .banner-text .btn {
            font-weight: bold;
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