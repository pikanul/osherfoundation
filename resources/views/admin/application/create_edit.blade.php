@extends('admin.layouts.app')
@section('title', 'application Create & Edit')
@section('content')

    <div class="content-wrapper">
        @php
            $links = [
                'Home' => route('admin.dashboard'),
                'application' => route('admin.application.index'),
                $application ? 'application Edit' : 'application Create' => ''
            ]
        @endphp
        <x-bread-crumb-component title='application Feature' :links="$links" />
        <div class="content-body">
            <div class="row" id="table-responsive">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="head-label">
                                <h4 class="mb-0"> {{__($application ? 'Edit application Feature' : 'Add application Feature')}}</h4>
                            </div>

                        </div>

                        <div class="card-body">
                            <form class="row"
                                action="{{ $application ? route('admin.application.update', $application->id) : route('admin.application.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($application)
                                    @method('PUT')
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="si">SI <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="si" name="si"
                                           placeholder="Enter  si"
                                           value="{{ $application ? $application->si : old('si') }}" required>
                                       @error('si')
                                           <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="roll">roll <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="roll" name="roll"
                                           placeholder="Enter  roll"
                                           value="{{ $application ? $application->roll : old('roll') }}" required>
                                       @error('roll')
                                           <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Application Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter  name"
                                            value="{{ $application ? $application->name : old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="father">Father Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="father" name="father"
                                            placeholder="Enter  father"
                                            value="{{ $application ? $application->father : old('father') }}" required>
                                        @error('father')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mother">Mother Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mother" name="mother"
                                            placeholder="Enter  mother"
                                            value="{{ $application ? $application->mother : old('mother') }}" required>
                                        @error('mother')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nid">NID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nid" name="nid"
                                            placeholder="Enter  nid"
                                            value="{{ $application ? $application->nid : old('nid') }}" required>
                                        @error('nid')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nid">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                            placeholder="Enter  Date of Birth"
                                            value="{{ $application ? $application->date_of_birth : old('date_of_birth') }}" required>
                                        @error('date_of_birth')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            placeholder="Enter  phone"
                                            value="{{ $application ? $application->phone : old('phone') }}" required>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_id">Course ID <span class="text-danger">*</span></label>
                                       <select class="form-control" id="course_id" name="course_id" required>
                                           <option value="">Select Course</option>
                                           @foreach (DB::table('courses')->get() as $course)
                                               <option value="{{ $course->id }}"
                                                   {{ $application && $application->course_id == $course->id ? 'selected' : (old('course_id') == $course->id ? 'selected' : '') }}>
                                                   {{ $course->course_name }}</option>
                                           @endforeach
                                       </select>
                                        @error('course_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label for="grade">Grade </label>
                                        <input type="text" class="form-control" id="grade" name="grade"
                                            placeholder="Enter  grade"
                                            value="{{ $application ? $application->grade : old('grade') }}" >
                                        @error('grade')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attendance">Attendance </label>
                                        <input type="text" class="form-control" id="attendance" name="attendance"
                                            placeholder="Enter  attendance"
                                            value="{{ $application ? $application->attendance : old('attendance') }}" >
                                        @error('attendance')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="written">Written</label>
                                        <input type="text" class="form-control" id="written" name="written"
                                            placeholder="Enter  written"
                                            value="{{ $application ? $application->written : old('written') }}" >
                                        @error('written')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>                                    
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="practical">Practical </label>
                                        <input type="text" class="form-control" id="practical" name="practical"
                                            placeholder="Enter  practical"
                                            value="{{ $application ? $application->practical : old('practical') }}" >
                                        @error('practical')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total">Total </label>
                                        <input type="text" class="form-control" id="total" name="total"
                                            placeholder="Enter  total"
                                            value="{{ $application ? $application->total : old('total') }}" >
                                        @error('total')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="application_status">Application Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="application_status" name="application_status" required>
                                            <option value="">Select Status</option>
                                            <option value="pending" {{ $application && $application->application_status == 'pending' ? 'selected' : (old('application_status') == 'pending' ? 'selected' : '') }}>Pending</option>
                                            <option value="approved" {{ $application && $application->application_status == 'approved' ? 'selected' : (old('application_status') == 'approved' ? 'selected' : '') }}>Approved</option>
                                            <option value="rejected" {{ $application && $application->application_status == 'rejected' ? 'selected' : (old('application_status') == 'rejected' ? 'selected' : '') }}>Rejected</option>
                                        </select>
                                        @error('application_status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>



                               


                                
                                
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light mb-1" onclick="resetForm()">Reset</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Responsive tables end -->
        </div>
    </div>
@endsection
@push('script')

    <script>
        function resetForm() {
            $('form')[0].reset();
        }
    </script>

@endpush