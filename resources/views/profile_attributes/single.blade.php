<form action="#">
    <div class="form-group">
        <label for="nome">ID</label>
        <p class="form-control-static"></p>
    </div>
    <div class="form-group">
        <label for="name">NAME</label>
        <p class="form-control-static">{{$profileAttribute->name}}</p>
    </div>
    <div class="form-group">
        <label for="label">LABEL</label>
        <p class="form-control-static">{{$profileAttribute->label}}</p>
    </div>
    <div class="form-group">
        <label for="description">DESCRIPTION</label>
        <p class="form-control-static">{{$profileAttribute->description}}</p>
    </div>
    <div class="form-group">
        <label for="user_id">CREATED BY</label>
        <p class="form-control-static">{{$profileAttribute->user_id}}</p>
    </div>
    <div class="form-group">
        <label for="multiline">MULTILINE</label>
        <p class="form-control-static">{{$profileAttribute->multiline}}</p>
    </div>
    <div class="form-group">
        <label for="requires_upload">REQUIRES_UPLOAD</label>
        <p class="form-control-static">{{$profileAttribute->requires_upload}}</p>
    </div>
    <div class="form-group">
        <label for="allowed_mime_types">ALLOWED_MIME_TYPES</label>
        <p class="form-control-static">{{$profileAttribute->allowed_mime_types}}</p>
    </div>
    <div class="form-group">
        <label for="enabled">ENABLED</label>
        <p class="form-control-static">{{$profileAttribute->enabled}}</p>
    </div>
    <div class="form-group">
        <label for="required">REQUIRED</label>
        <p class="form-control-static">{{$profileAttribute->required}}</p>
    </div>

    @if($profileAttribute->children)
        <div class="form-group">
            <label for="">Children</label>
            <p class="form-control-static">
                @each('profile_attributes.single',$profileAttribute->children, 'profileAttribute')
            </p>
        </div>
    @endif

    <div class="form-group">
        <label for="parent_id">PARENT_ID</label>
        <p class="form-control-static">{{$profileAttribute->parent_id}}</p>
    </div>

    <div class="form-group">
        <label for="template_id">TEMPLATE_ID</label>
        <p class="form-control-static">{{$profileAttribute->template_id}}</p>
    </div>
    <div class="form-group">
        <label for="template_id">PROFILE TYPE</label>
        <p class="form-control-static">{{$profileAttribute->profileType->type}}</p>
    </div>
</form>