@extends('web.layout.app')


@section('content')


<div class="container-fluid py-4">
  <div class="row">
  @include('web.layout.sidebar')
 

 <div class="col-sm-9">
   {{-- Else: Show default product listing --}}
    @if(isset($products) && count($products) > 0)
    <div class="container-fluid my-5">     
                        <div class="row g-4 mx-5">
                            @foreach($products as $deal)
                                <div class="col-md-3 col-sm-6">
                                    <div class="card h-100 shadow-sm border-0">
                                        {{-- Image --}}
                                        <div class="text-center p-3 bg-light">
                                             <a href="{{route('product_detail', ['id' =>$deal->product_id, 'store_id' => $deal->store_id])}}" style="text-decoration: none; color: inherit;">
                                            <img src="{{ asset($deal->product_image) }}" alt="{{ $deal->product_name }}"
                                                class="img-fluid rounded" style="max-height: 220px; min-height: 100px; object-fit: contain;"> </a>
                                        </div> 


                                        {{-- Product Details --}}
                                        <div class="card-body d-flex flex-column">
                                               <a href="{{route('product_detail', ['id' =>$deal->product_id, 'store_id' => $deal->store_id])}}" style="text-decoration: none; color: inherit;">
                                            <h6 class="card-title text-truncate">{{ $deal->product_name }}</h6>
                                            <p class="text-muted small mb-2">{{ Str::limit($deal->description, 60) }}</p></a>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-success">₹{{ number_format($deal->price) }}</span>
                                                <span class="text-muted small">
                                                    <del>₹{{ number_format($deal->mrp) }}</del>
                                                </span>
                                            </div> 

                                             @php
                                                {{$discount = $deal->mrp - $deal->price;}}
                                            @endphp
                                            @if($discount > 0)
                                                <p class="small text-danger mb-2">{{ number_format($discount) }} Rs Off</p>
                                            @endif

                                            {{-- Countdown Timer --}}
                                         <div class="d-flex align-items-center text-warning small mb-2">
                                                <i class="fa-solid fa-stopwatch me-1"></i> 
                                                {{-- <span class="countdown-timer"
                                                    data-endtime="{{ \Carbon\Carbon::parse($deal->valid_to)->timestamp }}">
                                                    Loading...
                                                </span> --}}
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
    @endif  

      </div> {{-- end col-sm-9 --}}
  </div> {{-- end row --}}
</div> {{-- end container --}}


  @endsection
