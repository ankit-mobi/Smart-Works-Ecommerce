{{-- alert handling --}}
    <div class="col-lg-12">
          @if (session()->has('success'))
            <div class="alert alert-success">
              @if(is_array(session()->get('success')))
                <ul>
                  @foreach (session()->get('success') as $message)
                    <li>{{ $message }}</li>
                  @endforeach
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                </ul>
              @else
                {{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
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
        

  {{-- Main  for the categories listing page --}}
<div class="col-sm-3">
    <div class="sticky-top">
        {{-- Categories Card --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h5 class="mb-0 fw-bold">Categories</h5>
                <button class="btn btn-link p-0 text-muted" type="button" data-bs-toggle="collapse"
                    data-bs-target="#categories-collapse" aria-expanded="true" aria-controls="categories-collapse">
                    <i class="fas fa-angle-up"></i>
                </button>
            </div>
            <div class="collapse show" id="categories-collapse">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        {{-- Link for All Products --}}
                        <li class="list-group-item border-0">
                            <a href="{{ route('products') }}" class="text-decoration-none text-dark fw-bold">All Products</a>
                        </li>
                        {{-- Parent Categories Loop --}}
                        @foreach($category->where('parent', 0) as $parentCat)
                            <li class="list-group-item border-0">
                                @php
                                    $hasChildren = $category->where('parent', $parentCat->cat_id)->count() > 0;
                                @endphp

                                @if($hasChildren)
                                    {{-- Collapsible for categories with subcategories --}}
                                    <a data-bs-toggle="collapse" href="#collapse-{{ $parentCat->cat_id }}"
                                       class="text-decoration-none text-dark fw-bold d-block">
                                        {{ $parentCat->title }}
                                        <i class="fas fa-plus float-end category-toggle-icon"></i>
                                    </a>
                                @else
                                    {{-- Simple link for categories without subcategories --}}
                                    <a href="{{ route('catee', [$parentCat->cat_id]) }}"
                                       class="text-dark fw-bold d-block">
                                        {{ $parentCat->title }}
                                    </a>
                                @endif

                                {{-- Subcategories --}}
                                <div id="collapse-{{ $parentCat->cat_id }}" class="collapse mt-2">
                                    <ul class="list-group list-group-flush">
                                        @foreach($category->where('parent', $parentCat->cat_id) as $subCat)
                                            <li class="list-group-item border-0 ps-4">
                                                <a href="{{ route('catee', [$subCat->cat_id]) }}"
                                                   class="text-decoration-none text-secondary d-block">
                                                    {{ $subCat->title }}
                                                </a>
                                                {{-- Child categories (if any) --}}
                                                @foreach($category->where('parent', $subCat->cat_id) as $childCat)
                                                    <a href="{{ route('catee', [$childCat->cat_id]) }}"
                                                       class="text-decoration-none text-muted d-block ps-4">{{ $childCat->title }}</a>
                                                @endforeach
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Sorting options card --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="card-title fw-bold">Sort By</h6>
                <div class="d-flex flex-wrap">
                    <a href="#" class="btn btn-sm btn-outline-secondary me-2 mb-2 active">Popularity</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary me-2 mb-2">Price (Low to High)</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary mb-2">Price (High to Low)</a>
                </div>
            </div>
        </div>
    </div>
</div>


  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle plus/minus icon for collapsible categories
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(element) {
            element.addEventListener('click', function() {
                const icon = this.querySelector('.category-toggle-icon');
                if (icon) {
                    if (this.classList.contains('collapsed')) {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    } else {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    }
                }
            });
        });
    });
</script>
