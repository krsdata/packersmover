@if ($paginator->hasPages())
    <div class="col-md-12 d-inline-block p-0 my-3">
            <nav>
            <ul class="pagination flex-wrap float-right "  id="ajax_pagination">
                    @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                             <i class="mdi mdi-chevron-left"></i>
                            </a>
                         </li>
                    {{--<li class="disabled"><img class="" src="{{url('./app-assets/images/icon/home_left_arrow.png')}}"></li>--}}
                @else
                   {{-- <li class="prevs_enbl" ><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><img class="" src="{{url('./app-assets/images/icon/home_left_arrow.png')}}"></a></li>--}}
                    <li class="page-item prevs_enbl">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                             <i class="mdi mdi-chevron-left"></i>
                            </a>
                         </li>
                    @endif


              @foreach ($elements as $element)
              {{-- "Three Dots" Separator --}}
              @if (is_string($element))
                  <li class="page-item disabled"><a class="page-link">{{ $element }}</a></li>
              @endif


              {{-- Array Of Links --}}
              @if (is_array($element))
                  @foreach ($element as $page => $url)
                      @if ($page == $paginator->currentPage())
                          <li class="page-item active"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                      @else

                          <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                      @endif
                  @endforeach
              @endif
          @endforeach


          @if ($paginator->hasMorePages())
          <li class="page-item arrow_right"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="mdi mdi-chevron-right"></i></a></li>
          {{--<li class="arrow_right"><a class="" href="{{ $paginator->nextPageUrl() }}" ><img src="{{url('./app-assets/images/icon/home_right_arrow.png')}}"></a></li>--}}
      @else
      <li class="page-item arrow_right disabled"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel=""><i class="mdi mdi-chevron-right"></i></a></li>
          {{--<li class="arrow_right disabled"><img class="" src="{{url('./app-assets/images/icon/home_right_arrow.png')}}"></li>--}}
      @endif

            </ul>
            </nav>
            </div>
@endif
