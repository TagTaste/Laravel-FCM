<div class="col-md-12">
        <div class="col-md-12" style="background-image: url('{{ route('blog.images',$article->image) }}')">
        	<h2> {{ $title }} </h2>
        </div>
        <div class="col-md-9 col-md-offset-3">
        	<p class='text-justify text-align-left'> {!! $article->content !!}</p>
        	
        </div>
</div>