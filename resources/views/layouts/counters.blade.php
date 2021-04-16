<div class="d-flex flex-wrap justify-content-around">
    @foreach ($data as $d)
        <counter :title="'{{ $d->name ?? '' }}'" :search="'{{ $search_for ?? '' }}'" :icon="'{{ $d->icon ?? '' }}'"
                 :color="'{{ $d->color ?? '' }}'"></counter>
    @endforeach
</div>
