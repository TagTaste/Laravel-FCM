@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Shoutout / Edit #{{$shoutout->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('shoutouts.update', $shoutout->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
	<label for="content-field">Content</label>
	<textarea name="content" id="content-field" class="form-control" rows="3">{{ old('content', $shoutout->content ) }}</textarea>
</div> <div class="form-group">
	<label for="profile_id-field">Profile_id</label>
	--profile_id--
</div> <div class="form-group">
	<label for="company_id-field">Company_id</label>
	--company_id--
</div> <div class="form-group">
	<label for="flag-field">Flag</label>
	--flag--
</div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('shoutouts.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection