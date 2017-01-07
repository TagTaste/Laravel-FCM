<div class="col-md-8">
        <div class="col-md-12" >
            <img src="{{ route('dishes.image',$article->image) }}" alt="" width="auto" height="200px">
        	<h2> {{ $title }} </h2>
        </div>
        <div class="col-md-9 col-md-offset-3">
        	<p class='text-justify text-align-left'> {!! $article->description !!}</p>

        </div>
</div>
<div class="col-md-4">
	<div class="row">
		<div class="com-md-3">
			<a class="btn btn-xs btn-warning" href="{{ route('articles.edit', ['id' => $id, 'type' => 'dish']) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
		</div>
		<div class="com-md-3">
			<a class="btn btn-xs btn-warning" href="{{ route('recipe_articles.create', $article->id) }}"><i class="glyphicon glyphicon-edit"></i> Add Receipe</a>
		</div>
        <div class="com-md-3">
            <a class="btn btn-xs btn-warning" href="{{ route('recipe_articles.edit', $article->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit Receipe</a>
        </div>
		<div class="com-md-3">
			<form action="{{ route('articles.destroy', $id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="type" value="dish">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
            </form>
		</div>
	</div>
</div>