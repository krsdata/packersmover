<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>
                <th>Id</th>
                <th>First Name</th>
                <th>Email</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Created At</th>                                                
                </tr>
    </thead>
    <tbody>
        @if(!$feedback->isEmpty())
            @foreach($feedback as $list)
            <tr id="{{en_de_crypt(@$list->id,'e')}}">
            <td >{{@$list->id}}</td>
                <td >{{@$list->name}}</td>
                <td >{{@$list->email}}</td>
                <td >{{@$list->review}}</td>
                <td >{{@$list->rating	}}</td>
                <td >{{@$list->created_at	}}</td>                 
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Feedback Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $feedback->onEachSide(1)->links('ajax_pagination') !!}
</div>