<script>
    $(function () {
        // Find elements with class .note-btn.dropdown-toggle
        $('.note-btn.dropdown-toggle').each(function () {
            const $this = $(this);

            // Check if the element has 'data-toggle' attribute
            const toggleAttr = $this.attr('data-toggle');
            if (toggleAttr) {
                // Convert 'data-toggle' to 'data-bs-toggle' and remove 'data-toggle'
                $this.attr('data-bs-toggle', toggleAttr).removeAttr('data-toggle');
            }
        });

        // Initialize Bootstrap dropdown
        $('.note-btn.dropdown-toggle').dropdown();
    });

</script>

<style>
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        width: 100%;
        height: 46px;
        line-height: 46px;
        font-size: 13px;
        padding: 3px 20px;
        padding-left: 20px;
        font-weight: 300;
        border-radius: 30px;
        color: var(--base_color);
        border: 1px solid #ECEEF4
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
        position: absolute;
        top: 1px;
        right: 20px;
        width: 20px;
        color: var(--text-color);
    }

    .select2-dropdown {
        background-color: white;
        border: 1px solid var(--backend-border-color);
        border-radius: 4px;
        box-sizing: border-box;
        display: block;
        position: absolute;
        left: -100000px;
        width: 100%;
        width: 100%;
        background: var(--bg_white);
        overflow: auto !important;
        border-radius: 0px 0px 10px 10px;
        margin-top: 1px;
        z-index: 9999 !important;
        border: 0;
        box-shadow: 0px 10px 20px rgb(108 39 255 / 30%);
        z-index: 1051;
        min-width: 200px;
    }

    .select2-search--dropdown .select2-search__field {
        padding: 4px;
        width: 100%;
        box-sizing: border-box;
        box-sizing: border-box;
        background-color: #fff;
        border: 1px solid rgba(130, 139, 178, 0.3) !important;
        border-radius: 3px;
        box-shadow: none;
        color: #333;
        display: inline-block;
        vertical-align: middle;
        padding: 0px 8px;
        width: 100% !important;
        height: 46px;
        line-height: 46px;
        outline: 0 !important;
    }

    .select2-container {
        width: 100% !important;
        min-width: 90px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--dynamic-text-color);
        line-height: 40px;
    }


    .makeResize.responsiveResize:last-child.col-xl-6 {
        margin-top: 30px;
    }

    #durationBox {
        /*margin-top: 30px;*/
    }

    @media (max-width: 1199px) {
        .responsiveResize2 {
            margin-top: 30px;
        }
    }
    .note-editor .note-editable p {
        margin-bottom: 0;
    }
</style>
<div class="modal-dialog modal-dialog-centered modal-lg student-details">
    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">
                @if($edit)
                    {{__('common.Edit')}}
                @else
                    {{__('common.Add')}}
                @endif
                {{__('courses.Virtual Class Room Lesson')}}
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form
                @if($edit)
                    class="form-horizontal" method="POST" action="{{ route('updateChapter') }}"
                enctype="multipart/form-data">
                @method('PUT')
                @else
                    class="form-horizontal" method="POST" action="{{ route('saveChapter') }}"
                    enctype="multipart/form-data">
                @endif
                @csrf
                <input type="hidden" name="course_id" value="{{@$course_id}}">
                <input type="hidden" name="chapter_id" value="{{@$chapter_id}}">

                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="input_type" value="0" id="">
                        <input type="hidden" name="course_type" value="virtual-classroom" id="">
                        <div class="lesson_div">
                            <div class="row">
                                <div class="row" id="overview_delivery_section">
                                    <div class="col-xl-12 mb_30">
                                        <label class="primary_input_label mt-1">
                                                        {{__('courses.Delivery Mode')}} <span class="required_mark">*</span>
                                        </label>
                                        <select class="primary_select mode_id" name="mode_id"
                                                id="">
                                            <option
                                                value="">{{__('courses.Delivery Mode')}}
                                            </option>
                                            <option
                                                data-display="{{__('courses.Teams')}} *"
                                                value="Teams" {{@$edit->mode_id=="Teams"?'selected':''}}>{{__('courses.Teams')}}
                                            </option>
                                            <option data-display="{{__('courses.Gmeet')}}"
                                                    value="Gmeet" {{@$edit->mode_id=="Gmeet"?'selected':''}}>{{__('courses.Gmeet')}}
                                            </option>

                                            <option data-display="{{__('courses.Zoom')}}" value="Zoom"
                                               
                                                value="Gmeet" {{@$edit->mode_id=="Zoom"?'selected':''}}> {{__('courses.Zoom')}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="row showteams" style="display:none;">
                                            <div class="col-lg-12">
                                                <div class="input-effect videoUrl">
                                                    <label class="primary_input_label mt-1">
                                                        {{__('courses.Teams')}} Link <span class="required_mark">*</span>
                                                    </label>

                                                    <input class="primary_input_field"
                                                        type="text"
                                                        name="tlink"
                                                        placeholder="Enter Teams URL"
                                                        value="{{ @$edit->link }}">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row showmeet" style="display:none;">
                                            <div class="col-lg-12">
                                                <div class="input-effect">
                                                    <label class="primary_input_label mt-1">
                                                        {{__('courses.Gmeet')}} Link <span class="required_mark">*</span>
                                                    </label>

                                                    <input class="primary_input_field"
                                                        type="text"
                                                        name="glink"
                                                        placeholder="Enter Gmeet URL"
                                                        value="{{ @$edit->link }}">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row showzoom" style="display:none;">
                                            <div class="col-lg-12">
                                                <div class="input-effect">
                                                    <label class="primary_input_label mt-1">
                                                        {{__('courses.Zoom')}} Link <span class="required_mark">*</span>
                                                    </label>

                                                    <input class="primary_input_field"
                                                        type="text"
                                                        name="zlink"
                                                        placeholder="Enter Zoom URL"
                                                        value="{{ @$edit->link }}">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-effect mt-2 pt-1">
                                        <label
                                            class="primary_input_label mt-1">{{__('courses.Lesson')}} {{__('common.Name')}}
                                            <span class="required_mark">*</span></label>
                                        <input
                                            class="primary_input_field name{{ $errors->has('chapter_name') ? ' is-invalid' : '' }}"
                                            type="text" name="name"
                                            placeholder="{{__('courses.Lesson')}} {{__('common.Name')}}"
                                            autocomplete="off"
                                            value="{{$edit->name??""}}">
                                        <input type="hidden" name="lesson_id"
                                               value="{{$edit->id??""}}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('chapter_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('chapter_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <p>&nbsp;</p>
                                    <div class="row">
                                        <div class="col-md-3">
                                        
                                            <label
                                                class="primary_input_label mt-1"> {{__('common.Start Date')}}
                                                <span class="required_mark">*</span></label>
                                            <input
                                                class="primary_input_field name{{ $errors->has('chapter_name') ? ' is-invalid' : '' }}"
                                                type="date" name="start_date" min="{{ date('Y-m-d') }}"
                                                placeholder="{{__('courses.Lesson')}} {{__('common.Start Date')}}"
                                                autocomplete="off"
                                                value="{{$edit->start_date??""}}">
                                            
                                            <span class="focus-border"></span>
                                            @if ($errors->has('start_date'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('start_date') }}</strong>
                                                </span>
                                            @endif
                                        
                                    </div>
                                    <div class="col-md-3">
                                        
                                            <label
                                                class="primary_input_label mt-1">{{__('common.Start Time')}}
                                                <span class="required_mark">*</span></label>
                                            <input class="primary_input_field name{{ $errors->has('start_time') ? ' is-invalid' : '' }}"
                                                    type="time"
                                                    name="start_time"
                                                    placeholder="{{__('courses.Lesson')}} {{__('common.Start Time')}}"
                                                    autocomplete="off"
                                                    value="{{$edit->start_time ?? ''}}">
                                            
                                            <span class="focus-border"></span>
                                            @if ($errors->has('start_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('start_time') }}</strong>
                                                </span>
                                            @endif
                                        
                                    </div>
                                    <div class="col-md-3">
                                        
                                            <label
                                                class="primary_input_label mt-1">{{__('common.End Date')}}
                                                <span class="required_mark">*</span></label>
                                            <input
                                                class="primary_input_field name{{ $errors->has('end_date') ? ' is-invalid' : '' }}"
                                                type="date" name="end_date"  min="{{ date('Y-m-d') }}"
                                                placeholder="{{__('courses.Lesson')}} {{__('common.End Date')}}"
                                                autocomplete="off"
                                                value="{{$edit->end_date??""}}">
                                            
                                            <span class="focus-border"></span>
                                            @if ($errors->has('end_date'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('end_date') }}</strong>
                                                </span>
                                            @endif
                                        
                                    </div>
                                    <div class="col-md-3">
                                        
                                            <label
                                                class="primary_input_label mt-1">{{__('common.End Time')}}
                                                <span class="required_mark">*</span></label>
                                            <input
                                                class="primary_input_field name{{ $errors->has('end_time') ? ' is-invalid' : '' }}"
                                                type="time" name="end_time"
                                                placeholder="{{__('courses.Lesson')}} {{__('common.End Time')}}"
                                                autocomplete="off"
                                                value="{{$edit->end_time??""}}">
                                            <input type="hidden" name="lesson_id"
                                                value="{{$edit->id??""}}">
                                            <span class="focus-border"></span>
                                            @if ($errors->has('end_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('end_time') }}</strong>
                                                </span>
                                            @endif
                                        
                                    </div>
                                    </div>
                                </div>
                        
                                <div class="col-lg-12 " id="durationBox">

                                    

                                    <div class="input-effect mt-2 pt-1 d-none">
                                        <div class="" id="">
                                            <label class="primary_input_label mt-1"
                                                   for="">{{__('courses.Privacy')}}
                                                <span class="required_mark">*</span> </label>
                                            <select class="primary_select" name="is_lock">
                                                <option
                                                    data-display="{{__('common.Select')}} {{__('courses.Privacy')}} "
                                                    value="">{{__('common.Select')}} {{__('courses.Privacy')}} </option>
                                                @if(isset($edit))
                                                    <option value="0"
                                                            @if ( @$edit->is_lock==0) selected @endif >{{__('courses.Unlock')}}</option>
                                                    <option value="1"
                                                            @if (@$edit->is_lock==1) selected @endif >{{__('courses.Locked')}}</option>
                                                @else
                                                    <option
                                                        value="0">{{__('courses.Unlock')}}</option>
                                                    <option value="1"
                                                            selected>{{__('courses.Locked')}}</option>
                                                @endif


                                            </select>
                                            @if ($errors->has('is_lock'))
                                                <span class="invalid-feedback invalid-select"
                                                      role="alert">
                                                                            <strong>{{ $errors->first('is_lock') }}</strong>
                                                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="input-effect mt-2 pt-1">
                                        <label class="primary_input_label mt-1">{{__('common.Description')}}
                                        </label>

                                        <textarea class="primary_textarea height_128" name="description"

                                                  id="description" cols="30"
                                                  rows="10">{{$edit->description??""}}</textarea>


                                        <span class="focus-border"></span>
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @if ((Auth::user()->role_id == INSTITUTE_ADMIN && Auth::user()->tenant_id == INSTITUTE) || (Auth::user()->role_id == CORPORATE))
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <label class="primary_input_label"
                                                    for=""> {{__('courses.Trainer Type')}} <strong class="text-danger">*</strong>
                                                </label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-25">
                                                <label class="primary_checkbox d-flex mr-12">
                                                    <input type="radio" id="type1"
                                                        name="type"
                                                        value="1"
                                                        @if(empty(old('type')))checked @else
                                                        {{old('type')==1?"checked":""}}
                                                        @endif>
                                                    <span class="checkmark me-2"></span>{{__('courses.Internal')}}
                                                </label>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-25">
                                                <label class="primary_checkbox d-flex mr-12">
                                                    <input type="radio" id="type2"
                                                        name="type"
                                                        value="2" {{old('type')==2?"checked":""}}>
                                                    <span class="checkmark me-2"></span> {{__('courses.External')}}</label>
                                            </div>
                                        
                                        </div>

                                        <div class="input-effect mt-2 pt-1" id="existing_quiz">
                                            @php
                                                $instructors = \App\Models\User::where('role_id', 10)->where('organization_id', auth()->user()->organization_id)->get();
                                            @endphp

                                            <label class="primary_input_label mt-1" for=""> {{__('common.Internal Instructor')}} <span
                                                        class="required_mark">*</span></label>
                                                <select class="primary_select" name="internal_instructor_id">
                                                    <option
                                                        data-display="{{__('common.Select')}} {{__('common.Internal Instructor')}}"
                                                        value="">
                                                        {{__('common.Select')}} {{__('common.Instructor')}}
                                                    </option>
                                                    
                                                    @foreach ($instructors as $instructor)
                                                        <option
                                                            value="{{ $instructor->id }}"
                                                            {{ isset($edit) ? ($edit->instructor_id == $instructor->id ? 'selected' : '') : '' }}>
                                                            {{ $instructor->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('instructor_id'))
                                                    <span class="invalid-feedback invalid-select" role="alert">
                                                    <strong>{{ $errors->first('instructor_id') }}</strong>
                                                </span>
                                                @endif
                                        </div>

                                        <div class="input-effect mt-2 pt-1" id="external_user">
                                            <label class="primary_input_label mt-1" for=""> {{__('common.External Instructor')}} <span
                                                        class="required_mark">*</span></label>
                                                <select class="primary_select" name="external_instructor_id">
                                                    <option
                                                        data-display="{{__('common.Select')}} {{__('common.External Instructor')}}"
                                                        value="">
                                                        {{__('common.Select')}} {{__('common.Instructor')}}
                                                    </option>

                                                    @foreach ($externals as $external)
                                                        <option
                                                            value="{{ $external->id }}"
                                                            {{ isset($edit) ? ($edit->external == $external->id ? 'selected' : '') : '' }}>
                                                            {{ $external->name }} ({{ $external->company_name }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('instructor_id'))
                                                    <span class="invalid-feedback invalid-select" role="alert">
                                                    <strong>{{ $errors->first('instructor_id') }}</strong>
                                                </span>
                                                @endif
                                        </div>

                                        @endif
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class=" d-flex justify-content-between mt-3">
                    <button type="button" class="primary-btn tr-bg"
                            data-bs-dismiss="modal">@lang('common.Cancel')</button>

                    <button class="primary-btn fix-gr-bg"
                            type="submit">
                        <i class="ti-check"></i>
                        @lang('common.Submit')</button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $('.primary_select').niceSelect();
    })

    initFilePond();


</script>
<script>
    $(document).on('change', '.category_id', function () {
        let key = $(this).data('key');

        let category_id = $('#category_id' + key).find(":selected").val();
        if (category_id === 'Youtube' || category_id === 'URL' || category_id === 'm3u8') {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).show();
            $("#vimeoUrl" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();

        } else if ((category_id === 'Self') || (category_id === 'Zip') || (category_id === 'GoogleDrive') || (category_id === 'PowerPoint') || (category_id === 'Excel') || (category_id === 'Text') || (category_id === 'Word') || (category_id === 'PDF') || (category_id === 'Image') || (category_id === 'AmazonS3') || (category_id === 'SCORM') || (category_id === 'SCORM-AwsS3') || (category_id === 'XAPI') || (category_id === 'XAPI-AwsS3') || (category_id === 'H5P')) {

            $("#iframeBox" + key).hide();
            $("#fileupload" + key).show();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#VdoCipherUrl" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();

        } else if (category_id === 'Vimeo') {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).show();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();

        } else if (category_id === 'VdoCipher') {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#VdoCipherUrl" + key).show();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();

        } else if (category_id === 'Iframe') {
            $("#iframeBox" + key).show();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();
        } else if (category_id === 'BunnyStorage') {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#bunnyStreamUrl" + key).show();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();

        } else if (category_id === 'Storage') {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#media_upload" + key).show();
            $("#editorBox" + key).hide();
        } else if (category_id === 'Editor') {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).show();
        } else {
            $("#iframeBox" + key).hide();
            $("#videoUrl" + key).hide();
            $("#vimeoUrl" + key).hide();
            $("#vimeoVideo" + key).val('');
            $("#youtubeVideo" + key).val('');
            $("#fileupload" + key).hide();
            $("#VdoCipherUrl" + key).hide();
            $("#bunnyStreamUrl" + key).hide();
            $("#media_upload" + key).hide();
            $("#editorBox" + key).hide();


        }

    });
</script>
<script>


    @if(isModuleActive("BunnyStorage"))
    getBunnyListForLesson()

    function getBunnyListForLesson() {
        $('.BunnyVideoLesson').select2({
            ajax: {
                url: '{{ route('bunny_stream.get_lesson') }}',
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    }
                },
                // processResults: function (response) {
                //     return {
                //         results: response,
                //         pagination: {
                //             more: true
                //         }
                //     };
                // },
                cache: true
            }
        });
    }
    @endif



    function getVdoCipherListForLesson() {
        $('.VdoCipherVideoLesson').select2({
            ajax: {
                url: '{{ route('getAllVdocipherData') }}',
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    }
                },
                cache: false
            }
        });
    }

    function getVimeoListForLesson() {
        $('.vimeoVideoLesson').select2({
            ajax: {
                url: '{{ route('getAllVimeoData') }}',
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    }
                },
                cache: false
            }
        });
    }


    $(document).ready(function () {

        getVdoCipherListForLesson();
        getVimeoListForLesson();

        @if(isset($edit) &&$edit->host=='Vimeo')

        $.ajax({
            url: "{{ url('admin/course/vimeo/video') }}?uri={{$edit->video_url}}",
            success: function (data) {
                $(".vimeoVideoLesson option:selected").text(data.name)
                getVimeoListForLesson();
            },
            error: function () {
                console.log('failed')
            }
        });
        @elseif(isset($edit) &&$edit->host=='VdoCipher')
                    $.ajax({
                        url: "{{ url('admin/course/vdocipher/video') }}/{{$edit->video_url}}",
                        success: function (data) {
                            $(".lessonVdocipher option:selected").text(data.title)
                            getVdoCipherListForLesson();
                        },
                        error: function () {
                            console.log('failed')
                        }
                    });
        @endif


    });
    @if ($edit)
    var editLesson = $('#category_id_edit_{{ $edit->id }}');
    editLesson.trigger('change');


    @endif

    $('#lms_editor').summernote({
        placeholder: '',
        tabsize: 2,
        height: 150,
        tooltip: true,
        callbacks: {
            onImageUpload: function (files) {
                sendFile(files, '#lms_editor')
            }
        }
    });

    $(document).ready(function () {

        function toggleMeetingLink(mode){
            $(".showteams, .showmeet, .showzoom").hide();

            if(mode === "Teams"){
                $(".showteams").show();
            } 
            else if(mode === "Gmeet"){
                $(".showmeet").show();
            } 
            else if(mode === "Zoom"){
                $(".showzoom").show();
            }
        }

        // On change
        $(".mode_id").on("change", function () {
            var mode = $(this).val();
            toggleMeetingLink(mode);
        });

        // On edit page load
        var selectedMode = $(".mode_id").val();
        toggleMeetingLink(selectedMode);

        

    });

    
</script>


<script type="text/javascript">
    $(document).ready(function () {

        function toggleMeetingLink(mode){
            $(".showteams, .showmeet, .showzoom").hide();

            if(mode === "Teams"){
                $(".showteams").show();
            } 
            else if(mode === "Gmeet"){
                $(".showmeet").show();
            } 
            else if(mode === "Zoom"){
                $(".showzoom").show();
            }
        }

        // On change
        $(".mode_id").on("change", function () {
            var mode = $(this).val();
            toggleMeetingLink(mode);
        });

        // On edit page load
        var selectedMode = $(".mode_id").val();
        toggleMeetingLink(selectedMode);

         $(document).ready(function () {

        function toggleTrainerType() {
            let type = $('input[name="type"]:checked').val();

            if (type == 1) {
                // Internal → show first, hide second
                $('#existing_quiz').show();
                $('#external_user').hide();
            } else if (type == 2) {
                // External → hide first, show second
                $('#existing_quiz').hide();
                $('#external_user').show();
            }
        }

        // Run on page load (important for edit mode)
        toggleTrainerType();

        // Run on change
        $('input[name="type"]').change(function () {
            toggleTrainerType();
        });

    });
    });
</script>
