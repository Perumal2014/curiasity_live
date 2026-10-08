<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-rating input[type="radio"]:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f5b301;
    }
</style>
@extends('backend.master')
@php
    $table_name='tenant_galleries';
@endphp
@section('table')
    {{$table_name}}
@endsection
@section('mainContent')
    @include("backend.partials.alertMessage")
    @php
        $LanguageList = getLanguageList();
    @endphp
    {!! generateBreadcrumb() !!}
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xxl-3">
                    <div class="white-box mb_30  student-details header-menu">
                        <div class="box_header common_table_header">
                            <div class="main-title d-flex mb-0">
                                <h3 class="mb-0"> @if(!isset($edit))
                                        {{__('setting.Add Review') }}
                                    @else
                                        {{__('setting.Update Review')}}
                                    @endif</h3>
                                @if(isset($edit))
                                    @if (permissionCheck('course.category.store'))
                                        <a href="{{route('course.category')}}"
                                           class="primary-btn small fix-gr-bg ml-4 d-flex justify-content-center align-items-center"
                                           style="line-height: 25px;"
                                           title="{{__('courses.Add New')}}">+</a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="row pt-0">
                            @if(isModuleActive('FrontendMultiLang'))
                                <ul class="nav nav-tabs no-bottom-border  mt-sm-md-20 mb-10 ml-3"
                                    role="tablist">
                                    @foreach ($LanguageList as $key => $language)
                                        <li class="nav-item">
                                            <a class="nav-link  @if (auth()->user()->language_code == $language->code) active @endif"
                                               href="#element{{$language->code}}"
                                               role="tab"
                                               data-bs-toggle="tab">{{ $language->native }}  </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>


                        @if (isset($edit))
                            <form action="{{route('reviews.update')}}" method="POST"
                                  id="gallery-form"
                                  name="gallery-form" enctype="multipart/form-data">
                                <input type="hidden" name="id"
                                       value="{{$edit->id}}">
                                @else
                                    
                                        <form action="{{route('reviews.store') }}" method="POST"
                                              id="gallery-form" name="gallery-form"
                                              enctype="multipart/form-data">
                                    
                                @endif
                                            @csrf

                                            
                                            <div class="row">
                                                <div class="col-lg-12 mt-10">
                                                    <div class="mb-15">
                                                        <label class="primary_input_label"
                                                               for="reviewer_name">{{ __('setting.Reviewer Name') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input class="primary_input_field" type="text"
                                                               name="reviewer_name" autocomplete="off"
                                                               value="{{isset($edit)? $edit->reviewer_name:old('reviewer_name')}}"
                                                               placeholder="{{ __('setting.Reviewer Name') }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-10">
                                                    <div class="mb-15">
                                                        <label class="primary_input_label"
                                                               for="description">{{ __('setting.Description') }} <span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="primary_textarea" name="description"
                                                                  id="description"
                                                                  placeholder="{{ __('setting.Description') }}">{{isset($edit)? $edit->description:old('description')}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-10">
                                                    <div class="mb-15">
                                                        <label class="primary_input_label" for="rating">
                                                            {{ __('setting.Rating') }} <span class="text-danger">*</span>
                                                        </label>

                                                        @php
                                                            $selectedRating = isset($edit) ? $edit->rating : old('rating', 0);
                                                        @endphp

                                                        <div class="star-rating">
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <input type="radio"
                                                                    id="star{{ $i }}"
                                                                    name="rating"
                                                                    value="{{ $i }}"
                                                                    {{ $selectedRating == $i ? 'checked' : '' }}>

                                                                <label for="star{{ $i }}" title="{{ $i }} Stars">
                                                                    <i class="fa fa-star"></i>
                                                                </label>
                                                            @endfor
                                                        </div>

                                                        @error('rating')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="col-lg-12 text-center">
                                                    <div class="d-flex justify-content-center pt_20">
                                                        <button type="submit"
                                                                class="primary-btn semi_large fix-gr-bg"
                                                                data-bs-toggle="tooltip" title="Gallery"
                                                                id="save_button_parent">
                                                            <i class=" fa fa-check "></i>
                                                            @if(!isset($edit))
                                                                {{ __('common.Save') }}
                                                            @else
                                                                {{ __('common.Update') }}
                                                            @endif
                                                        </button>


                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </section>


    
    @include('backend.partials.delete_modal')
@endsection
@push('scripts')
    <script type="application/javascript">


        dataTableOptions = updateColumnExportOption(dataTableOptions, [0, 1, 2, 3, 4, ]);

        let table = $('#lms_table').DataTable(dataTableOptions);


    </script>
 @endpush
`   1