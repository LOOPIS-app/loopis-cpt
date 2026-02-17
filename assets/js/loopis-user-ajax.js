jQuery(function ($) {

    function renderResults($wrapper, users) {
        const $results = $wrapper.find('.loopis-user-results');
        $results.empty();

        if (!users.length) {
            $results.hide();
            return;
        }

        users.forEach(user => {
            $results.append(
                `<div class="loopis-user-item" data-id="${user.id}">${user.label}</div>`
            );
        });

        $results.show();
    }

    function selectSingle($wrapper, user) {
        const key = $wrapper.data('key');

        $wrapper.find('.loopis-user-selected').html(
            `<span class="loopis-user-chip" data-id="${user.id}">
                ${user.label}
                <button type="button">×</button>
                <input type="hidden" name="${key}" value="${user.id}">
            </span>`
        );
    }

    function selectMulti($wrapper, user) {
        const key = $wrapper.data('key');

        if ($wrapper.find('input[value="' + user.id + '"]').length) return;

        $wrapper.find('.loopis-user-selected').append(
            `<span class="loopis-user-chip" data-id="${user.id}">
                ${user.label}
                <button type="button">×</button>
                <input type="hidden" name="${key}[]" value="${user.id}">
            </span>`
        );
    }

    $(document).on('input', '.loopis-user-search', function () {

        const $wrapper = $(this).closest('.loopis-user-ajax');
        const query = $(this).val();

        if (query.length < 2) {
            $wrapper.find('.loopis-user-results').hide();
            return;
        }

        $.post(loopisUserAjax.ajax_url, {
            action: 'loopis_user_search',
            nonce: loopisUserAjax.nonce,
            q: query
        }, function (resp) {

            if (resp.success) {
                renderResults($wrapper, resp.data);
            }

        });

    });

    $(document).on('click', '.loopis-user-item', function () {

        const $wrapper = $(this).closest('.loopis-user-ajax');
        const mode = $wrapper.data('mode');

        const user = {
            id: $(this).data('id'),
            label: $(this).text()
        };

        if (mode === 'multi') {
            selectMulti($wrapper, user);
        } else {
            selectSingle($wrapper, user);
        }

        $wrapper.find('.loopis-user-search').val('');
        $wrapper.find('.loopis-user-results').hide().empty();

    });

    $(document).on('click', '.loopis-user-chip button', function () {

        const $wrapper = $(this).closest('.loopis-user-ajax');
        const mode = $wrapper.data('mode');

        if (mode === 'single') {
            $wrapper.find('.loopis-user-selected').empty();
        } else {
            $(this).closest('.loopis-user-chip').remove();
        }

    });

});
