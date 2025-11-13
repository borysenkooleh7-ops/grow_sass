<!--Media Gallery-->
<div class="row">
    <div class="col-lg-12">

        <!--filters-->
        <div class="media-gallery-filters mb-3">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary active" data-filter="all" onclick="filterMedia('all')">
                    <i class="ti-layout-grid2"></i> {{ cleanLang(__('lang.all')) }} ({{ $stats['total'] ?? 0 }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="images" onclick="filterMedia('images')">
                    <i class="ti-image"></i> {{ cleanLang(__('lang.images')) }} ({{ $stats['images'] ?? 0 }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="videos" onclick="filterMedia('videos')">
                    <i class="ti-video-clapper"></i> {{ cleanLang(__('lang.videos')) }} ({{ $stats['videos'] ?? 0 }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="documents" onclick="filterMedia('documents')">
                    <i class="ti-file"></i> {{ cleanLang(__('lang.documents')) }} ({{ $stats['documents'] ?? 0 }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="audio" onclick="filterMedia('audio')">
                    <i class="ti-music-alt"></i> {{ cleanLang(__('lang.audio')) }} ({{ $stats['audio'] ?? 0 }})
                </button>
            </div>

            <div class="float-right">
                <select class="form-control form-control-sm" id="sort-by" onchange="sortMedia(this.value)">
                    <option value="date_desc">{{ cleanLang(__('lang.newest_first')) }}</option>
                    <option value="date_asc">{{ cleanLang(__('lang.oldest_first')) }}</option>
                    <option value="size_desc">{{ cleanLang(__('lang.largest_first')) }}</option>
                    <option value="size_asc">{{ cleanLang(__('lang.smallest_first')) }}</option>
                </select>
            </div>
        </div>

        <!--media grid-->
        <div class="media-gallery-grid" id="media-gallery-grid">
            @if(isset($media) && count($media) > 0)
                @foreach($media as $item)
                <div class="media-item" data-type="{{ $item->type }}" data-id="{{ $item->id }}">
                    <div class="media-thumbnail">
                        @if($item->type == 'image')
                            <img src="{{ $item->url }}" alt="{{ $item->filename }}" onclick="viewMedia({{ $item->id }})">
                        @elseif($item->type == 'video')
                            <div class="video-thumbnail" onclick="viewMedia({{ $item->id }})">
                                <i class="ti-control-play"></i>
                                <img src="{{ $item->thumbnail_url ?? asset('public/images/video-placeholder.png') }}" alt="{{ $item->filename }}">
                            </div>
                        @elseif($item->type == 'document')
                            <div class="document-thumbnail" onclick="viewMedia({{ $item->id }})">
                                <i class="ti-file"></i>
                                <span class="file-extension">{{ strtoupper($item->extension) }}</span>
                            </div>
                        @elseif($item->type == 'audio')
                            <div class="audio-thumbnail" onclick="viewMedia({{ $item->id }})">
                                <i class="ti-music-alt"></i>
                            </div>
                        @endif

                        <div class="media-overlay">
                            <div class="media-actions">
                                <button class="btn btn-xs btn-light" onclick="downloadMedia({{ $item->id }})" title="{{ __('lang.download') }}">
                                    <i class="ti-download"></i>
                                </button>
                                <button class="btn btn-xs btn-light" onclick="shareMedia({{ $item->id }})" title="{{ __('lang.share') }}">
                                    <i class="ti-share"></i>
                                </button>
                                <button class="btn btn-xs btn-danger" onclick="deleteMedia({{ $item->id }})" title="{{ __('lang.delete') }}">
                                    <i class="ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="media-info">
                        <div class="media-filename" title="{{ $item->filename }}">{{ str_limit($item->filename, 20) }}</div>
                        <div class="media-meta">
                            <small class="text-muted">
                                {{ formatFileSize($item->size) }} • {{ runtimeDateAgo($item->created_at) }}
                            </small>
                        </div>
                        <div class="media-sender">
                            <small>
                                @if($item->sender_type == 'client')
                                    <i class="ti-user"></i> {{ $item->sender_name }}
                                @else
                                    <i class="ti-headphone-alt"></i> {{ $item->sender_name }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach

                <!--load more-->
                @if($media->hasMorePages())
                <div class="col-12 text-center mt-3">
                    <button class="btn btn-outline-primary" onclick="loadMoreMedia()">
                        <i class="ti-reload"></i> {{ cleanLang(__('lang.load_more')) }}
                    </button>
                </div>
                @endif
            @else
                <div class="col-12 text-center text-muted p-5">
                    <i class="ti-gallery" style="font-size: 64px; opacity: 0.3;"></i>
                    <h4 class="mt-3">{{ cleanLang(__('lang.no_media_found')) }}</h4>
                    <p>{{ cleanLang(__('lang.media_will_appear_here')) }}</p>
                </div>
            @endif
        </div>

    </div>
</div>


<script>
    let currentFilter = 'all';
    let currentPage = 1;

    function filterMedia(type) {
        currentFilter = type;
        currentPage = 1;

        // Update active button
        $('.media-gallery-filters .btn').removeClass('active');
        $('[data-filter="' + type + '"]').addClass('active');

        // Filter items
        if (type === 'all') {
            $('.media-item').show();
        } else {
            $('.media-item').hide();
            $('.media-item[data-type="' + type + '"]').show();
        }
    }

    function sortMedia(sortBy) {
        // Implement sorting logic
        $.ajax({
            url: '/whatsapp/media/sort',
            method: 'GET',
            data: { sort: sortBy, filter: currentFilter },
            success: function(response) {
                $('#media-gallery-grid').html(response.html);
            }
        });
    }

    function viewMedia(mediaId) {
        NX.loadModal({
            url: '/whatsapp/media/' + mediaId + '/view',
            title: '{{ cleanLang(__("lang.view_media")) }}',
            size: 'large'
        });
    }

    function downloadMedia(mediaId) {
        window.location.href = '/whatsapp/media/' + mediaId + '/download';
    }

    function shareMedia(mediaId) {
        NX.loadModal({
            url: '/whatsapp/media/' + mediaId + '/share',
            title: '{{ cleanLang(__("lang.share_media")) }}',
            size: 'medium'
        });
    }

    function deleteMedia(mediaId) {
        if (confirm('{{ cleanLang(__("lang.are_you_sure")) }}')) {
            $.ajax({
                url: '/whatsapp/media/' + mediaId,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('[data-id="' + mediaId + '"]').fadeOut(300, function() {
                            $(this).remove();
                        });
                        NX.notification({
                            type: 'success',
                            message: '{{ cleanLang(__("lang.media_deleted")) }}'
                        });
                    }
                }
            });
        }
    }

    function loadMoreMedia() {
        currentPage++;
        $.ajax({
            url: '/whatsapp/media',
            method: 'GET',
            data: { page: currentPage, filter: currentFilter },
            success: function(response) {
                $('#media-gallery-grid').append(response.html);
            }
        });
    }
</script>
<\!--Styles-->
<link href="/public/css/whatsapp/components.css?v={{ config('system.versioning') }}" rel="stylesheet">
