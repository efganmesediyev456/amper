@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Məhsullar</h4>
                <div class="buttons">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-success">Geriyə qayıt</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.products.store')}}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                            id="{{$language->code}}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{$language->code}}"
                            type="button" role="tab"
                            aria-controls="{{$language->code}}"
                            aria-selected="true">
                            {{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->first) active @endif"
                        id="{{$language->code}}" role="tabpanel"
                        aria-labelledby="{{$language->code}}-tab">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_{{$language->code}}" class="form-label">Ad ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Ad daxil edin">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug {{$language->code}}</label>
                                    <input type="text" class="form-control" name="slug[{{$language->code}}]"
                                        id="name[{{ $language->code }}]"
                                        placeholder="Slug daxil edin"
                                        value="">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label">Açar sözlər {{$language->code}}</label>
                                    <input type="text" class="form-control tagsview" name="seo_keywords[{{$language->code}}]"
                                        id="seo_keywords[{{ $language->code }}]"
                                        placeholder="Açar sözlər daxil edin"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name_az" class="form-label">Seo Haqqında {{$language->code}}</label>
                                    <textarea class="form-control" name="seo_description[{{$language->code}}]"
                                        id="seo_description[{{ $language->code }}]"
                                        placeholder="Haqqında daxil edin"></textarea>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Haqqında {{$language->code}}</label>
                                    <textarea class="form-control ckeditor" name="description[{{$language->code}}]"
                                        id="description[{{ $language->code }}]"
                                        placeholder=""
                                        value=""></textarea>
                                </div>
                            </div>

                        </div>









                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Məhsulun kodu</label>
                            <input name="product_code" class="form-control" id="" type="text">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Yeni məhsul?</label>
                            <select name="is_new" class="form-control" id="">
                                <option value="1">Bəli</option>
                                <option value="0">Xeyr</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Qiymət</label>
                            <input type="number" name="price" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Endirimli Qiymət</label>
                            <input type="number" name="discountPrice" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Stokda say</label>
                            <input type="number" name="quantity" value="" class="form-control">
                        </div>
                    </div>
                   

                

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Şəkil</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 480x400 piksel</small>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Şəkillər</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                            <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 480x400 piksel</small>

                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Kateqoriya</label>
                            <select name="category_id" class="form-control select2" id="category_id">
                                <option value="">Seçin</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Alt kateqoriya</label>
                            <select name="subcategory_id" class="form-select select2 select" id="subcategory_id">
                                <option>Seçin</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Brend</label>
                            <select name="brend_id" class="form-select select2 select" id="brend_id">
                                <option value="">Seçin</option>
                            </select>
                        </div>
                    </div>





        </div>

        <div class="row mt-4">
                    <div class="col-12">
                        <h4>Məhsulun Özəllikləri</h4>
                        <hr>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="property_select" class="form-label">Özəllik</label>
                            <select id="property_select" class="form-control property-select">
                                <option value="">Seçin</option>
                                @foreach($properties as $property)
                                    <option value="{{$property->id}}">{{$property->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sub_property_select" class="form-label">Alt Özəllik</label>
                            <select id="sub_property_select" class="form-control sub-property-select" disabled>
                                <option value="">Əvvəlcə özəllik seçin</option>
                            </select>
                            <button type="button" id="add_property_btn" class="btn btn-primary mt-2" disabled>Əlavə Et</button>
                        </div>
                    </div>

                    <!-- Seçilmiş özəlliklərin görüntülənəcəyi yer -->
                    <div class="col-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5>Seçilmiş Özəlliklər</h5>
                            </div>
                            <div class="card-body">
                                <div id="selected_properties_container" class="row">
                                    <!-- JavaScript ilə əlavə olunacaq -->
                                </div>
                                <!-- Hidden input fields to store selected properties and sub-properties -->
                                <div id="hidden_properties_container">
                                    <!-- JavaScript ilə əlavə olunacaq -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="row mt-2">
            <div class="d-flex justify-content-end">
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-success">
                        <i class="fas fa-save"></i>
                        <span>Yadda saxla</span>
                    </button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</div>

@endsection



@push('js')
    <script>
        $(document).ready(function() {

            $("#category_id").on("change", function(e){
                var value=$(this).val();
                $.ajax({ 
                    url:"{{ route('admin.categories.getSubCategories') }}",
                    type:"post",
                    data:{
                        value,
                        '_token':"{{ csrf_token() }}"
                    },
                    success:function(e){
                        $("#subcategory_id").html(e.view).select2()
                    },
                    error:function(e){

                    }
                })
            })

            $("#subcategory_id").on("change", function(e){
                var value=$(this).val();
                $.ajax({ 
                    url:"{{ route('admin.categories.getBrends') }}",
                    type:"post",
                    data:{
                        value,
                        '_token':"{{ csrf_token() }}"
                    },
                    success:function(e){
                        $("#brend_id").html(e.view).select2()
                    },
                    error:function(e){

                    }
                })
            })

            // Property seçildiyi zaman alt özəllikləri yüklə
            $('#property_select').on('change', function() {
                const propertyId = $(this).val();

                if(propertyId) {
                    // Alt özəllikləri AJAX ilə əldə et
                    $.ajax({
                        url: "/admin/products/get-sub-properties",
                        type: 'GET',
                        data: {
                            property_id: propertyId
                        },
                        success: function(response) {
                            let options = '<option value="">Alt özəllik seçin</option>';

                            if(response.subProperties.length > 0) {
                                response.subProperties.forEach(function(subProperty) {
                                    options += `<option value="${subProperty.id}">${subProperty.title}</option>`;
                                });

                                $('#sub_property_select').html(options);
                                $('#sub_property_select').prop('disabled', false);
                                $('#add_property_btn').prop('disabled', false);
                            } else {
                                $('#sub_property_select').html('<option value="">Bu özəllik üçün alt özəllik tapılmadı</option>');
                                $('#sub_property_select').prop('disabled', true);
                                $('#add_property_btn').prop('disabled', true);
                            }
                        },
                        error: function(xhr) {
                            console.error('AJAX Error: ', xhr);
                            alert('Alt özəlliklər yüklənərkən xəta baş verdi.');
                        }
                    });
                } else {
                    $('#sub_property_select').html('<option value="">Əvvəlcə özəllik seçin</option>');
                    $('#sub_property_select').prop('disabled', true);
                    $('#add_property_btn').prop('disabled', true);
                }
            });

            // Əlavə Et düyməsinə klik zamanı seçilmiş özəllikləri əlavə et
            $('#add_property_btn').on('click', function() {
                const propertyId = $('#property_select').val();
                const propertyText = $('#property_select option:selected').text();
                const subPropertyId = $('#sub_property_select').val();
                const subPropertyText = $('#sub_property_select option:selected').text();

                if(!propertyId || !subPropertyId) {
                    alert('Zəhmət olmasa həm özəllik, həm də alt özəllik seçin.');
                    return;
                }

                // Əgər bu cütlük artıq əlavə edilməyibsə
                if(!$(`input[data-property="${propertyId}"][data-subproperty="${subPropertyId}"]`).length) {
                    // Görüntü üçün element
                    const propertyElement = `
                    <div class="col-md-6 mb-2 property-item" data-property="${propertyId}" data-subproperty="${subPropertyId}">
                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                            <div>
                                <strong>${propertyText}:</strong> ${subPropertyText}
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-property">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;

                    // Hidden input fields
                    const hiddenInputs = `
                    <input type="hidden" name="properties[]" value="${propertyId}" data-property="${propertyId}" data-subproperty="${subPropertyId}">
                    <input type="hidden" name="sub_properties[]" value="${subPropertyId}" data-property="${propertyId}" data-subproperty="${subPropertyId}">
                `;

                    // Elementləri əlavə et
                    $('#selected_properties_container').append(propertyElement);
                    $('#hidden_properties_container').append(hiddenInputs);

                    // Seçimləri sıfırla
                    $('#property_select').val('');
                    $('#sub_property_select').html('<option value="">Əvvəlcə özəllik seçin</option>');
                    $('#sub_property_select').prop('disabled', true);
                    $('#add_property_btn').prop('disabled', true);
                } else {
                    alert('Bu özəllik və alt özəllik cütü artıq əlavə edilib.');
                }
            });

            // Əlavə edilmiş özəlliyi silmək
            $(document).on('click', '.remove-property', function() {
                const item = $(this).closest('.property-item');
                const propertyId = item.data('property');
                const subPropertyId = item.data('subproperty');

                // Görünən elementi sil
                item.remove();

                // Hidden inputları sil
                $(`input[data-property="${propertyId}"][data-subproperty="${subPropertyId}"]`).remove();
            });
        });

         $(document).ready(function () {
            $(".select2").select2();
        });
    </script>
@endpush
