@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row">

    <div class="col-12">
      <a href="{{ route('word.create') }}">
        <button class="btn btn-success btn-sm mb-2 float-right">+ Add new word</button>
      </a>
    </div>

      @foreach($word as $w)

        <div class="col-12 Word" id="Word-{{$w->id}}">
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
              <a href="{{ route('word.show', $w) }}">
                <button type="button" class="btn btn-secondary">Move</button>
              </a>
              <button type="button" class="btn btn-danger" onclick="deleteWord('{{ $w->id }}', event)">Delete</button>
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

      $.ajax({
        url: `/word/${_word_id}`,
        method: 'DELETE',
        dataType: 'JSON',
        data: {_token: '{{@csrf_token()}}', _method: '{{ method_field('DELETE') }}', id: _word_id},
        success: function (res) {
          console.warn(res);
        }
      });

      const _selector = $(`#Word-${_word_id}`);
      _selector.fadeOut(800, () => {
        $(this).remove();
      });
    }
  </script
@endsection