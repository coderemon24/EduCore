/**
 * Academic Dependent Dropdowns
 * Dynamically loads sections and subjects when an Academic Class is selected.
 * Compatible with Vanilla JS and jQuery Select2 components.
 */
(function() {
    'use strict';

    function initAcademicDependentDropdowns() {
        const classSelects = document.querySelectorAll(
            '#academic_class_id, select[data-academic-class-select], select[name="academic_class_id"]'
        );

        if (!classSelects.length) {
            return;
        }

        classSelects.forEach(function(classSelect) {
            // Avoid multiple bindings on the same element
            if (classSelect.dataset.dependentBound === 'true') {
                return;
            }
            classSelect.dataset.dependentBound = 'true';

            // Find matching form or search document for section target
            const form = classSelect.closest('form') || document;
            const sectionSelect = form.querySelector('#section_id, select[data-dependent-sections], select[name="section_id"]');
            const subjectSelect = form.querySelector('#subject_ids, select[data-dependent-subjects], select[name="subject_id"]');

            function loadSections(classId, preserveSelectedId) {
                if (!sectionSelect) return;

                if (!classId || classId === 'all') {
                    sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
                    if (window.jQuery && typeof window.jQuery(sectionSelect).select2 === 'function') {
                        window.jQuery(sectionSelect).trigger('change.select2');
                    }
                    return;
                }

                // Show loading state
                sectionSelect.disabled = true;
                const originalHtml = sectionSelect.innerHTML;
                sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
                if (window.jQuery && typeof window.jQuery(sectionSelect).select2 === 'function') {
                    window.jQuery(sectionSelect).trigger('change.select2');
                }

                const endpoint = `/admin/academic/classes/${classId}/sections`;

                fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(sections => {
                    sectionSelect.disabled = false;
                    let html = '<option value="">-- Select Section --</option>';

                    if (Array.isArray(sections) && sections.length > 0) {
                        sections.forEach(function(sec) {
                            const isSelected = preserveSelectedId && String(preserveSelectedId) === String(sec.id);
                            html += `<option value="${sec.id}" ${isSelected ? 'selected' : ''}>${sec.name}</option>`;
                        });
                    } else {
                        html = '<option value="">No sections found for this class</option>';
                    }

                    sectionSelect.innerHTML = html;

                    if (window.jQuery && typeof window.jQuery(sectionSelect).select2 === 'function') {
                        window.jQuery(sectionSelect).trigger('change.select2');
                    }
                })
                .catch(error => {
                    console.error('Failed to load academic sections:', error);
                    sectionSelect.disabled = false;
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                    if (window.jQuery && typeof window.jQuery(sectionSelect).select2 === 'function') {
                        window.jQuery(sectionSelect).trigger('change.select2');
                    }
                });
            }

            // Bind change listener
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                const selectedSection = sectionSelect ? (sectionSelect.dataset.selected || '') : '';
                loadSections(classId, selectedSection);
            });

            // If class is already selected on page load (e.g. edit form or validation error return)
            if (classSelect.value) {
                const selectedSection = sectionSelect ? (sectionSelect.dataset.selected || sectionSelect.value || '') : '';
                // Only load if section dropdown is empty or only has 1 empty option
                if (sectionSelect && sectionSelect.options.length <= 1) {
                    loadSections(classSelect.value, selectedSection);
                }
            }
        });
    }

    // Initialize on DOMContentLoaded and expose helper
    window.initAcademicDependentDropdowns = initAcademicDependentDropdowns;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAcademicDependentDropdowns);
    } else {
        initAcademicDependentDropdowns();
    }
})();
