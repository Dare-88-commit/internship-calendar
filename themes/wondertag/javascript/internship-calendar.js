(function ($) {
    function ajaxParams(extra) {
        return Object.assign(
            {
                f: 'internship_calendar'
            },
            extra || {}
        );
    }

    function renderResults(response) {
        if (response && typeof response.html === 'string') {
            $('.js-intern-calendar-results').html(response.html);
        }
        if (response && typeof response.current_week !== 'undefined') {
            window.APP_CONTEXT.currentWeek = response.current_week;
        }
    }

    let debounceTimer = null;
    const $search = $('[data-intern-search]');
    const $week = $('[data-intern-week]');
    const $reset = $('[data-intern-reset]');
    const endpoint = window.APP_CONTEXT && window.APP_CONTEXT.ajax ? window.APP_CONTEXT.ajax : 'requests.php';

    function fetchEvents(params) {
        return $.getJSON(endpoint, ajaxParams(params)).done(renderResults);
    }

    $search.on('input', function () {
        const keyword = $(this).val();
        const week = $week.val();
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(function () {
            fetchEvents({
                s: 'search_events',
                keyword: keyword,
                week: week
            });
        }, 250);
    });

    $week.on('change', function () {
        fetchEvents({
            s: 'search_events',
            keyword: $search.val(),
            week: $(this).val()
        });
    });

    $reset.on('click', function () {
        $search.val('');
        $week.val('0');
        fetchEvents({
            s: 'search_events',
            keyword: '',
            week: 0
        });
    });

    $(document).on('click', '[data-intern-modal-close]', function () {
        $('.js-intern-modal-root').attr('hidden', true);
    });

    $(document).on('click', '[data-intern-delete]', function (event) {
        const id = $(this).data('internDelete');
        if (!window.confirm('Delete this event permanently?')) {
            event.preventDefault();
            return;
        }
        $.post(endpoint, ajaxParams({ s: 'delete_event', id: id }))
            .done(function () {
                fetchEvents({
                    s: 'search_events',
                    keyword: $search.val(),
                    week: $week.val()
                });
            });
        event.preventDefault();
    });

    document.querySelectorAll('[data-event-date]').forEach(function (el) {
        const today = window.APP_CONTEXT && window.APP_CONTEXT.today ? window.APP_CONTEXT.today : '';
        if (today && el.getAttribute('data-event-date') === today) {
            el.classList.add('today-highlight');
        }
    });
})(jQuery);

