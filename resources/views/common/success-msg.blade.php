@if ($message = Session::get('success'))
    <div class="col-sm-12">
      <div class="alert alert-success">
        <p>{{ $message }}</p>
      </div>
    </div>
@endif