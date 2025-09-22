@extends('web.layout.app')


@section('content')


<div class="container-fluid py-4">
  <div class="row">
  @include('web.layout.sidebar')
 

 <div class="col-sm-9">
   <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    {{-- If search results exist --}}
    @if(isset($prod) && count($prod) > 0)
           
           @foreach($prod as $Sprod)
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            {{-- Product Image --}}
            <div class="text-center p-3 bg-light">
                <a href="{{route('product_detail', ['id' =>$Sprod->product_id, 'store_id' => $Sprod->store_id])}}" style="text-decoration: none; color: inherit;">
                <img src="{{ asset($Sprod->product_image) }}" alt="{{ $Sprod->product_name }}"
                     class="img-fluid rounded" style="max-height: 220px; object-fit: contain;"></a>
            </div>

            {{-- Product Details --}}
            <div class="card-body d-flex flex-column">
                <a href="{{route('product_detail', ['id' =>$Sprod->product_id, 'store_id' => $Sprod->store_id])}}" style="text-decoration: none; color:inherit">
                <h6 class="card-title text-truncate">{{ $Sprod->product_name }}</h6>
                <p class="text-muted small mb-2">{{ Str::limit($Sprod->description, 60) }}</p>
                </a>

                <div class="mt-auto">
                    {{-- Price & Discount --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success">₹{{ number_format($Sprod->price) }}</span>
                        <span class="text-muted small">
                            <del>₹{{ number_format($Sprod->mrp) }}</del>
                        </span>
                    </div>
                    @php
                        $discount = $Sprod->mrp - $Sprod->price;
                    @endphp
                    @if($discount > 0)
                        <p class="small text-danger mb-2">{{ $discount }} Rs Off</p>
                    @endif
                    {{-- Stock / Add Button --}}
                     <div>
                                    @if ($Sprod->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $inCartQty = $cart[$Sprod->product_id]['quantity'] ?? 0;
                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST" action="{{ route('cart.update', $Sprod->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST" action="{{ route('cart.update', $Sprod->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST" action="{{ route('cart.add', $Sprod->product_id) }}">
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
    </div>
@endforeach



    {{-- Else: Show default product listing --}}
    @elseif(isset($products) && count($products) > 0)
       @foreach($products as $product)
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            {{-- Product Image --}}
            <div class="text-center p-3 bg-light">
                <a href="{{route('product_detail', ['id' =>$product->product_id, 'store_id' => $product->store_id])}}" style="text-decoration: none; color: inherit;">
                <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}"
                     class="img-fluid rounded" style="max-height: 220px; object-fit: contain;"></a>
            </div>

            {{-- Product Details --}}
            <div class="card-body d-flex flex-column">
                <a href="{{route('product_detail', ['id' =>$product->product_id, 'store_id' => $product->store_id])}}" style="text-decoration: none; color:inherit">
                <h6 class="card-title text-truncate">{{ $product->product_name }}</h6>
                <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                </a>

                <div class="mt-auto">
                    {{-- Price & Discount --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success">₹{{ number_format($product->price) }}</span>
                        <span class="text-muted small">
                            <del>₹{{ number_format($product->mrp) }}</del>
                        </span>
                    </div>
                    @php
                        $discount = $product->mrp - $product->price;
                    @endphp
                    @if($discount > 0)
                        <p class="small text-danger mb-2">{{ $discount }} Rs Off</p>
                    @endif
                    {{-- Stock / Add Button --}}
                     <div>
                                    @if ($product->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $inCartQty = $cart[$product->product_id]['quantity'] ?? 0;
                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST" action="{{ route('cart.update', $product->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST" action="{{ route('cart.update', $product->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST" action="{{ route('cart.add', $product->product_id) }}">
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
    </div>
@endforeach

    
        
     {{-- All top selling , deal of the day, just_arrived_prod --}}
     @elseif(isset($allproducts) && count($allproducts) > 0)
       @foreach($allproducts as $allprod)
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            {{-- Product Image --}}
            <div class="text-center p-3 bg-light">
                <a href="{{route('product_detail', ['id' =>$allprod->product_id, 'store_id' => $allprod->store_id])}}" style="text-decoration: none; color: inherit;">
                <img src="{{ asset($allprod->product_image) }}" alt="{{ $allprod->product_name }}"
                     class="img-fluid rounded" style="max-height: 220px; object-fit: contain;"></a>
            </div>

            {{-- Product Details --}}
            <div class="card-body d-flex flex-column">
                <a href="{{route('product_detail', ['id' =>$allprod->product_id, 'store_id' => $allprod->store_id])}}" style="text-decoration: none; color:inherit">
                <h6 class="card-title text-truncate">{{ $allprod->product_name }}</h6>
                <p class="text-muted small mb-2">{{ Str::limit($allprod->description, 60) }}</p>
                </a>

                <div class="mt-auto">
                    {{-- Price & Discount --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success">₹{{ number_format($allprod->price) }}</span>
                        <span class="text-muted small">
                            <del>₹{{ number_format($allprod->mrp) }}</del>
                        </span>
                    </div>
                    @php
                        $discount = $allprod->mrp - $allprod->price;
                    @endphp
                    @if($discount > 0)
                        <p class="small text-danger mb-2">{{ $discount }} Rs Off</p>
                    @endif
                    {{-- Stock / Add Button --}}
                     <div>
                                    @if ($allprod->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $inCartQty = $cart[$allprod->product_id]['quantity'] ?? 0;
                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST" action="{{ route('cart.update', $allprod->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST" action="{{ route('cart.update', $allprod->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST" action="{{ route('cart.add', $allprod->product_id) }}">
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
    </div>
@endforeach

                        

    
        @else
        <div class="col-12">
            <p class="text-center text-muted">No products available.</p>
        </div>

       
    @endif
</div>



  </div> {{-- end col-sm-9 --}}
  </div> {{-- end row --}}
</div> {{-- end container --}}



  <style>
  /* This rule ensures all product images have the same dimensions */
  .product-image {
    width: 150px;
    /* or a larger size, e.g., 200px */
    height: 250px;
    object-fit: contain;
    /* The key property for maintaining aspect ratio */
    display: block;
    /* Ensures the image behaves as a block element */
    margin: 0 auto;
    /* Centers the image */
  }

 
</style>
@endsection

