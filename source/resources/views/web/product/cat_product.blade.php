@extends('web.layout.app')

@section('content')


  @extends('web.layout.sidebar')
  @section('precontent')


   <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    {{-- If search results exist --}}
    @if(isset($Sprods) && count($Sprods) > 0)
        @foreach($Sprods as $Sprod)
            <div class="col mb-4">
                <div class="card h-100 product-card shadow-sm">
                    <a href="{{ route('product_detail', ['id' => $Sprod->product_id, 'store_id' => $produt]) }}"
                       class="text-decoration-none text-dark">
                        @if(isset($Sprod->varients[0]))
                            <img src="{{ asset($Sprod->varients[0]->varient_image) }}" 
                                 class="card-img-top p-3 product-image" 
                                 alt="{{ $Sprod->product_name }}">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" 
                                 class="card-img-top p-3 product-image" 
                                 alt="No Image">
                        @endif
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title product-name">{{ $Sprod->product_name }}</h6>
                        @if(isset($Sprod->varients[0]))
                            <p class="card-text text-muted small mb-1">{{ $Sprod->varients[0]->description }}</p>
                            <h5 class="product-price font-weight-bold">₹{{ $Sprod->varients[0]->price }}</h5>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            @csrf
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-outline-secondary">1</button>
                                <button type="button" class="btn btn-outline-secondary"><i class="fas fa-plus"></i></button>
                            </div>
                            @if(isset($Sprod->varients[0]))
                                <button class="btn add-to-cart-btn" 
                                        data-varient-id="{{ $Sprod->varients[0]->varient_id }}" 
                                        data-qty="1">
                                    Add to Cart
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <h5>More Products</h5>


    {{-- Else: Show default product listing --}}
    @elseif(isset($products) && count($products) > 0)
        @foreach($products as $product)
            <div class="col mb-4">
                <div class="card h-100 product-card shadow-sm">
                    <a href="{{ route('product_detail', ['id' => $product->product_id,'store_id' => $product->store_id]) }}"
                       class="text-decoration-none text-dark">
                        <img src="{{ asset($product->product_image) }}" 
                             class="card-img-top p-3 product-image" 
                             alt="{{ $product->product_name }}">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title product-name">{{ $product->product_name }}</h6>
                        @foreach($prod_variant as $variant)
                            @if($variant->product_id == $product->product_id)
                                <p class="card-text text-muted small mb-1">{{ $variant->description }}</p>
                                <h5 class="product-price font-weight-bold">₹{{ $variant->base_price }}</h5>
                                @break
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            @csrf
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-outline-secondary">1</button>
                                <button type="button" class="btn btn-outline-secondary"><i class="fas fa-plus"></i></button>
                            </div>
                            @if(isset($variant))
                                <button class="btn add-to-cart-btn" 
                                        data-varient-id="{{ $variant->varient_id }}" 
                                        data-qty="1">
                                    Add to Cart
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    
        @else
        <div class="col-12">
            <p class="text-center text-muted">No products available.</p>
        </div>

        {{-- All top selling --}}
    {{-- @elseif(isset()) --}}
    @endif
</div>





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

@endsection
