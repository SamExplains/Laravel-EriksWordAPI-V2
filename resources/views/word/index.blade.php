@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row">

      @foreach($word as $wKey => $w)

        <div class="col-12 Word" id="Word-{{$wKey}}">
          <div class="card">

          <div class="card-header">
            <span class="mr-3 alert-primary p-2">
              {{ $w->longdate }}
            </span> {{ $w->word }}
          </div>

          <div class="card-body">

            <section>
              <h5 class="card-title">Update information</h5>
              <p>Update interval of <span class="alert-warning p-1">{{ $w->update_interval }}</span>, word will next be updated on <span class="alert-warning p-1">{{ $w->update_iso }}</span></p>
            </section>

            <section>
              <h5 class="card-title">Entries</h5>

              <details>
                <summary>Entries information</summary>
                <p>
                  {{ $w->unserializeEntry() }}
                </p>
              </details>

            </section>

            <section>
              <h5 class="card-title">Lexi Stats</h5>

              <details>
                <summary>Lexi Stats information</summary>
                <p>
                  {{ $w->unserializeLexi() }}
                </p>
              </details>

            </section>

            <div class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route('word.edit', $w) }}">
                <button type="button" class="btn btn-secondary">
                  Edit
                </button>
              </a>
              <button type="button" class="btn btn-secondary">Move</button>
              <button type="button" class="btn btn-danger" onclick="deleteWord('{{ $wKey }}', event)">Delete {{ $wKey }}</button>
            </div>

            {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
            </div>
          </div>
        </div>

      @endforeach

    </div>

</div>

@endsection
@section('scripts')
  <script type="application/javascript">
    function deleteWord(_word_id) {
      const _selector = $(`#Word-${_word_id}`);
      _selector.fadeOut(800, () => {
        $(this).remove();
      });
    }
  </script>
@endsection