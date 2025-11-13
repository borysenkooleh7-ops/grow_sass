<!--Add Tags to Multiple Tickets Modal-->
<div class="row">
    <div class="col-lg-12">

        <!--tags-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.tags')) }}*</label>
            <div class="col-sm-12">
                <select class="select2-multiple-tags form-control form-control-sm" multiple="multiple" id="bulk_tags" name="bulk_tags[]">
                    @foreach($tags as $tag)
                    <option value="{{ $tag->tag_title }}">{{ $tag->tag_title }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">{{ cleanLang(__('lang.add_tags_help')) }}</small>
            </div>
        </div>

        <!--action type-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label">{{ cleanLang(__('lang.action')) }}</label>
            <div class="col-sm-12">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tag_action" id="tag_action_add" value="add" checked>
                    <label class="form-check-label" for="tag_action_add">
                        {{ cleanLang(__('lang.add_tags')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tag_action" id="tag_action_replace" value="replace">
                    <label class="form-check-label" for="tag_action_replace">
                        {{ cleanLang(__('lang.replace_existing_tags')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tag_action" id="tag_action_remove" value="remove">
                    <label class="form-check-label" for="tag_action_remove">
                        {{ cleanLang(__('lang.remove_tags')) }}
                    </label>
                </div>
            </div>
        </div>

        <!--selected count-->
        <div class="alert alert-info">
            <i class="ti-info-alt"></i> {{ cleanLang(__('lang.this_will_affect')) }}
            <strong><span id="selected-tickets-count">0</span></strong>
            {{ cleanLang(__('lang.tickets')) }}
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Count selected tickets
        const selectedCount = $('input[name^="ids["]:checked').length;
        $('#selected-tickets-count').text(selectedCount);
    });
</script>
