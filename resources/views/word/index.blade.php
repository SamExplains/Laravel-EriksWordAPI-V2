@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row">

      @foreach($word as $w)

        <div class="col-12 Word">
          <div class="card">

          <div class="card-header">
            <span class="mr-3 alert-primary p-2">
              {{ $w->longdate }}
            </span> {{ $w->word }}
          </div>

          <div class="card-body">

            <section>
              <h5 class="card-title">Update information</h5>
              <p>Update interval of <span class="alert-warning p-1">{{ $w->update_interval }}</span> will next be updated on <span class="alert-warning p-1">{{ $w->update_iso }}</span></p>
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

            {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
            </div>
          </div>
        </div>

      @endforeach

    </div>

</div>

@stop