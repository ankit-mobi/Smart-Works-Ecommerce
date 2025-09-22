@extends('web.layout.app')


@section('content')


  <div class="container-fluid py-4">
    <div class="row">
      @include('web.layout.sidebar')


      <div class="col-sm-9">


        @if(isset($prev_product) && !empty($prev_product))
            <div class="container my-5">
              <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <div class="row g-0">

                  {{-- Product Image --}}
                  <div class="col-md-5 d-flex align-items-center justify-content-center bg-light">
                    <img src="{{ asset($prev_product->product_image) }}" class="img-fluid p-4"
                      alt="{{ $prev_product->product_name }}" style="max-height: 450px; object-fit: contain;">
                  </div>

                  {{-- Product Details --}}
                  <div class="col-md-7">
                    <div class="card-body d-flex flex-column h-100">

                      {{-- Title --}}
                      <h3 class="card-title fw-bold mb-3">{{ $prev_product->product_name }}</h3>

                      {{-- Short Description + Read More --}}
                      <p class="card-text text-muted mb-2" id="shortDescription" style="display: inline;">
                        {{ Str::limit($prev_product->description, 100) }}
                      </p>
                      <button id="readMoreButton" class="btn btn-link p-0 ms-1 align-baseline text-primary"
                        style="display: none;">
                        Read More
                      </button>
                      <button id="readLessButton" class="btn btn-link p-0 ms-1 align-baseline text-primary"
                        style="display: none;">
                        Read Less
                      </button>

                      {{-- Price Section --}}
                      <div class="mt-3">
                        <span class="fw-bold h3 text-success me-2">₹{{ number_format($prev_product->price, 2) }}</span>
                        <span class="text-muted small">
                          <del>₹{{ number_format($prev_product->mrp, 2) }}</del>
                        </span>
                      </div>

                      {{-- Discount --}}
                      @php
                        $discount = $prev_product->mrp - $prev_product->price;
                      @endphp
                      @if($discount > 0)
                        <p class="text-danger fw-bold mt-2">Save ₹{{ $discount }}</p>
                      @endif

                      {{-- Stock / Add Button --}}
                      <div class="mt-auto">        
                                    @if ($prev_product->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $inCartQty = $cart[$prev_product->product_id]['quantity'] ?? 0;
                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST" action="{{ route('cart.update', $prev_product->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST" action="{{ route('cart.update', $prev_product->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST" action="{{ route('cart.add', $prev_product->product_id) }}">
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

                {{-- Full Description --}}
                <div id="fullDescriptionContainer" class="card-footer bg-white border-top p-4" style="display: none;">
                  <h5 class="fw-bold">Description</h5>
                  <p class="text-muted mt-2" id="fullDescriptionText">{{ $prev_product->description }}</p>
                </div>
              </div>
            </div>



            <hr class="my-5">


             {{-- varients of products --}}
            @if(isset($varient) && !$varient->isEmpty())
              <div class="container my-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <h3 class="text-uppercase">Varient of products</h3>
                  <a href="{{ route('products') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>

                <div class="row g-4">
                  @foreach($varient->take(8) as $prod_vats)
                    <div class="col-md-3 col-sm-6">
                      <div class="card h-100 shadow-sm border-0">
                        {{-- Product Image --}}
                        <div class="text-center p-3 bg-light">
                          <a href="{{route('product_detail', ['id' => $prod_vats->product_id, 'store_id' => $prod_vats->store_id])}}"
                            style="text-decoration: none; color: inherit;">
                            <img src="{{ asset($prod_vats->varient_image) }}" alt="{{ $prod_vats->product_name }}"
                              class="img-fluid rounded" style="max-height: 100px; object-fit: contain;"></a>
                        </div>

                        {{-- Product Details --}}
                        <div class="card-body d-flex flex-column">
                          <a href="{{route('product_detail', ['id' => $prod_vats->product_id, 'store_id' => $prod_vats->store_id])}}"
                            style="text-decoration: none; color:inherit">
                            <h6 class="card-title text-truncate">{{ $prod_vats->product_name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($prod_vats->description, 60) }}</p>
                          </a>

                          <div class="mt-auto">
                            {{-- Price & Discount --}}
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="fw-bold text-success">₹{{ number_format($prod_vats->price) }}</span>
                              <span class="text-muted small">
                                <del>₹{{ number_format($prod_vats->mrp) }}</del>
                              </span>
                            </div>
                            @php
                              $discount = $prod_vats->mrp - $prod_vats->price;
                            @endphp
                            @if($discount > 0)
                              <p class="small text-danger mb-2">{{ $discount }} Rs Off</p>
                            @endif
                            {{-- Stock / Add Button --}}
                             <div>
                                    @if ($prod_vats->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $inCartQty = $cart[$prod_vats->product_id]['quantity'] ?? 0;
                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST" action="{{ route('cart.update', $prod_vats->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST" action="{{ route('cart.update', $prod_vats->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST" action="{{ route('cart.add', $prod_vats->product_id) }}">
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
              </div>
            @endif

            {{-- Related Products Section --}}
            @if(isset($related_prods) && !$related_prods->isEmpty())
              <div class="container my-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <h3 class="text-uppercase">Related Products</h3>
                  <a href="{{ route('products') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>

                <div class="row g-4">
                  @foreach($related_prods->take(8) as $related_prod)
                    <div class="col-md-3 col-sm-6">
                      <div class="card h-100 shadow-sm border-0">
                        {{-- Product Image --}}
                        <div class="text-center p-3 bg-light">
                          <a href="{{route('product_detail', ['id' => $related_prod->product_id, 'store_id' => $related_prod->store_id])}}"
                            style="text-decoration: none; color: inherit;">
                            <img src="{{ asset($related_prod->product_image) }}" alt="{{ $related_prod->product_name }}"
                              class="img-fluid rounded" style="max-height: 100px; object-fit: contain;"></a>
                        </div>

                        {{-- Product Details --}}
                        <div class="card-body d-flex flex-column">
                          <a href="{{route('product_detail', ['id' => $related_prod->product_id, 'store_id' => $related_prod->store_id])}}"
                            style="text-decoration: none; color:inherit">
                            <h6 class="card-title text-truncate">{{ $related_prod->product_name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($related_prod->description, 60) }}</p>
                          </a>

                          <div class="mt-auto">
                            {{-- Price & Discount --}}
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="fw-bold text-success">₹{{ number_format($related_prod->price) }}</span>
                              <span class="text-muted small">
                                <del>₹{{ number_format($related_prod->mrp) }}</del>
                              </span>
                            </div>
                            @php
                              $discount = $related_prod->mrp - $related_prod->price;
                            @endphp
                            @if($discount > 0)
                              <p class="small text-danger mb-2">{{ $discount }} Rs Off</p>
                            @endif
                            {{-- Stock / Add Button --}}
                             <div>
                                    @if ($related_prod->stock > 0)
                                        @php
                                            $cart = session('cart', []);
                                            $inCartQty = $cart[$related_prod->product_id]['quantity'] ?? 0;
                                        @endphp

                                        @if ($inCartQty > 0)
                                            <div class="d-flex justify-content-between align-items-center border rounded p-1">
                                                {{-- Decrease --}}
                                                <form method="POST" action="{{ route('cart.update', $related_prod->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">-</button>
                                                </form>

                                                {{-- Quantity --}}
                                                <span class="px-2">{{ $inCartQty }}</span>

                                                {{-- Increase --}}
                                                <form method="POST" action="{{ route('cart.update', $related_prod->product_id) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">+</button>
                                                </form>
                                            </div>
                                        @else
                                            {{-- Add button --}}
                                            <form method="POST" action="{{ route('cart.add', $related_prod->product_id) }}">
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
              </div>
            @endif


          </div>
        @else
        <div class="col-12">
          <p class="text-center text-muted">No products available.</p>
        </div>
      @endif






    </div> {{-- end col-sm-9 --}}
  </div> {{-- end row --}}
  </div> {{-- end container --}}

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      const readMoreButton = document.getElementById('readMoreButton');
      const readLessButton = document.getElementById('readLessButton');
      const fullDescriptionContainer = document.getElementById('fullDescriptionContainer');
      const shortDescription = document.getElementById('shortDescription');


      const fullText = "{{ $prev_product->description }}";
      const charlimit = 100;

      if (fullText.length > charlimit) {
        readMoreButton.style.display = 'inline-block';
      } else {
        shortDescription.textContent = fullText;
      }

      readMoreButton.addEventListener('click', function () {
        fullDescriptionContainer.style.display = 'block';
        shortDescription.textContent = '';
        readMoreButton.style.display = 'none';
        readLessButton.style.display = 'inline-block';
      });

      readLessButton.addEventListener('click', function () {
        fullDescriptionContainer.style.display = 'none';
        shortDescription.textContent = "{{ Str::limit($prev_product->description, 100) }}";
        readMoreButton.style.display = 'inline-block';

        readLessButton.style.display = 'none';
      });
    });
  </script>
@endsection