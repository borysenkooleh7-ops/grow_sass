@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? 'WhatsApp Tags' }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button" class="btn btn-info btn-sm" id="btn-add-tag">
                <i class="ti-plus"></i> {{ __('lang.add_tag') }}
            </button>
        </div>
    </div>

    <!--tags list-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.name') }}</th>
                                    <th>{{ __('lang.color') }}</th>
                                    <th>{{ __('lang.description') }}</th>
                                    <th>{{ __('lang.contacts') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $tag)
                                <tr>
                                    <td>
                                        <span class="badge" style="background-color: {{ $tag->whatsapptag_color ?? '#007bff' }}">
                                            {{ $tag->whatsapptag_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="color-box" style="background-color: {{ $tag->whatsapptag_color ?? '#007bff' }}; width: 30px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    </td>
                                    <td>{{ $tag->whatsapptag_description ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-pill badge-info">0</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info btn-edit-tag"
                                                data-id="{{ $tag->whatsapptag_id }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete-tag"
                                                data-id="{{ $tag->whatsapptag_id }}">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="ti-info-alt"></i>
                                            {{ __('lang.no_tags_found') }}.
                                            <a href="javascript:void(0)" id="btn-add-first-tag">
                                                {{ __('lang.add_your_first_tag') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!--ADD TAG MODAL-->
<div class="modal fade" id="modal-add-tag" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('lang.add_tag') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add-tag">
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('lang.tag_name') }} *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('lang.color') }}</label>
                        <input type="color" class="form-control" name="color" value="#007bff">
                    </div>

                    <div class="form-group">
                        <label>{{ __('lang.description') }}</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="ti-check"></i> {{ __('lang.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
$(document).ready(function() {
    $('#btn-add-tag, #btn-add-first-tag').on('click', function() {
        $('#modal-add-tag').modal('show');
    });

    $('#form-add-tag').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '/whatsapp/tags/create',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#modal-add-tag').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endsection
