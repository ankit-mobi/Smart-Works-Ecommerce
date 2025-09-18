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
  <br><br><br>

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

         @yield('precontent')

    </div>
  </div>


 
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
