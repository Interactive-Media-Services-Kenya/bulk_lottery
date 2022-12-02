@if ($view == 1)
    <a class="btn btn-xs btn-primary" href="{{ route($routePart . '.show', $row->id) }}">
        <i class="bi-eye"></i>
    </a>
@endif
@if ($edit == 1)
    <a class="btn btn-xs btn-warning" href="{{ route($routePart . '.edit', $row->id) }}">
        <i class="bi-pencil"></i>
    </a>
@endif
@if($delete == 1)
    <form action="{{ route($routePart .'.destroy', $row->id) }}" method="POST"
        onsubmit="return confirm('Are You Sure You Want To Delete?');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-sm btn-danger"><i class="bi-trash"></i></button>
    </form>
@endif
