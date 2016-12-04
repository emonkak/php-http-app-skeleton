<div class="alert alert-{{ $type }} alert-dismissible fade in" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  @if (count($messages) > 1)
    <ul class="mb-0">
      @foreach ($messages as $message)
        <li>{{ $message }}</li>
      @endforeach
    </ul>
  @else
    @foreach ($messages as $message)
      <p class="mb-0">{{ $message }}</p>
    @endforeach
  @endif
</div>
