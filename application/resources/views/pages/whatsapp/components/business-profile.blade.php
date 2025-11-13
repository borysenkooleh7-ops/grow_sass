<!--WhatsApp Business Profile Management-->
<div class="row">
    <div class="col-lg-12">

        <form id="business-profile-form">
            <!--basic information-->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><i class="ti-briefcase"></i> {{ cleanLang(__('lang.business_information')) }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.business_name')) }}*</label>
                                <input type="text" class="form-control" name="business_name"
                                    value="{{ $profile->business_name ?? '' }}" required>
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.about')) }}</label>
                                <textarea class="form-control" rows="3" name="about">{{ $profile->about ?? '' }}</textarea>
                                <small class="form-text text-muted">{{ cleanLang(__('lang.business_description_help')) }}</small>
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.business_category')) }}</label>
                                <select class="select2-basic form-control" name="category">
                                    <option value="">{{ cleanLang(__('lang.select_category')) }}</option>
                                    <option value="retail" {{ runtimePreselected('retail', $profile->category ?? '') }}>{{ cleanLang(__('lang.retail')) }}</option>
                                    <option value="restaurant" {{ runtimePreselected('restaurant', $profile->category ?? '') }}>{{ cleanLang(__('lang.restaurant')) }}</option>
                                    <option value="services" {{ runtimePreselected('services', $profile->category ?? '') }}>{{ cleanLang(__('lang.services')) }}</option>
                                    <option value="healthcare" {{ runtimePreselected('healthcare', $profile->category ?? '') }}>{{ cleanLang(__('lang.healthcare')) }}</option>
                                    <option value="education" {{ runtimePreselected('education', $profile->category ?? '') }}>{{ cleanLang(__('lang.education')) }}</option>
                                    <option value="other" {{ runtimePreselected('other', $profile->category ?? '') }}>{{ cleanLang(__('lang.other')) }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.website')) }}</label>
                                <input type="url" class="form-control" name="website"
                                    value="{{ $profile->website ?? '' }}" placeholder="https://example.com">
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.email')) }}</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ $profile->email ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.profile_picture')) }}</label>
                                <div class="profile-picture-container text-center mb-3">
                                    @if(isset($profile->profile_picture))
                                        <img src="{{ $profile->profile_picture }}" alt="Profile" class="img-fluid rounded-circle" style="max-width: 150px;">
                                    @else
                                        <div class="profile-picture-placeholder">
                                            <i class="fab fa-whatsapp" style="font-size: 64px; color: #25D366;"></i>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control-file" name="profile_picture" accept="image/*">
                                <small class="form-text text-muted">{{ cleanLang(__('lang.recommended_size_640x640')) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--contact information-->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><i class="ti-location-pin"></i> {{ cleanLang(__('lang.contact_information')) }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.address')) }}</label>
                                <textarea class="form-control" rows="2" name="address">{{ $profile->address ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.city')) }}</label>
                                <input type="text" class="form-control" name="city" value="{{ $profile->city ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.state')) }}</label>
                                <input type="text" class="form-control" name="state" value="{{ $profile->state ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.postal_code')) }}</label>
                                <input type="text" class="form-control" name="postal_code" value="{{ $profile->postal_code ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.country')) }}</label>
                                <select class="select2-basic form-control" name="country">
                                    <option value="">{{ cleanLang(__('lang.select_country')) }}</option>
                                    @foreach(config('system.countries') as $country)
                                        <option value="{{ $country['code'] }}" {{ runtimePreselected($country['code'], $profile->country ?? '') }}>
                                            {{ $country['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.latitude')) }} / {{ cleanLang(__('lang.longitude')) }}</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="latitude" value="{{ $profile->latitude ?? '' }}" placeholder="40.7128">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="longitude" value="{{ $profile->longitude ?? '' }}" placeholder="-74.0060">
                                    </div>
                                </div>
                                <small class="form-text text-muted">{{ cleanLang(__('lang.location_help')) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--business hours-->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><i class="ti-time"></i> {{ cleanLang(__('lang.business_hours')) }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $business_hours = json_decode($profile->business_hours ?? '{}', true);
                    @endphp

                    @foreach($days as $day)
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="day-{{ $day }}" name="day_enabled[{{ $day }}]"
                                    {{ isset($business_hours[$day]) && $business_hours[$day]['enabled'] ? 'checked' : '' }}>
                                <label class="custom-control-label" for="day-{{ $day }}">
                                    <strong>{{ ucfirst(cleanLang(__('lang.'.$day))) }}</strong>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control form-control-sm" name="opening_time[{{ $day }}]"
                                value="{{ $business_hours[$day]['opening'] ?? '09:00' }}">
                        </div>
                        <div class="col-md-1 text-center">
                            <span>{{ cleanLang(__('lang.to')) }}</span>
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control form-control-sm" name="closing_time[{{ $day }}]"
                                value="{{ $business_hours[$day]['closing'] ?? '17:00' }}">
                        </div>
                    </div>
                    @endforeach

                    <div class="alert alert-info mt-3">
                        <i class="ti-info-alt"></i> {{ cleanLang(__('lang.business_hours_help')) }}
                    </div>
                </div>
            </div>

            <!--save button-->
            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ti-save"></i> {{ cleanLang(__('lang.save_profile')) }}
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    $('#business-profile-form').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/whatsapp/business-profile/save',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.profile_saved_successfully")) }}'
                    });
                }
            },
            error: function(xhr) {
                NX.notification({
                    type: 'error',
                    message: '{{ cleanLang(__("lang.error_saving_profile")) }}'
                });
            }
        });
    });
</script>
