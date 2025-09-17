@extends('web.layout.app')

@section('content')
<div class="col-lg-12">
          @if (session()->has('success'))
            <div class="alert alert-success">
              @if(is_array(session()->get('success')))
                <ul>
                  @foreach (session()->get('success') as $message)
                    <li>{{ $message }}</li>
                  @endforeach
                </ul>
              @else
                {{ session()->get('success') }}
              @endif
            </div>
          @endif
          @if (count($errors) > 0)
            @if($errors->any())
              <div class="alert alert-danger" role="alert">
                {{$errors->first()}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
            @endif
          @endif
        </div>

  {{-- Main container for the product listing page --}}
  <div class="container-fluid py-4">
    <div class="row">
      {{-- Left sidebar for filters --}}
      <div class="col-sm-3">
        {{-- Categories filter card --}}
        <div class="sticky-top"> {{-- for sticky-top behaviours--}}
          <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Categories</h5>
              <button class="btn btn-link p-0" type="button" data-toggle="collapse" data-target="#categories-collapse"
                aria-expanded="true" aria-controls="categories-collapse">
                <i class="fas fa-angle-up"></i>
              </button>
            </div>
            <div class="collapse show" id="categories-collapse">
              <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                  {{-- All Products link --}}
                  <li class="list-group-item border-0">
                    <a href="{{ route('products') }}" class="text-decoration-none text-dark font-weight-bold">All
                      Products</a>
                  </li>

                  @if(count($category) > 0)
                    @foreach($category as $cat)
                      @if($cat->parent == 0)
                        {{-- Parent category with sub-categories --}}
                        <li class="list-group-item border-0">
                          <a data-toggle="collapse" href="#collapse-{{ $cat->cat_id }}"
                            class="text-decoration-none text-dark font-weight-bold d-block">
                            {{ $cat->title }}
                            <i class="fa fa-plus float-right"></i>
                          </a>
                          <div id="collapse-{{ $cat->cat_id }}" class="collapse mt-2">
                            <ul class="list-group list-group-flush">
                              @foreach($category_sub as $cat_sub)
                                @if($cat_sub->parent == $cat->cat_id)
                                  {{-- Sub-category --}}
                                  <li class="list-group-item border-0 pl-4">
                                    <a href="{{ route('catee', [$cat_sub->cat_id]) }}"
                                      class="text-decoration-none text-danger font-weight-normal d-block">
                                      {{ $cat_sub->title }}
                                    </a>
                                    {{-- Check for child categories --}}
                                    @if(count($category_child) > 0)
                                      <div class="mt-2">
                                        @foreach($category_child as $cat_child)
                                          @if($cat_sub->cat_id == $cat_child->parent)
                                            {{-- Child category --}}
                                            <a href="{{ route('catee', [$cat_child->cat_id]) }}"
                                              class="text-decoration-none text-secondary d-block pl-4">{{ $cat_child->title }}</a>
                                          @endif
                                        @endforeach
                                      </div>
                                    @endif
                                  </li>
                                @endif
                              @endforeach
                            </ul>
                          </div>
                        </li>
                      @endif
                    @endforeach
                  @endif
                </ul>
              </div>
            </div>
          </div>

          {{-- Price range filter card --}}
          {{-- <div class="card shadow-sm mb-4">
            <div class="card-header">
              <h5 class="mb-0">Price (₹)</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="price-range-1" value="0-500">
                  <label class="form-check-label" for="price-range-1">₹0 - ₹500</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="price-range-2" value="500-1000">
                  <label class="form-check-label" for="price-range-2">₹500 - ₹1000</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="price-range-3" value="1000-2000">
                  <label class="form-check-label" for="price-range-3">₹1000 - ₹2000</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="price-range-4" value="2000-5000">
                  <label class="form-check-label" for="price-range-4">₹2000 - ₹5000</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="price-range-5" value="5000-10000">
                  <label class="form-check-label" for="price-range-5">₹5000 - ₹10000</label>
                </div>
              </form>
            </div>
          </div> --}}
        </div>
      </div>

      {{-- Right section for products and sorting --}}
      <div class="col-sm-9">
        {{-- Sorting options card --}}
        <div class="card shadow-sm mb-4">
          <div class="card-body d-flex justify-content-start align-items-center">
            <span class="mr-3 text-muted">Sort By:</span>
            <button class="btn btn-sm btn-outline-secondary mr-2 active">Popularity</button>
            <button class="btn btn-sm btn-outline-secondary mr-2">Price (Low to High)</button>
            <button class="btn btn-sm btn-outline-secondary">Price (High to Low)</button>
          </div>
        </div>

        

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    {{-- If search results exist --}}
    @if(isset($prod) && count($prod) > 0)
        @foreach($prod as $product)
            <div class="col mb-4">
                <div class="card h-100 product-card shadow-sm">
                    <a href="{{ route('product_detail', ['id' => $product->product_id]) }}"
                       class="text-decoration-none text-dark">
                        @if(isset($product->varients[0]))
                            <img src="{{ asset($product->varients[0]->varient_image) }}" 
                                 class="card-img-top p-3 product-image" 
                                 alt="{{ $product->product_name }}">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" 
                                 class="card-img-top p-3 product-image" 
                                 alt="No Image">
                        @endif
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title product-name">{{ $product->product_name }}</h6>
                        @if(isset($product->varients[0]))
                            <p class="card-text text-muted small mb-1">{{ $product->varients[0]->description }}</p>
                            <h5 class="product-price font-weight-bold">₹{{ $product->varients[0]->price }}</h5>
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
                            @if(isset($product->varients[0]))
                                <button class="btn add-to-cart-btn" 
                                        data-varient-id="{{ $product->varients[0]->varient_id }}" 
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
                    <a href="{{ route('product_detail', ['id' => $product->product_id]) }}"
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
    @endif
</div>

      </div>
    </div>
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

  div#headingone12 {
    margin-top: 4px !important;
  }
</style>

@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      // Toggle plus/minus icon for collapsible elements
      $('.collapse').on('show.bs.collapse', function () {
        $(this).prev().find('.fa').removeClass('fa-plus').addClass('fa-minus');
      }).on('hide.bs.collapse', function () {
        $(this).prev().find('.fa').removeClass('fa-minus').addClass('fa-plus');
      });
    });
  </script>
@endpush