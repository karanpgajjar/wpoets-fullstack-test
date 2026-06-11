$(function () {
    'use strict';

    const API = '../api/index.php';
    const sectionModal = new bootstrap.Modal('#sectionModal');
    const slideModal = new bootstrap.Modal('#slideModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');

    function loading(show) {
        $('#loadingOverlay').toggleClass('show', show);
    }

    function showErrors($container, errors) {
        $container.html(errors.map(e => `<div>${e}</div>`).join('')).removeClass('d-none');
    }

    function apiCall(method, url, data) {
        return $.ajax({
            url,
            method,
            contentType: 'application/json',
            data: data ? JSON.stringify(data) : undefined,
        });
    }

    function slugify(str) {
        return str.toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-');
    }

    $('#sectionLabel').on('input', function () {
        if (!$('#sectionId').val()) {
            $('#sectionSlug').val(slugify($(this).val()));
        }
    });

    $('#btnAddSection').on('click', function () {
        $('#sectionModalTitle').text('Add Section');
        $('#sectionId').val('');
        $('#sectionLabel, #sectionSlug, #sectionIconPath').val('');
        $('#sectionSortOrder').val(0);
        $('#sectionIsActive').val(1);
        $('#sectionErrors').addClass('d-none').empty();
        sectionModal.show();
    });

    $(document).on('click', '.btn-edit-section', function () {
        const id = $(this).data('id');
        loading(true);
        apiCall('GET', `${API}?resource=sections&id=${id}`)
            .done(function (res) {
                if (!res.success) { alert(res.message); return; }
                const s = res.data;
                $('#sectionModalTitle').text('Edit Section');
                $('#sectionId').val(s.id);
                $('#sectionLabel').val(s.label);
                $('#sectionSlug').val(s.slug);
                $('#sectionIconPath').val(s.icon_path);
                $('#sectionSortOrder').val(s.sort_order);
                $('#sectionIsActive').val(s.is_active);
                $('#sectionErrors').addClass('d-none').empty();
                sectionModal.show();
            })
            .fail(() => alert('Failed to load section.'))
            .always(() => loading(false));
    });

    $('#btnSaveSection').on('click', function () {
        const id = $('#sectionId').val();
        const payload = {
            label: $('#sectionLabel').val().trim(),
            slug: $('#sectionSlug').val().trim(),
            icon_path: $('#sectionIconPath').val().trim(),
            sort_order: parseInt($('#sectionSortOrder').val(), 10) || 0,
            is_active: parseInt($('#sectionIsActive').val(), 10),
        };
        $('#sectionErrors').addClass('d-none').empty();
        loading(true);

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API}?resource=sections&id=${id}` : `${API}?resource=sections`;

        apiCall(method, url, payload)
            .done(function (res) {
                if (!res.success) {
                    if (res.errors) showErrors($('#sectionErrors'), res.errors);
                    else alert(res.message);
                    loading(false);
                    return;
                }
                location.reload();
            })
            .fail(() => { alert('Save failed.'); loading(false); });
    });

    let pendingDeleteResource = null;
    let pendingDeleteId = null;

    $(document).on('click', '.btn-delete-section', function () {
        pendingDeleteResource = 'sections';
        pendingDeleteId = $(this).data('id');
        $('#deleteConfirmText').text(`Permanently delete section "${$(this).data('label')}"?`);
        deleteModal.show();
    });

    $('#btnAddSlide').on('click', function () {
        $('#slideModalTitle').text('Add Slide');
        $('#slideId').val('');
        $('#slideSectionId').val('');
        $('#slideCategory, #slideTitle, #slideImagePath').val('');
        $('#slideLinkUrl').val('#');
        $('#slideSortOrder').val(0);
        $('#slideIsActive').val(1);
        $('#slideErrors').addClass('d-none').empty();
        slideModal.show();
    });

    $(document).on('click', '.btn-edit-slide', function () {
        const id = $(this).data('id');
        loading(true);
        apiCall('GET', `${API}?resource=slides&id=${id}`)
            .done(function (res) {
                if (!res.success) { alert(res.message); return; }
                const sl = res.data;
                $('#slideModalTitle').text('Edit Slide');
                $('#slideId').val(sl.id);
                $('#slideSectionId').val(sl.section_id);
                $('#slideCategory').val(sl.category);
                $('#slideTitle').val(sl.title);
                $('#slideLinkUrl').val(sl.link_url);
                $('#slideImagePath').val(sl.image_path);
                $('#slideSortOrder').val(sl.sort_order);
                $('#slideIsActive').val(sl.is_active);
                $('#slideErrors').addClass('d-none').empty();
                slideModal.show();
            })
            .fail(() => alert('Failed to load slide.'))
            .always(() => loading(false));
    });


    $('#btnSaveSlide').on('click', function () {
        const id = $('#slideId').val();
        const payload = {
            section_id: parseInt($('#slideSectionId').val(), 10) || 0,
            category: $('#slideCategory').val().trim(),
            title: $('#slideTitle').val().trim(),
            link_url: $('#slideLinkUrl').val().trim() || '#',
            image_path: $('#slideImagePath').val().trim(),
            sort_order: parseInt($('#slideSortOrder').val(), 10) || 0,
            is_active: parseInt($('#slideIsActive').val(), 10),
        };
        $('#slideErrors').addClass('d-none').empty();
        loading(true);

        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API}?resource=slides&id=${id}` : `${API}?resource=slides`;

        apiCall(method, url, payload)
            .done(function (res) {
                if (!res.success) {
                    if (res.errors) showErrors($('#slideErrors'), res.errors);
                    else alert(res.message);
                    loading(false);
                    return;
                }
                location.reload();
            })
            .fail(() => { alert('Save failed.'); loading(false); });
    });

    $(document).on('click', '.btn-delete-slide', function () {
        pendingDeleteResource = 'slides';
        pendingDeleteId = $(this).data('id');
        const title = $(this).data('title') || 'this slide';
        $('#deleteConfirmText').text(`Permanently delete slide "${title.substring(0, 60)}…"?`);
        deleteModal.show();
    });

    $('#btnConfirmDelete').on('click', function () {
        if (!pendingDeleteResource || !pendingDeleteId) return;

        const url = `${API}?resource=${pendingDeleteResource}&id=${pendingDeleteId}`;
        loading(true);

        apiCall('DELETE', url)
            .done(function (res) {
                if (res.success) {
                    location.reload();
                } else {
                    deleteModal.hide();
                    alert(res.message);
                    loading(false);
                }
            })
            .fail(() => { deleteModal.hide(); alert('Delete failed.'); loading(false); })
            .always(() => { pendingDeleteResource = null; pendingDeleteId = null; });
    });

});
