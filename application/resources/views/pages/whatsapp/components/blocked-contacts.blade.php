<!--Blocked Contacts Management-->
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header">
                <h5><i class="ti-na"></i> {{ cleanLang(__('lang.blocked_contacts')) }}</h5>
            </div>
            <div class="card-body">

                <!--search and filter-->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="search-blocked" placeholder="{{ cleanLang(__('lang.search_contacts')) }}" onkeyup="searchBlockedContacts()">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="filter-block-reason" onchange="filterBlockedContacts()">
                            <option value="">{{ cleanLang(__('lang.all_reasons')) }}</option>
                            <option value="spam">{{ cleanLang(__('lang.spam')) }}</option>
                            <option value="abusive">{{ cleanLang(__('lang.abusive')) }}</option>
                            <option value="inappropriate">{{ cleanLang(__('lang.inappropriate')) }}</option>
                            <option value="other">{{ cleanLang(__('lang.other')) }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-danger" onclick="addBlockedContact()">
                            <i class="ti-plus"></i> {{ cleanLang(__('lang.block_number')) }}
                        </button>
                    </div>
                </div>

                <!--blocked contacts table-->
                <div class="table-responsive">
                    <table class="table table-hover" id="blocked-contacts-table">
                        <thead>
                            <tr>
                                <th>{{ cleanLang(__('lang.contact_name')) }}</th>
                                <th>{{ cleanLang(__('lang.phone_number')) }}</th>
                                <th>{{ cleanLang(__('lang.reason')) }}</th>
                                <th>{{ cleanLang(__('lang.blocked_by')) }}</th>
                                <th>{{ cleanLang(__('lang.blocked_date')) }}</th>
                                <th>{{ cleanLang(__('lang.notes')) }}</th>
                                <th>{{ cleanLang(__('lang.actions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($blocked_contacts) && count($blocked_contacts) > 0)
                                @foreach($blocked_contacts as $contact)
                                <tr data-reason="{{ $contact->block_reason }}">
                                    <td>
                                        <strong>{{ $contact->contact_name ?? __('lang.unknown') }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $contact->phone_number }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">{{ ucfirst($contact->block_reason) }}</span>
                                    </td>
                                    <td>
                                        <img src="{{ getUsersAvatar($contact->blocked_by_avatar_directory, $contact->blocked_by_avatar_filename) }}"
                                            alt="user" class="img-circle avatar-xsmall mr-2">
                                        {{ $contact->blocked_by_name }}
                                    </td>
                                    <td>{{ runtimeDate($contact->blocked_at) }}</td>
                                    <td>
                                        @if($contact->block_notes)
                                            <button class="btn btn-xs btn-link" onclick="viewBlockNotes('{{ addslashes($contact->block_notes) }}')">
                                                <i class="ti-eye"></i> {{ cleanLang(__('lang.view')) }}
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-success" onclick="unblockContact({{ $contact->id }}, '{{ $contact->phone_number }}')">
                                            <i class="ti-unlock"></i> {{ cleanLang(__('lang.unblock')) }}
                                        </button>
                                        <button class="btn btn-xs btn-danger" onclick="deleteBlockedContact({{ $contact->id }})">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted p-4">
                                        <i class="ti-check" style="font-size: 48px; opacity: 0.3; color: green;"></i>
                                        <p>{{ cleanLang(__('lang.no_blocked_contacts')) }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!--block rules-->
                <div class="mt-4">
                    <h6>{{ cleanLang(__('lang.automatic_blocking_rules')) }}</h6>
                    <div class="alert alert-info">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="auto_block_spam" name="auto_block_spam"
                                {{ config('whatsapp.auto_block_spam') ? 'checked' : '' }}
                                onchange="updateBlockingSetting('auto_block_spam', this.checked)">
                            <label class="form-check-label" for="auto_block_spam">
                                {{ cleanLang(__('lang.auto_block_spam_messages')) }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="auto_block_links" name="auto_block_links"
                                {{ config('whatsapp.auto_block_links') ? 'checked' : '' }}
                                onchange="updateBlockingSetting('auto_block_links', this.checked)">
                            <label class="form-check-label" for="auto_block_links">
                                {{ cleanLang(__('lang.auto_block_suspicious_links')) }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="auto_block_repeated" name="auto_block_repeated"
                                {{ config('whatsapp.auto_block_repeated') ? 'checked' : '' }}
                                onchange="updateBlockingSetting('auto_block_repeated', this.checked)">
                            <label class="form-check-label" for="auto_block_repeated">
                                {{ cleanLang(__('lang.auto_block_repeated_messages')) }}
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function searchBlockedContacts() {
        const searchValue = $('#search-blocked').val().toLowerCase();
        $('#blocked-contacts-table tbody tr').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(searchValue));
        });
    }

    function filterBlockedContacts() {
        const reason = $('#filter-block-reason').val();
        if (reason === '') {
            $('#blocked-contacts-table tbody tr').show();
        } else {
            $('#blocked-contacts-table tbody tr').each(function() {
                $(this).toggle($(this).data('reason') === reason);
            });
        }
    }

    function addBlockedContact() {
        NX.loadModal({
            url: '/whatsapp/blocked-contacts/create',
            title: '{{ cleanLang(__("lang.block_contact")) }}',
            size: 'medium'
        });
    }

    function unblockContact(contactId, phoneNumber) {
        if (confirm('{{ cleanLang(__("lang.are_you_sure_unblock")) }} ' + phoneNumber + '?')) {
            $.ajax({
                url: '/whatsapp/blocked-contacts/' + contactId + '/unblock',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        NX.notification({
                            type: 'success',
                            message: '{{ cleanLang(__("lang.contact_unblocked")) }}'
                        });
                        location.reload();
                    }
                }
            });
        }
    }

    function deleteBlockedContact(contactId) {
        if (confirm('{{ cleanLang(__("lang.are_you_sure")) }}')) {
            $.ajax({
                url: '/whatsapp/blocked-contacts/' + contactId,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        }
    }

    function viewBlockNotes(notes) {
        alert(notes); // You can replace this with a better modal
    }

    function updateBlockingSetting(setting, value) {
        $.ajax({
            url: '/whatsapp/settings/blocking',
            method: 'POST',
            data: {
                setting: setting,
                value: value
            },
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: '{{ cleanLang(__("lang.setting_updated")) }}'
                    });
                }
            }
        });
    }
</script>
