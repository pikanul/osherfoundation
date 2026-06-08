

            <form action="{{ $project ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}"
                class="form_ajax_submit" method="POST" class="" enctype="multipart/form-data">
                @csrf

                @if ($project)
                @method('put')
                @endif
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name"
                                value="{{ $project ? $project->name : '' }}" name="name" placeholder="Enter Name"
                                oninput="generateSlugMake(this, '#slug')">
                        </div>
                    </div>
                      <div class="col-xl-6 col-md-6 col-12 mb-1">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug"
                                value="{{ $project ? $project->slug : old('slug') }}" name="slug"
                                placeholder="Enter slug">
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                        <div class="form-group">
                            <label for="duration">Duration</label>
                            <input type="text" class="form-control" id="duration"
                                value="{{ $project ? $project->duration : '' }}" name="duration"
                                placeholder="Enter Duration">
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                        <div class="form-group">
                            <label for="funded_by">Funded By</label>
                            <input type="text" class="form-control" id="funded_by"
                                value="{{ $project ? $project->funded_by : '' }}" name="funded_by"
                                placeholder="Enter funded by">
                        </div>
                    </div>


                    <div class=" col-md-6 col-12 mb-1">
                        <div class="form-group">
                            <label for="project_category_id">Stage Of Project</label>
                            <select class="select2 form-control" name="project_category_id"
                                data-url="{{ route('admin.project_categories.select') }}" data-ajax="true">
                                <option
                                    value="{{ $project && $project->project_category_id ? $project->project_category_id : ''}}"
                                    {{ $project && $project->project_category_id == 0 ? 'selected' : '' }}>
                                    {{ $project && $project->category ? $project->category->name : 'Select Category'}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control select2" name="status">
                                <option value="1" {{ $project && $project->status == 1 ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ $project && $project->status == 0 ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
                <button class="btn btn-primary waves-effect waves-float waves-light float-right"
                    type="submit">{{ $project ? 'Update' : 'Create' }}</button>
            </form>
