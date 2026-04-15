document.addEventListener('DOMContentLoaded', function () {
    const adminButton = document.querySelector('.adminLoginButton');
    const loginDialog = document.querySelector('.loginDialog');
    const closeModal = document.querySelector('.closeModal');

    if (adminButton && loginDialog) {
        adminButton.addEventListener('click', function () {
            loginDialog.showModal();
        });
    }

    if (closeModal && loginDialog) {
        closeModal.addEventListener('click', function () {
            loginDialog.close();
        });
    }

    // Add Record Dialog

    const addRecordButton = document.querySelector('.addRecordButton');
    const addRecordDialog = document.querySelector('.addRecordDialog');

    if (addRecordButton && addRecordDialog) {
        addRecordButton.addEventListener('click', function () {
            addRecordDialog.showModal();
        });
    }

    const closeAddRecordModal = document.querySelector('.closeAddRecordModal');

    if (closeAddRecordModal && addRecordDialog) {
        closeAddRecordModal.addEventListener('click', function () {
            addRecordDialog.close();
        });
    }

    // Edit Record Dialog

    document.querySelectorAll('.editButton').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const farmerName = this.getAttribute('data-farmeName');
            const address = this.getAttribute('data-address');
            const program = this.getAttribute('data-program');
            const line = this.getAttribute('data-line');
            const causeOfDamage = this.getAttribute('data-causeOfDamage');
            const modeOfPayment = this.getAttribute('data-modeOfPayment');
            const remarks = this.getAttribute('data-remarks');

            // Populate the edit form with the existing data
            const editForm = document.querySelector('.editRecordform');
            if (editForm) {
                editForm.action = `/records/${id}`;
                console.log('Form action set to:', editForm.action);
            }

            const farmerNameInput = document.querySelector('.editRecordform input[name="farmerName"]');
            if (farmerNameInput) farmerNameInput.value = farmerName;

            const addressInput = document.querySelector('.editRecordform input[name="address"]');
            if (addressInput) addressInput.value = address;

            const programSelect = document.querySelector('.editRecordform select[name="program"]');
            if (programSelect) programSelect.value = program;

            const lineSelect = document.querySelector('.editRecordform select[name="line"]');
            if (lineSelect) lineSelect.value = line;

            const causeInput = document.querySelector('.editRecordform input[name="causeOfDamage"]');
            if (causeInput) causeInput.value = causeOfDamage;

            const modeOfPaymentSelect = document.querySelector('.editRecordform select[name="modeOfPayment"]');
            if (modeOfPaymentSelect) modeOfPaymentSelect.value = modeOfPayment;

            const remarksInput = document.querySelector('.editRecordform input[name="remarks"]');
            if (remarksInput) remarksInput.value = remarks;

            const editDialog = document.querySelector('.editRecordDialog');
            if (editDialog) {
                editDialog.showModal();
            }
        });
    });

    // Delete Record Dialog

    document.querySelectorAll('.deleteButton').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const farmerName = this.getAttribute('data-farmer-name');
            const deleteForm = document.querySelector('.deleteRecordForm');
            const message = document.querySelector('.deleteRecordMessage');
            const deleteDialog = document.querySelector('.deleteRecordDialog');

            if (deleteForm) {
                deleteForm.action = `/records/${id}`;
            }

            if (message) {
                message.textContent = `Record for ${farmerName} will be deleted.`;
            }

            if (deleteDialog) {
                deleteDialog.showModal();
            }
        });
    });

    const closeEditRecordDialog = document.querySelector('.closeEditRecordDialog');
    const editRecordDialog = document.querySelector('.editRecordDialog');

    if (closeEditRecordDialog && editRecordDialog) {
        closeEditRecordDialog.addEventListener('click', function () {
            editRecordDialog.close();
        });
    }

    // Add form submit listener for debugging
    const editForm = document.querySelector('.editRecordform');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            console.log('Form submitted with action:', this.action);
            console.log('Form data:', new FormData(this));
        });
    }

    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const recordCheckboxes = document.querySelectorAll('.record-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            recordCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk Delete Dialog
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkDeleteDialog = document.querySelector('.bulkDeleteDialog');
    const bulkDeleteList = document.querySelector('.bulk-delete-list');
    const confirmBulkDelete = document.getElementById('confirm-bulk-delete');
    const cancelBulkDelete = document.querySelector('.cancelBulkDelete');
    const bulkForm = document.getElementById('bulk-form');

    if (bulkDeleteBtn && bulkDeleteDialog) {
        bulkDeleteBtn.addEventListener('click', function () {
            const selectedCheckboxes = document.querySelectorAll('.record-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Please select at least one record to delete.');
                return;
            }

            // Clear previous list
            bulkDeleteList.innerHTML = '';

            // Add selected farmers to the list
            selectedCheckboxes.forEach(checkbox => {
                const farmerName = checkbox.dataset.farmerName || checkbox.closest('tr').cells[3].textContent;
                const li = document.createElement('li');
                li.textContent = farmerName;
                bulkDeleteList.appendChild(li);
            });

            bulkDeleteDialog.showModal();
        });
    }

    if (confirmBulkDelete && bulkForm) {
        confirmBulkDelete.addEventListener('click', function () {
            bulkDeleteDialog.close();
            bulkForm.submit();
        });
    }

    if (cancelBulkDelete && bulkDeleteDialog) {
        cancelBulkDelete.addEventListener('click', function () {
            bulkDeleteDialog.close();
        });
    }

    // Add Admin Dialog
    const addAdminButton = document.querySelector('.addAdminButton');
    const addAdminDialog = document.querySelector('.addAdminDialog');
    const closeAddAdminDialog = document.querySelector('.closeAddAdminDialog');

    if (addAdminButton && addAdminDialog) {
        addAdminButton.addEventListener('click', function () {
            addAdminDialog.showModal();
        });
    }

    if (closeAddAdminDialog && addAdminDialog) {
        closeAddAdminDialog.addEventListener('click', function () {
            addAdminDialog.close();
        });
    }

    // Edit Admin Dialog
    const editAdminButtons = document.querySelectorAll('.editAdminButton');
    const editAdminDialog = document.querySelector('.editAdminDialog');
    const closeEditAdminDialog = document.querySelector('.closeEditAdminDialog');
    const editAdminForm = document.querySelector('.editAdminForm');

    editAdminButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');

            if (editAdminForm) {
                editAdminForm.action = `/admin/users/${id}`;
            }

            const usernameInput = document.querySelector('#adminUsername');
            if (usernameInput) usernameInput.value = username;

            if (editAdminDialog) {
                editAdminDialog.showModal();
            }
        });
    });

    if (closeEditAdminDialog && editAdminDialog) {
        closeEditAdminDialog.addEventListener('click', function () {
            editAdminDialog.close();
        });
    }
});
