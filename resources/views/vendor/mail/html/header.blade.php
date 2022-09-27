<tr>
    <td class="header">
        <a href="{{ url('/') }}">
        	@if(!empty($logo)) 
	        <img src="{{ asset('images/') }}/{{$logo }}" alt="logo"/>
	        @else 
	        <img src="{{ asset('images/logo.png') }}" alt="logo"/>
	        @endif
        </a>
    </td>
</tr>
