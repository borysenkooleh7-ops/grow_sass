@extends('layout.wrapper')

@section('content')

<!-- Main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor">{{ $page['page_heading'] ?? __('lang.whatsapp_contacts') }}</h3>
        </div>
        <div class="col-md-7 col-12 align-self-center text-right">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-add-contact">
                <i class="ti-plus"></i> {{ __('lang.add_contact') }}
            </button>
        </div>
    </div>

    <!--contacts table-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="contacts-table">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.name') }}</th>
                                    <th>{{ __('lang.phone') }}</th>
                                    <th>{{ __('lang.email') }}</th>
                                    <th>{{ __('lang.tags') }}</th>
                                    <th>{{ __('lang.last_contact') }}</th>
                                    <th>{{ __('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->whatsappcontact_name }}</td>
                                    <td>{{ $contact->whatsappcontact_phone }}</td>
                                    <td>{{ $contact->whatsappcontact_email ?? '-' }}</td>
                                    <td>
                                        @if($contact->tags)
                                            @foreach($contact->tags as $tag)
                                                <span class="badge badge-info">{{ $tag->whatsapptag_name }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $contact->whatsappcontact_last_contact_at ? $contact->whatsappcontact_last_contact_at->diffForHumans() : '-' }}</td>
                                    <td>
                                        <a href="/whatsapp/conversations?contact={{ $contact->whatsappcontact_id }}"
                                           class="btn btn-sm btn-info" title="{{ __('lang.view_conversations') }}">
                                            <i class="ti-comments"></i>
                                        </a>
                                        <button class="btn btn-sm btn-warning btn-edit-contact"
                                                data-id="{{ $contact->whatsappcontact_id }}"
                                                title="{{ __('lang.edit') }}">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        @if($contact->whatsappcontact_blocked)
                                            <button class="btn btn-sm btn-success btn-unblock-contact"
                                                    data-id="{{ $contact->whatsappcontact_id }}"
                                                    data-url="{{ url('/whatsapp/contacts/' . $contact->whatsappcontact_id . '/unblock') }}"
                                                    title="{{ __('lang.unblock_contact') }}">
                                                <i class="ti-unlock"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-danger btn-block-contact"
                                                    data-id="{{ $contact->whatsappcontact_id }}"
                                                    data-url="{{ url('/whatsapp/contacts/' . $contact->whatsappcontact_id . '/block') }}"
                                                    title="{{ __('lang.block_contact') }}">
                                                <i class="ti-lock"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-12">
                            {{ $contacts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('javascript')
<script>
    // Block contact
    $(document).on('click', '.btn-block-contact', function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const button = $(this);

        if (confirm('Are you sure you want to block this contact? They will not be able to send you messages.')) {
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to block contact');
                    }
                },
                error: function() {
                    alert('Failed to block contact. Please try again.');
                }
            });
        }
    });

    // Unblock contact
    $(document).on('click', '.btn-unblock-contact', function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const button = $(this);

        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to unblock contact');
                }
            },
            error: function() {
                alert('Failed to unblock contact. Please try again.');
            }
        });
    });
</script>
@endsection
