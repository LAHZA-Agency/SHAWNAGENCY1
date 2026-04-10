import './bootstrap';
import 'table-sort-js/table-sort.js';
import Alpine from 'alpinejs';
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";
import Chart from 'chart.js/auto';
import Sortable from 'sortablejs';
import html2pdf from 'html2pdf.js';

window.Alpine = Alpine;
Alpine.start();


document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    const toast = document.querySelector('.toast');
    const session_alert = document.querySelector('.session-alert');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const load_toast = document.querySelector(".toast-load");
    let isPasswordRequired = false;
    forms.forEach(form => {

        if (form.classList.contains('v-form')) {
            return;
        }

        isPasswordRequired = form.classList.contains('required-password');
        const passwordInputs = form.querySelectorAll('input[name="password"]');
        const emailInputs = form.querySelectorAll('input[name="email"]');
        const usernameInputs = form.querySelectorAll('input[name="username"]');
        const telInputs = form.querySelectorAll('input[name="tel"]');
        const fileInputs = form.querySelectorAll('input[type="file"]');

        const allInputs = [...passwordInputs, ...emailInputs, ...usernameInputs, ...telInputs, ...fileInputs];

        allInputs.forEach(input => {
            input.addEventListener('input', () => {
                let errorSpan = null;
                if (input.hasAttribute('multiple')) {
                    errorSpan = input.closest('.upload-container').querySelector('.error-message');
                } else {
                    errorSpan = document.querySelector(`.${input.name}-error-message`);
                }
                input.classList.remove("!border-error", "focus:!ring-error");
                if (errorSpan) {
                    errorSpan.textContent = '';
                }
            });
        });

        form.addEventListener('submit', function (event) {

            if (!validateForm(form, passwordInputs, emailInputs, usernameInputs, telInputs, fileInputs)) {
                event.preventDefault();
                if (toast) {
                    showHideAlert(toast);
                }
            } else {
                load_toast.classList.remove("translate-y-[150%]");
            }

        });
    });

    function validateForm(form, passwordInputs, emailInputs, usernameInputs, telInputs, fileInputs) {
        let isValid = true;
        const allInputs = [...passwordInputs, ...emailInputs, ...usernameInputs, ...telInputs];

        allInputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
                showError(input);
            }
        });

        fileInputs.forEach(input => {
            if (input.hasAttribute('multiple')) {
                if (input.files.length > 0) {
                    const validFiles = validateAndFilterFiles(input);
                    if (validFiles.length !== input.files.length) {
                        const errorElement = input.closest('.upload-container').querySelector('.error-message');
                        errorElement.textContent = 'Certains fichiers invalides ont été supprimés.';
                        removeHiddenClass([errorElement]);
                    }
                }
            } else {
                if (!validateField(input)) {
                    isValid = false;
                    showError(input);
                }
            }
        });

        return isValid;
    }

    function validateAndFilterFiles(input) {
        const files = Array.from(input.files);
        const dataTransfer = new DataTransfer();

        files.forEach(file => {
            if (validateFile(file)) {
                dataTransfer.items.add(file);
            }
        });

        input.files = dataTransfer.files;
        return dataTransfer.files;
    }

    function validateField(input) {
        if (input.name === 'password') {
            if (!isPasswordRequired) return true;
            const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;
            return input.value && passwordPattern.test(input.value);
        }

        if (input.name === 'email') {
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return input.value && emailPattern.test(input.value);
        }

        if (input.name === 'username') {
            const usernamePattern = /^[a-zA-ZÀ-ÿ'-]+(?:[\s-'][a-zA-ZÀ-ÿ'-]+)*$/;
            return input.value && usernamePattern.test(input.value);
        }

        if (input.name === 'tel') {
            const telPattern = /^\+?[0-9]{10,15}$/;
            return input.value && telPattern.test(input.value);
        }

        if (input.type === 'file') {
            const file = input.files[0];
            const errorElement = input.closest('.upload-container').querySelector('.error-message');
            if (input.classList.contains("images-input")) {
                return file ? validateFile(file, errorElement) : true;
            } else {
                return file ? validateFile(file, errorElement) : false;
            }
        }

        return true;
    }

    function showError(input) {
        input.classList.add("!border-error", "focus:!ring-error");
        let errorSpan = input.parentElement.querySelector(`.${input.name}-error-message`);
        if (!errorSpan) {
            errorSpan = document.querySelector(`.${input.name}-error-message`);
        }
        if (errorSpan) {
            let errorMessage = '';
            switch (input.name) {
                case 'password':
                    errorMessage = 'Le mot de passe doit contenir au moins 8 caractères, y compris des majuscules, des minuscules, des chaînes de caractères et des chiffres.';
                    break;
                case 'email':
                    errorMessage = 'Veuillez saisir une adresse e-mail valide';
                    break;
                case 'username':
                    errorMessage = 'Le nom de membre ne peut contenir que des lettres, des chiffres et des traits de soulignement.';
                    break;
                case 'tel':
                    errorMessage = 'Veuillez saisir un numéro de téléphone valide (10 à 15 chiffres, avec ou sans +).';
                    break;
                case 'identity_document':
                    errorMessage = 'Assurez-vous d\'avoir téléchargé le fichier dans le bon format. ';
                    break;
                case 'profile':
                    errorMessage = 'Assurez-vous d\'avoir téléchargé le fichier dans le bon format. ';
                    break;
                case 'images':
                    errorMessage = 'Assurez-vous d\'avoir téléchargé le fichier dans le bon format. ';
                    break;
            }
            errorSpan.textContent = errorMessage;
            errorSpan.style.color = 'red';
            errorSpan.style.fontSize = '0.875rem';
            errorSpan.style.marginTop = '0.5rem';
        }
    }


    // show/hide toast if there is errors returned from backend
    if (session_alert) {
        showHideAlert(session_alert);
    }


    // trigger all dropdown trigger elements
    const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            const dropdownParent = this.closest('.dropdown');
            const dropdownContent = dropdownParent.querySelector('.dropdown-content');

            dropdownContent.classList.toggle('hidden');
        });
    });
    document.addEventListener('click', function (event) {
        const isClickInsideDropdown = Array.from(dropdownTriggers).some(trigger => trigger.contains(event.target));
        const isClickInsideDropdownContent = Array.from(document.querySelectorAll('.dropdown-content')).some(content => content.contains(event.target));
        if (!isClickInsideDropdown && !isClickInsideDropdownContent) {
            const openDropdowns = document.querySelectorAll('.dropdown-content');
            openDropdowns.forEach(content => content.classList.add('hidden'));
        }
    });

    // chage member status
    const checkboxes = document.querySelectorAll('input[name="memberStatus"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = this.value;
            const status = this.checked ? 'active' : 'inactive';
            fetch(`/member/update-status/${userId}/${status}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const sessionAlertSuccess = document.querySelector('.session-alert-success');
                        const messageContainer = document.querySelector('.session-alert-success .status-message');
                        messageContainer.textContent = data.success;
                        showHideAlert(sessionAlertSuccess);
                    } else if (data.error) {
                        const sessionAlertError = document.querySelector('.session-alert-error');
                        const messageContainer = document.querySelector('.session-alert-error .status-message');
                        messageContainer.textContent = data.error;
                        showHideAlert(sessionAlertError);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });

    // show/hide alerts
    function showHideAlert(alert) {
        alert.classList.remove('translate-y-[150%]');
        setTimeout(() => {
            alert.classList.add('translate-y-[150%]');
        }, 5000);
    }

    // Delete member
    const DeleteMBtns = document.querySelectorAll(".delete-member-btn");
    const alertDialog = document.querySelector(".alert-dialog");
    const alertDialogContent = document.querySelector(".alert-dialog-content");
    const closeDialogBtns = document.querySelectorAll(".close-dialog-btn");
    const deleteMemeberBtn = document.querySelector(".delete-action-btn");
    const deleteModelForm = document.querySelector(".delte-model-form");

    // ── Unauthorized modal pour members ──
    const unauthorizedModalMember = document.getElementById('unauthorized-modal-member');
    const unauthorizedContentMember = unauthorizedModalMember?.querySelector('.unauthorized-content-member');
    const membersDeleteModal = document.getElementById('members-delete-modal');
    const memberUserRole = membersDeleteModal?.dataset.userRole;

    function openUnauthorizedModalMember() {
        if (!unauthorizedModalMember) return;
        unauthorizedModalMember.classList.remove('hidden');
        unauthorizedModalMember.classList.add('flex');
        setTimeout(() => unauthorizedContentMember.classList.remove('opacity-0', 'translate-y-20'), 10);
    }

    function closeUnauthorizedModalMember() {
        if (!unauthorizedModalMember) return;
        unauthorizedContentMember.classList.add('opacity-0', 'translate-y-20');
        setTimeout(() => {
            unauthorizedModalMember.classList.add('hidden');
            unauthorizedModalMember.classList.remove('flex');
        }, 300);
    }

    unauthorizedModalMember?.querySelectorAll('.close-unauthorized-member').forEach(btn => {
        btn.addEventListener('click', closeUnauthorizedModalMember);
    });

    DeleteMBtns.forEach((button) => {
        button.addEventListener('click', () => {
            const memberId = button.dataset.id;
            const type = button.dataset.type;
            deleteMemeberBtn.dataset.id = memberId;
            removeHiddenClass([alertDialog]);
            alertDialog.classList.add("flex");
            document.body.classList.add("overflow-hidden");
            setTimeout(() => {
                alertDialogContent.classList.remove("opacity-0", "translate-y-20");
                alertDialogContent.classList.add("opacity-100", "translate-y-0");
            }, 30);
            if (type != 'member') {
                deleteMemeberBtn.classList.add('hidden');
                deleteModelForm.action = `/mannequin/${memberId}`
            }
        });
    });

    if (alertDialog) {
        alertDialog.addEventListener('click', (event) => {
            if (event.target === alertDialog) {
                closeModal();
            }
        });
    }
    closeDialogBtns.forEach((button) => {
        button.addEventListener('click', () => {
            closeModal();
        });
    });
    function closeModal() {
        alertDialogContent.classList.remove("opacity-100", "translate-y-0");
        alertDialogContent.classList.add("opacity-0", "translate-y-20");
        setTimeout(() => {
            addHiddenClass([alertDialog]);
            alertDialog.classList.remove("flex");
            document.body.classList.remove("overflow-hidden");
        }, 500);
    }

    if (deleteMemeberBtn) {
        deleteMemeberBtn.addEventListener('click', () => {

            // ── Bloquer bookeuse ──
            if (memberUserRole === 'bookeuse') {
                closeModal();
                setTimeout(() => {
                    openUnauthorizedModalMember();
                }, 500);
                return;
            }

            const loadingIcon = deleteMemeberBtn.querySelector(".loading");
            const trashIcon = deleteMemeberBtn.querySelector(".trash");
            const btnContent = deleteMemeberBtn.querySelector(".content");
            removeHiddenClass([loadingIcon]);
            addHiddenClass([trashIcon]);
            btnContent.textContent = "Chargement";
            const id = deleteMemeberBtn.dataset.id;
            fetch(`/member/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const m_row = document.querySelector(`tr[data-id="${id}"]`);
                        if (m_row) m_row.remove();
                        closeModal();
                        const sessionAlertSuccess = document.querySelector('.session-alert-success');
                        const messageContainer = document.querySelector('.session-alert-success .status-message');
                        messageContainer.textContent = data.success;
                        showHideAlert(sessionAlertSuccess);
                    } else if (data.error) {
                        closeModal();
                        const sessionAlertError = document.querySelector('.session-alert-error');
                        const messageContainer = document.querySelector('.session-alert-error .status-message');
                        messageContainer.textContent = data.error;
                        showHideAlert(sessionAlertError);
                    }
                })
                .catch(error => {
                    location.reload();
                    console.error('Error:', error);
                    closeModal();
                })
                .finally(() => {
                    removeHiddenClass([trashIcon]);
                    addHiddenClass([loadingIcon]);
                    btnContent.textContent = "Oui Supprimer";
                });
        });
    }

    // Dark Mode toggle switch
    if (localStorage.getItem('dark-mode') === 'true') {
        document.documentElement.classList.add('dark');
    }
    const lightSwitches = document.querySelectorAll('.light-switch');
    if (lightSwitches.length > 0) {
        lightSwitches.forEach((lightSwitch, i) => {
            if (localStorage.getItem('dark-mode') === 'true') {
                lightSwitch.checked = true;
            }
            lightSwitch.addEventListener('change', () => {
                const { checked } = lightSwitch;
                lightSwitches.forEach((el, n) => {
                    if (n !== i) {
                        el.checked = checked;
                    }
                });
                if (lightSwitch.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('dark-mode', true);
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('dark-mode', false);
                }
            });
        });
    }

    const MAX_FILE_SIZE = 1.5 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/tiff', 'image/raw', 'image/dng', 'application/pdf'];
    document.querySelectorAll('input[type="file"]').forEach(input => {
        if (!input.classList.contains('v-form-input')) {
            setupFilePreview(input);
        }
    });

    function setupFilePreview(input) {
        let allFilesArray = [];

        input.addEventListener('change', function (e) {
            const container = this.closest('.upload-container');
            const errorMessage = container.querySelector('.error-message');
            const imagesGrid = container.querySelector('.images-grid');
            const uploadWidget = container.querySelector('.upload-widget');
            const noFilesSelectedWidget = uploadWidget.querySelector('.no-files-selected-widget');
            const filesSelectedWidget = uploadWidget.querySelector('.files-selected-widget');
            const submitImagesBtn = document.querySelector('.upload-images-btn');

            addHiddenClass([errorMessage]);

            if (this.hasAttribute('multiple')) {
                const newFilesArray = Array.from(this.files);
                const validFiles = newFilesArray.filter(file => validateFile(file, errorMessage));

                if (validFiles.length === 0) {
                    input.value = '';
                    return;
                }

                allFilesArray = allFilesArray.concat(validFiles);
                updateInputFiles(input, allFilesArray);
                refreshImageGrid();
            } else {
                handleSingleFileUpload();
            }

            function refreshImageGrid() {
                imagesGrid.innerHTML = '';

                allFilesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const imageContainer = createImageContainer(e.target.result, index);
                        imagesGrid.appendChild(imageContainer);
                    };
                    reader.readAsDataURL(file);
                });

                updateUIState();
            }

            function createImageContainer(imageSrc, index) {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'relative group rounded-lg min-h-32 min-w-32 aspect-square overflow-hidden';
                imageContainer.dataset.fileIndex = index;

                imageContainer.innerHTML = `
                    <div class="absolute inset-0 min-h-32 rounded-lg aspect-square">
                        <img src="${imageSrc}" alt="Preview" class="w-full h-full object-cover"/>
                        <div class="absolute inset-0 bg-secondary/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" class="delete-image">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-primary-light">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;

                const deleteButton = imageContainer.querySelector('.delete-image');
                deleteButton.addEventListener('click', () => {
                    allFilesArray.splice(index, 1);
                    updateInputFiles(input, allFilesArray);
                    refreshImageGrid();
                });

                return imageContainer;
            }

            function updateInputFiles(input, filesArray) {
                const dataTransfer = new DataTransfer();
                filesArray.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
            }

            function updateUIState() {
                if (allFilesArray.length > 0) {
                    addHiddenClass([noFilesSelectedWidget]);
                    removeHiddenClass([filesSelectedWidget]);
                    filesSelectedWidget.classList.add('flex');
                    uploadWidget.classList.add('w-fit', 'add-more', 'min-h-32', 'min-w-32');
                    uploadWidget.classList.remove('w-full');
                    if (submitImagesBtn) {
                        removeHiddenClass([submitImagesBtn]);
                    }
                } else {
                    addHiddenClass([filesSelectedWidget]);
                    removeHiddenClass([noFilesSelectedWidget]);
                    uploadWidget.classList.remove('add-more', 'min-h-32', 'min-w-32');
                    uploadWidget.classList.add('w-full');
                    if (submitImagesBtn) {
                        addHiddenClass([submitImagesBtn]);
                    }
                }
            }

            function handleSingleFileUpload() {
                let previewArea = container.querySelector('.preview-area');
                const file = input.files[0];
                if (!file) return;

                if (!validateFile(file, errorMessage)) {
                    return;
                }

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewArea.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-full inset absolute object-cover"/>
                            <span class="w-full inset absolute items-center p-2 justify-center bg-main/50 backdrop-blur flex opacity-0 group-hover:opacity-100 z-10 h-full transition-all duration-200">
                                <p class="text-primary-light text-center">${file.name}</p>
                            </span>`;
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    previewArea.innerHTML = `
                        <div class="w-full inset absolute items-center p-2 justify-center h-full flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-20 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="w-full inset absolute items-center p-2 justify-center bg-main/50 backdrop-blur flex opacity-0 group-hover:opacity-100 z-10 h-full transition-all duration-200">
                            <p class="text-primary-light text-center">${file.name}</p>
                        </span>`;
                    previewArea.classList.add('min-h-40');
                }
            }
        });
    }

    function validateFile(file, errorElement) {
        if (file.size > MAX_FILE_SIZE) {
            if (errorElement) {
                errorElement.textContent = 'La taille du fichier doit être inférieure à 1.5 Mo';
                removeHiddenClass([errorElement]);
            }
            return false;
        }

        if (!ALLOWED_TYPES.includes(file.type)) {
            if (errorElement) {
                errorElement.textContent = 'Seuls les fichiers PNG, JPG, TIFF, RAW, DNG, WEBP sont autorisés. Les fichiers PDF sont autorisés uniquement pour les documents d\'identité. Les fichiers incorrects ont été supprimés.';
                removeHiddenClass([errorElement]);
            }
            return false;
        }

        return true;
    }

    function addHiddenClass(elements) {
        elements.forEach((el) => {
            el.classList.add('hidden');
        });
    }

    function removeHiddenClass(elements) {
        elements.forEach((el) => {
            el.classList.remove('hidden');
        });
    }


    // show/hide password
    const togglePssButtons = document.querySelectorAll('.password-toggle');
    togglePssButtons.forEach(button => {
        button.addEventListener('click', function () {
            const inputId = this.getAttribute('data-password-input');
            const passwordInput = document.getElementById(inputId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                addHiddenClass([this.querySelector('.eye-icon.show')]);
                removeHiddenClass([this.querySelector('.eye-icon.hide')]);
            } else {
                passwordInput.type = 'password';
                removeHiddenClass([this.querySelector('.eye-icon.show')]);
                addHiddenClass([this.querySelector('.eye-icon.hide')]);
            }
        });
    });


    // Tab switching functionality
    const tabs = document.querySelectorAll('.tab');
    const tabsTriggers = document.querySelectorAll('.tabTrigger');

    tabsTriggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
            tabsTriggers.forEach(t => {
                t.classList.remove('border-secondary');
                t.classList.add('border-transparent');
                t.classList.remove('text-secondary');
                t.classList.add('text-secondary-light/70');
            });

            this.classList.add('border-secondary/70');
            this.classList.remove('border-transparent');
            this.classList.add('text-secondary');
            this.classList.remove('text-secondary-light/70');

            // Show target tab
            const target = this.getAttribute('data-target');
            tabs.forEach(tab => {
                if (tab.id === target) {
                    tab.classList.remove('hidden');
                    if (tab.id == "tab4") {
                        tab.classList.add('flex');
                    }
                } else {
                    tab.classList.add('hidden');
                    if (tab.id == "tab4") {
                        tab.classList.remove('flex');
                    }
                }
            });
        });
    });



    // toggle sidbar
    const sideText = document.querySelector('.sideText');
    const sideTextParent = document.querySelector('.sideTextParent');
    const otherContent = document.querySelector('.otherContent');
    const expandBtn = document.getElementById('expandMenu');
    const collapseBtn = document.getElementById('collapseMenu');
    const dashgrid = document.querySelector('.dashgrid');
    if (expandBtn && collapseBtn) {
        expandBtn.addEventListener('click', function () {
            sideText.classList.remove('-translate-x-[150%]', 'w-0', 'opacity-0');
            sideText.classList.add('translate-x-0', 'w-64', 'opacity-100');

            sideTextParent.classList.remove('w-[3.2rem]');
            otherContent.classList.remove('w-full');
            expandBtn.classList.add('hidden');
            collapseBtn.classList.remove('hidden');

            dashgrid.classList.remove('flex');
            dashgrid.classList.add('xl:grid');

        });
        collapseBtn.addEventListener('click', function () {
            dashgrid.classList.add('flex');
            dashgrid.classList.remove('xl:grid');
            sideText.classList.remove('translate-x-0', 'w-64', 'opacity-100');
            sideText.classList.add('-translate-x-[150%]', 'w-0', 'opacity-0');

            sideTextParent.classList.add('w-[3.2rem]');
            otherContent.classList.add('w-full');
            expandBtn.classList.remove('hidden');
            collapseBtn.classList.add('hidden');
        });
    }

    document.querySelectorAll('.clear-comment-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const textarea = this.closest('.comment').querySelector('.comment-textarea');
            textarea.value = '';
        });
    });


    // toggle  replay to comments widget
    const replayTriggers = document.querySelectorAll('.reply-trigger');
    replayTriggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
            toggleReplyWidget(this);
        });
    });
    function toggleReplyWidget(trigger) {
        const dataKey = trigger.dataset.key;
        const replyWidget = document.querySelector(`.reply-widget[data-key="${dataKey}"]`);
        replyWidget.classList.toggle('hidden');
        setTimeout(() => {
            replyWidget.classList.toggle('opacity-0');
            replyWidget.classList.toggle('translate-y-[-10px]');
        }, 50);
    }

    function createDate() {
        const now = new Date();
        return `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
    }

    function showToast(type, message) {
        const toast = document.querySelector(`.${type}-toast`);
        const messageContainer = toast.querySelector('.status-message');
        messageContainer.textContent = message;
        showHideAlert(toast);
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    // Add comment
    document.querySelectorAll('.add-comment-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const commentSection = this.closest('.comment');
            const textarea = commentSection.querySelector('.comment-textarea');
            const content = textarea.value.trim();

            if (!content) {
                alert('Please enter a comment');
                return;
            }

            try {
                const response = await fetch('/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        candidate_id: textarea.dataset.id,
                        comment_content: content
                    })
                });

                const result = await response.json();

                if (result.success) {
                    textarea.value = '';
                    const formattedDate = createDate();
                    const commentsSection = document.querySelector('.comment-content');
                    const commentsSectionPlaceholder = document.querySelector('.comment-content .placeholder');

                    const newComment = `
                    <div class="flex justify-end">
                        <span class="text-secondary-light/50 text-sm">${formattedDate}</span>
                    </div>
                    <p class="text-secondary-light mt-1">${content}</p>
                `;

                    if (commentsSectionPlaceholder) {
                        commentsSection.innerHTML = newComment;
                    } else {
                        commentsSection.insertAdjacentHTML('afterbegin', `
                            ${newComment}
                        <hr class="border-c-border mb-2 mt-6 w-1/6">
                    `);
                    }

                    showToast('success', result.message);
                } else {
                    showToast('error', `Error adding comment: ${result.message}`);
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Error adding comment: Network error');
            }
        });
    });

    // Reply to comment
    document.querySelectorAll('.reply-widget').forEach(widget => {
        const clearBtn = widget.querySelector('.clear-reply-btn');
        const addBtn = widget.querySelector('.add-reply-btn');
        const textarea = widget.querySelector('textarea');
        const commentId = widget.dataset.commentId;

        clearBtn?.addEventListener('click', () => {
            textarea.value = '';
        });

        addBtn?.addEventListener('click', async () => {
            const content = textarea.value.trim();
            if (!content) return;

            try {
                load_toast.classList.remove("translate-y-[150%]");
                const response = await fetch('/admin-reply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        comment_id: commentId,
                        reply_content: content
                    })
                });

                const result = await response.json();

                if (result.success) {
                    load_toast.classList.add("translate-y-[150%]");
                    const replyContainer = document.querySelector(`.comment-replay-cotainer[data-comment-cont-id="${commentId}"]`);
                    const replytrigger = document.querySelector(`.reply-trigger[data-key="${commentId}"]`);
                    const formattedDate = createDate();
                    const newReply = `
                    <div class="replay-container group">
                        <div class="bg-primary p-4 pl-6 rounded-t-full rounded-l-full mt-2 text-start">
                            ${content}
                        </div>
                        <span class='text-sm text-primary-dark pl-4'>${formattedDate}</span>
                    </div>
                `;

                    replyContainer.insertAdjacentHTML('beforeend', newReply);
                    textarea.value = '';
                    toggleReplyWidget(replytrigger);
                } else {
                    load_toast.classList.add("translate-y-[150%]");
                    showToast('error', `Error adding reply: ${result.message}`);
                }
            } catch (error) {
                console.error('Error:', error);
                load_toast.classList.add("translate-y-[150%]");
                showToast('error', 'Error adding reply: Network error');
            }
        });
    });


    // image gallery
    Fancybox.bind("[data-fancybox]", {
        Toolbar: {
            display: {
                left: [],
                middle: [],
                right: ["fullscreen", "close"]
            }
        },
        Navigation: {
            prevTpl: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
            nextTpl: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        },
        contentClick: false,
        wheel: false,
        Images: {
            Panzoom: {
                // zoom: false,
                maxScale: 1,
                // pinchToZoom: false
            }
        }
    });

    // measurements inputs
    const inputs = document.querySelectorAll('.measurement-input');
    inputs.forEach(input => {
        const decreaseBtn = input.parentElement.querySelector('.decrease-btn');
        const increaseBtn = input.parentElement.querySelector('.increase-btn');

        decreaseBtn.addEventListener('click', () => {
            const currentValue = parseFloat(input.value) || 0;
            input.value = Math.max(0, (currentValue - 0.1)).toFixed(1);
        });

        increaseBtn.addEventListener('click', () => {
            const currentValue = parseFloat(input.value) || 0;
            input.value = (currentValue + 0.1).toFixed(1);
        });

        input.addEventListener('input', () => {
            if (input.value < 0) input.value = 0;
        });
    });

    // change model status
    const change_model_statusBtns = document.querySelectorAll(".change-model-status");
    const alertDialogMS = document.querySelector(".alertDialogMS");
    const alertDialogContentMS = document.querySelector(".alertDialogContentMS");
    const closeDialogBtnsMS = document.querySelectorAll(".closeDialogBtnsMS");
    const modelStatusForm = document.querySelector(".alertDialogMS form");

    change_model_statusBtns.forEach((button) => {
        button.addEventListener('click', () => {
            const modelId = button.dataset.id;
            const modelSatus = button.dataset.status;
            const verifiedInputs = Array.from(modelStatusForm.querySelectorAll('input[name="verified"]'));
            verifiedInputs.forEach((input) => {
                if (input.value == modelSatus) {
                    input.parentElement.classList.add('hidden');
                }
            });
            modelStatusForm.action = modelStatusForm.action.replace(/\/\d+$/, `/${modelId}`);
            removeHiddenClass([alertDialogMS]);
            alertDialogMS.classList.add("flex");
            document.body.classList.add("overflow-hidden");
            setTimeout(() => {
                alertDialogContentMS.classList.remove("opacity-0", "translate-y-20");
                alertDialogContentMS.classList.add("opacity-100", "translate-y-0");
            }, 30);
        });
    });
    closeDialogBtnsMS.forEach((button) => {
        button.addEventListener('click', () => {
            closeModalMS();
        });
    });
    function closeModalMS() {
        alertDialogContentMS.classList.remove("opacity-100", "translate-y-0");
        alertDialogContentMS.classList.add("opacity-0", "translate-y-20");

        setTimeout(() => {
            addHiddenClass([alertDialogMS]);
            alertDialogMS.classList.remove("flex");
            document.body.classList.remove("overflow-hidden");
        }, 500);
    }
    if (alertDialogMS) {
        alertDialogMS.addEventListener('click', (event) => {
            if (event.target === alertDialogMS) {
                closeModalMS();
            }
        });
    }


    // Get all note input elements
    const exactNotes = document.querySelectorAll('.exact-note');
    const minInputs = document.querySelectorAll('.min-note');
    const maxInputs = document.querySelectorAll('.max-note');
    const plusBtns = document.querySelectorAll('.plus-btn');
    const minusBtns = document.querySelectorAll('.minus-btn');

    // Handle exact note inputs
    exactNotes.forEach(input => {
        input.addEventListener('change', function () {
            // Ensure value is between 0 and 20
            let value = parseFloat(this.value) || 0;
            value = Math.max(0, Math.min(20, value));
            this.value = value;

            // Update display
            const displayElement = this.closest('.note-container').querySelector('.note-display');
            if (displayElement) {
                displayElement.textContent = `${value}/20`;
            }
        });
    });

    // Handle plus buttons
    plusBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.closest('.input-group').querySelector('input');
            let value = parseFloat(input.value) || 0;
            if (value < 20) {
                input.value = value + 1;
                input.dispatchEvent(new Event('input'));
            }
        });
    });

    // Handle minus buttons
    minusBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.closest('.input-group').querySelector('input');
            let value = parseFloat(input.value) || 0;
            if (value > 0) {
                input.value = value - 1;
                input.dispatchEvent(new Event('input'));
            }
        });
    });

    // Handle min/max inputs directly
    [...minInputs, ...maxInputs].forEach(input => {
        input.addEventListener('input', function () {
            let value = parseFloat(this.value) || 0;
            value = Math.max(0, Math.min(20, value));
            this.value = value;
        });
    });


    const modelsChart = document.getElementById('modelsGrowthChart');

    if (modelsChart) {
        const ctx = modelsChart.getContext('2d')
        const thisMonthData = JSON.parse(modelsChart.dataset.thisMonth);
        const lastMonthData = JSON.parse(modelsChart.dataset.lastMonth);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'],
                datasets: [
                    {
                        label: 'Ce Mois',
                        data: thisMonthData,
                        borderColor: '#c979ad',
                        backgroundColor: 'rgba(201, 121, 173, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#c979ad',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Mois Dernier',
                        data: lastMonthData,
                        borderColor: '#2460b6',
                        backgroundColor: 'rgba(36, 96, 182, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2460b6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }


    const statsTrigger = document.querySelector('.statistiques-trigger');
    const statsContainer = document.querySelector('.statistiques-cont');

    if (statsTrigger) {
        statsTrigger.addEventListener('click', () => {
            statsContainer.classList.toggle('hidden');
        });
    }


    // remove images trigger
    const checkboxesImgs = document.querySelectorAll('.peer\\/img-select');
    const removeImagesButton = document.querySelector('.delete-selected-images-btn');

    function toggleRemoveButton() {
        const anyChecked = Array.from(checkboxesImgs).some(checkbox => checkbox.checked);

        if (anyChecked) {
            removeImagesButton.classList.remove('opacity-0', 'scale-0');
        } else {
            removeImagesButton.classList.add('opacity-0', 'scale-0');
        }
    }

    checkboxesImgs.forEach(checkbox => {
        checkbox.addEventListener('change', toggleRemoveButton);
    });

    // load images animation
    const imageContainers = document.querySelectorAll('.lazy-image-container');
    imageContainers.forEach(container => {
        const img = container.querySelector('.lazy-image');
        const loader = container.querySelector('.lazy-image-loader');
        if (img) {
            if (img.complete) {
                loader.classList.add('opacity-0');
                setTimeout(() => {
                    loader.classList.add('hidden');
                }, 300);
                img.classList.remove('opacity-0');
                img.classList.add('opacity-100');
            } else {
                img.addEventListener('load', function () {
                    loader.classList.add('opacity-0');
                    setTimeout(() => {
                        loader.classList.add('hidden');
                    }, 300);
                    img.classList.remove('opacity-0');
                    img.classList.add('opacity-100');
                });
            }
        }
    });


    // Remove actions
    const deleteConfirmEL = document.querySelector(".delete-confirm");
    const deleteConfirmELContent = document.querySelector(".delete-confirm-content");
    const closeDeleteConfirmBtn = document.querySelectorAll(".close-delete-confirm-btn");
    const deleteConfirmForm = document.querySelector(".delete-confirm-from");

    // close the confirmation modal
    function closeConfirmModal() {
        deleteConfirmELContent.classList.remove("opacity-100", "translate-y-0");
        deleteConfirmELContent.classList.add("opacity-0", "translate-y-20");

        setTimeout(() => {
            deleteConfirmEL.classList.add("hidden");
            deleteConfirmEL.classList.remove("flex");
            document.body.classList.remove("overflow-hidden");
        }, 500);
    }

    // show the confirmation modal
    function showConfirmModal() {
        deleteConfirmEL.classList.add("flex");
        deleteConfirmEL.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
        setTimeout(() => {
            deleteConfirmELContent.classList.remove("opacity-0", "translate-y-20");
            deleteConfirmELContent.classList.add("opacity-100", "translate-y-0");
        }, 30);
    }

    // Close the modal when the close button is clicked
    closeDeleteConfirmBtn.forEach((button) => {
        button.addEventListener('click', () => {
            closeConfirmModal();
        });
    });

    // Close the modal when clicking outside of it
    if (deleteConfirmEL) {
        deleteConfirmEL.addEventListener('click', (event) => {
            if (event.target === deleteConfirmEL) {
                closeConfirmModal();
            }
        });
    }

    function setDeleteAction(id, action) {
        switch (action) {
            case 'remove-note':
                deleteConfirmForm.action = `/tableau-de-bord/mannequin/model-rating/${id}`;
                break;
            case 'remove-contract':
                deleteConfirmForm.action = `/tableau-de-bord/mannequin/model-contract/${id}`;
                break;
            case 'remove-images':
                deleteConfirmForm.action = `/tableau-de-bord/mannequin/remove-images?ids=${id.join(',')}`;
                break;
        }
    }

    // Remove note
    const removeNoteButtons = document.querySelectorAll('.remove-rating');
    removeNoteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const ratingId = this.getAttribute('data-note-id');
            setDeleteAction(ratingId, 'remove-note');
            showConfirmModal();
        });
    });

    // Remove contact
    const removeContractButtons = document.querySelectorAll('.remove-contract');
    removeContractButtons.forEach(button => {
        button.addEventListener('click', function () {
            const contractId = this.getAttribute('data-contract-id');
            setDeleteAction(contractId, 'remove-contract');
            showConfirmModal();
        });
    });

    // Remove images
    const deleteButton = document.querySelector('.delete-selected-images-btn');
    if (deleteButton) {
        deleteButton.addEventListener('click', function () {
            const selectedImagesToRemove = document.querySelectorAll('input[name="model-imgs"]:checked');
            const selectedImageoRemoveIds = Array.from(selectedImagesToRemove).map(checkbox => checkbox.value);
            setDeleteAction(selectedImageoRemoveIds, 'remove-images');
            showConfirmModal();
        });
    }


    // validate form
    const form = document.querySelector('form.v-form');
    const profilePictureInput = document.getElementById('profile_picture');
    const identityDocumentInput = document.getElementById('identity_document');

    if (form) {
        const fields = [
            { id: 'username', pattern: /^[a-zA-ZÀ-ÿ'-]+(?:[\s-'][a-zA-ZÀ-ÿ'-]+)*$/, errorMessage: { required: 'Le nom d\'utilisateur est requis.', invalid: 'Le nom de membre ne peut contenir que des lettres et des espaces.' } },
            { id: 'email', pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, errorMessage: { required: 'L\'email est requis.', invalid: 'Veuillez saisir une adresse e-mail valide.' } },
            { id: 'tel', pattern: /^\+?[0-9]{10,15}$/, errorMessage: { required: 'Le numéro de téléphone est requis.', invalid: 'Veuillez saisir un numéro de téléphone valide (10 à 15 chiffres, avec ou sans +).' } }
        ];

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            let isValid = true;

            // Validate text fields
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    const value = input.value.trim();
                    const errorMessage = document.querySelector(`.${field.id}-error-message`);

                    if (!value) {
                        errorMessage.textContent = field.errorMessage.required;
                        input.classList.add('border-error');
                        isValid = false;
                        showHideAlert(toast);
                    } else if (field.pattern && !field.pattern.test(value)) {
                        errorMessage.textContent = field.errorMessage.invalid;
                        input.classList.add('border-error');
                        isValid = false;
                        showHideAlert(toast);
                    }
                }
            });

            // Validate password only if password field exists and has a value
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                const passwordValue = passwordInput.value.trim();
                const passwordErrorMessage = document.querySelector('.password-error-message');

                if (passwordValue !== '') {
                    const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;
                    if (!passwordPattern.test(passwordValue)) {
                        passwordErrorMessage.textContent = 'Le mot de passe doit contenir au moins 8 caractères, y compris des majuscules, des minuscules, des chiffres.';
                        passwordInput.classList.add('border-error');
                        isValid = false;
                        showHideAlert(toast);
                    }
                }
            }

            // Validate radio buttons if they exist
            const genderIdentity = document.querySelector('input[name="gender_identity"]:checked');
            const statusModel = document.querySelector('input[name="status_model"]:checked');

            if ((!genderIdentity && document.querySelector('input[name="gender_identity"]')) ||
                (!statusModel && document.querySelector('input[name="status_model"]'))) {
                isValid = false;
                showHideAlert(toast);
            }

            if (isValid) {
                this.submit();
            }

            // Add input event listeners to clear errors
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    input.addEventListener('input', () => {
                        input.classList.remove('border-error');
                        const errorMessage = document.querySelector(`.${field.id}-error-message`);
                        if (errorMessage) {
                            errorMessage.textContent = '';
                        }
                    });
                }
            });

            // Clear password error on input if password field exists
            if (passwordInput) {
                passwordInput.addEventListener('input', () => {
                    passwordInput.classList.remove('border-error');
                    const passwordErrorMessage = document.querySelector('.password-error-message');
                    if (passwordErrorMessage) {
                        passwordErrorMessage.textContent = '';
                    }
                });
            }
        });
    }

    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function () {
            previewImage(this, 'profile-p-img');
        });
    }

    if (identityDocumentInput) {
        identityDocumentInput.addEventListener('change', function () {
            previewImage(this, 'identity-document-preview');
        });
    }

    function previewImage(input, previewClass) {
        const file = input.files[0];
        const preview = document.querySelector(`.${previewClass}`);
        const errorMessage = document.querySelector(`.${input.id}-error-message`);

        const pdfPreview = input.id === 'identity_document' ? document.querySelector('.identity-document-preview-pdf') : null;
        const pdfPreviewCont = input.id === 'identity_document' ? document.querySelector('.identity_document_prev') : null;

        errorMessage.textContent = '';
        input.classList.remove('border-error');

        if (input.id === 'identity_document') {
            if (pdfPreviewCont) {
                addHiddenClass([pdfPreviewCont]);
            }
            if (pdfPreview) {
                addHiddenClass([pdfPreview]);
            }
            addHiddenClass([preview]);
        } else {
            addHiddenClass([preview]);
        }

        if (file) {
            const allowedTypes = input.id === 'profile_picture'
                ? ['image/jpeg', 'image/png', 'image/webp', 'image/tiff', 'image/raw', 'image/dng']
                : ['image/jpeg', 'image/png', 'image/webp', 'image/tiff', 'image/raw', 'image/dng', 'application/pdf'];

            if (!allowedTypes.includes(file.type)) {
                errorMessage.textContent = 'Type de fichier non autorisé.';
                input.classList.add('border-error');
                return;
            }

            if (file.size > 1.5 * 1024 * 1024) {
                errorMessage.textContent = 'La taille du fichier doit être inférieure à 1.5 Mo.';
                input.classList.add('border-error');
                return;
            }

            if (input.id === 'identity_document') {
                if (pdfPreviewCont) {
                    removeHiddenClass([pdfPreviewCont]);
                }

                if (file.type === 'application/pdf') {
                    if (pdfPreview) {
                        removeHiddenClass([pdfPreview]);
                        addHiddenClass([preview]);
                        const pdfTitleDiv = pdfPreview.querySelector('div');
                        if (pdfTitleDiv) {
                            pdfTitleDiv.textContent = file.name;
                        }
                    }
                } else if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        removeHiddenClass([preview]);
                        if (pdfPreview) {
                            addHiddenClass([pdfPreview]);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            }
            else {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    removeHiddenClass([preview]);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    const grid = document.getElementById('sortable-grid');

    if (grid) {
        new Sortable(grid, {
            animation: 150,
            ghostClass: 'bg-primary-light',
            onEnd: function (evt) {
                const itemOrder = Array.from(grid.children).map((item, index) => ({
                    id: item.dataset.id,
                    position: index
                }));
                updateImageOrder(itemOrder);
            }
        });
    }

    function updateImageOrder(order) {
        fetch('/admin/model/update-image-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ order: order })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showHideAlert(document.querySelector('.toast-success-order'));
                }
            })
            .catch((error) => {
                showHideAlert(document.querySelector('.toast-error-order'));
                console.error('Error updating order:', error);
            });
    }


    // Downlaod pdf
    const downloadButton = document.querySelector('.downloadPdfBtn');
    if (downloadButton) {
        downloadButton.addEventListener('click', function (event) {
            load_toast.classList.remove("translate-y-[150%]");
            event.preventDefault();
            const modelId = this.getAttribute('data-id');

            if (modelId) {
                fetch(`/model/${modelId}/download-pdf`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({ id: modelId })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.mannequin_candidate) {
                            generatePDFFromData(data);
                        } else {
                            console.error('Error: Invalid data received:', data);
                            load_toast.classList.add("translate-y-[150%]");
                        }
                    })
                    .catch((error) => {
                        load_toast.classList.add("translate-y-[150%]");
                        console.error('Error fetching data:', error)
                    });
            } else {
                console.error('Error: Button is missing data-id.');
                load_toast.classList.add("translate-y-[150%]");
            }
        });
    }

    function formatNumber(num) {
        // Convert to number with one decimal place
        const formatted = Number(Number(num).toFixed(1));
        // Convert back to string to return the exact format we want
        return formatted.toString();
    }

    function convertMeasurementsToUK(measurements, sexe) {
        const ukMeasurements = {};

        if (measurements) {
            // Height (cm to inches)
            if (measurements.total_height) {
                const heightInches = measurements.total_height / 2.54;
                ukMeasurements.height = heightInches.toFixed(2);
            } else {
                ukMeasurements.height = 'N/A';
            }

            // Weight (kg to lbs)
            if (measurements.poids) {
                const weightLbs = measurements.poids * 2.20462;
                ukMeasurements.weight = weightLbs.toFixed(2);
            } else {
                ukMeasurements.weight = 'N/A';
            }

            // Circumferences (cm to inches)
            if (measurements.chest_circumference) {
                const bustInches = measurements.chest_circumference / 2.54;
                ukMeasurements.bust = bustInches.toFixed(2);
            } else {
                ukMeasurements.bust = 'N/A';
            }
            if (measurements.waist_circumference) {
                const waistInches = measurements.waist_circumference / 2.54;
                ukMeasurements.waist = waistInches.toFixed(2);
            } else {
                ukMeasurements.waist = 'N/A';
            }
            if (measurements.hips_circumference) {
                const hipsInches = measurements.hips_circumference / 2.54;
                ukMeasurements.hips = hipsInches.toFixed(2);
            } else {
                ukMeasurements.hips = 'N/A';
            }


            // Shoe Size (EU to UK)
            if (measurements.pointure) {
                const euSize = measurements.pointure;
                let ukShoeSize;

                if (sexe === 'Homme') {
                    ukShoeSize = euSize - 34;
                } else if (sexe === 'Femme') {
                    ukShoeSize = euSize - 33;
                } else {
                    ukShoeSize = euSize - 33;
                }

                ukMeasurements.shoeSize = formatNumber(Math.round(ukShoeSize * 2) / 2);
            } else {
                ukMeasurements.shoeSize = 'N/A';
            }
        }
        return ukMeasurements;
    }


    function formatModelData(data) {
        const candidate = data.mannequin_candidate;
        const measurements = candidate.measurements && candidate.measurements[0] ? candidate.measurements[0] : {};
        const ukMeasurements = convertMeasurementsToUK(measurements, candidate.gender_identity);
        return {
            name: data.name || 'N/A',
            agencyName: "Shawn Agency | Agence de mannequinat et publicité",
            languages: candidate.langues_parlees ? candidate.langues_parlees.split(',').map(lang => lang.trim()) : ['N/A'],
            piercings: candidate.piercings ? 'Oui' : 'Aucun' || 'N/A',
            tattoos: candidate.tatouages ? 'Oui' : 'Aucun' || 'N/A',
            sport: candidate.sport_pratique ? 'Oui' : 'Aucun' || 'N/A',
            height: measurements.total_height || 'N/A',
            heightUs: ukMeasurements.height,
            weight: measurements.poids || 'N/A',
            weightUs: ukMeasurements.weight,
            bust: measurements.chest_circumference || 'N/A',
            bustUs: ukMeasurements.bust,
            waist: measurements.waist_circumference || 'N/A',
            waistUs: ukMeasurements.waist,
            hips: measurements.tour_de_hanches || 'N/A',
            hipsUs: ukMeasurements.hips,
            shoeSize: measurements.pointure || 'N/A',
            shoeSizeUs: ukMeasurements.shoeSize,
            eyeColor: candidate.couleur_yeux || 'N/A',
            hairColor: candidate.couleur_cheveux || 'N/A',
            agencyLogo: "/images/logo.png",
            mainPhoto: candidate.profile ? `/${candidate.profile}` : "/images/main.jpg",
            images: candidate.images ? candidate.images.sort((a, b) => a.position - b.position).slice(0, 3).map(img => `/storage/${img.image_url}`) : ['', ''],
            agencyAddress: "34 Avenue des Champs-Élysées, 75008 Paris",
            agencyContact: "contact@shawnagency.fr | +33 6 11 17 43 22",
            modelType: candidate.model_type || 'N/A',
            sexe: candidate.gender_identity || 'Homme'
        };
    }

    async function translate(text, targetLang) {
        const sourceLang = 'fr';
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=${sourceLang}&tl=${targetLang}&dt=t&q=${encodeURI(text)}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            if (data && data[0] && data[0][0] && data[0][0][0]) {
                return data[0][0][0];
            } else {
                return text;
            }
        } catch (error) {
            console.error('Translation error:', error);
            return text;
        }
    }

    async function generateModelCompCard(modelData) {
        const borderColor = modelData.modelType === "Model" ? 'black' : 'hsl(2.45deg 44.14% 21.76%)';
        // const modelName = modelData.name.replace(/-/g, ' ');
        const modelName = formatModelName(modelData.name);
        const translatedEyeColor = await translate(modelData.eyeColor, 'en');
        const translatedHairColor = await translate(modelData.hairColor, 'en');


        const htmlString = /*html*/`
                    <table
                    style="
                        width: 100%;
                        height: 100%;
                        font-family: Montserrat, serif;
                        max-width: 210mm;
                        height: 290mm;
                        margin: 0 auto;
                        padding: 0;
                    "
                    >

                    <tr>
                        <td>
                            <h1
                                style="
                                    text-align: center;
                                    font-size: 32px;
                                    font-weight: 900;
                                    color: black;                                    text-transform:capitalize;
                                    margin:0;
                                "
                            >
                                ${modelName}
                            </h1>
                            <p
                                style="
                                    text-align: center;
                                    font-size: 1.1rem;
                                    margin-top: 0.15rem;
                                    color: black;
                                "
                            >
                                Shawn Agency
                            </p>
                            <div
                                style="
                                    display: grid;
                                    grid-template-columns: 1fr 1fr;
                                    grid-template-rows: 1fr 1fr;
                                    gap: 1rem;
                                    margin-bottom: 1rem;
                                    width: 85%;
                                    margin: auto;
                                    padding-top: 1.5rem;
                                    height: 700px;
                                "
                            >
                                <div
                                    style="grid-row: span 2; position: relative; border: .15rem solid ${borderColor}; background: url('${modelData.images[0]
            }'); background-size: cover; background-position: center;"
                                ></div>
                                <div
                                    style="border: .15rem solid ${borderColor}; background: url('${modelData.images[1]
            }'); background-size: cover; background-position: center;"
                                ></div>
                                <div
                                    style="border: .15rem solid ${borderColor}; background: url('${modelData.images[2]
            }'); background-size: cover; background-position: center;"
                                ></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="display: grid;margin-top: .5rem;margin-bottom:0;margin-inline:1rem;text-align:center; grid-template-columns: ${modelData.sexe === "Femme"
                ? "repeat(8, 1fr)"
                : "repeat(6, 1fr)"
            };">
                                <span style="color:black;">Hauteur</span>
                                <span style="color:black;">Poids</span>
                                <span style="color:black;${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">Taille</span>
                                <span style="color:black;">Hanche</span>
                                <span style="color:black; ${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">Poitrine</span>
                                <span style="color:black;">Pointure</span>
                                <span style="color:black;">Yeux</span>
                                <span style="color:black;">Cheveux</span>
                            </div>
                            <div style="background:#a2725e;color:white;border-radius:10px;padding-bottom:12px;padding-top:5px;margin-block:10px;margin-inline:1rem;display: grid;text-align:center; grid-template-columns: ${modelData.sexe === "Femme"
                ? "repeat(8, 1fr)"
                : "repeat(6, 1fr)"
            };">
                                <span style="margin-top:-.5rem;">${modelData.height}</span>
                                <span style="margin-top:-.5rem;">${modelData.weight}</span>
                                <span style="margin-top:-.5rem;${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">${modelData.waist}</span>
                                <span style="margin-top:-.5rem;">${modelData.hips}</span>
                                <span style="margin-top:-.5rem;${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">${modelData.bust}</span>
                                <span style="margin-top:-.5rem;">${modelData.shoeSize}</span>
                                <span style="margin-top:-.5rem;">${modelData.eyeColor}</span>
                                <span style="margin-top:-.5rem;">${modelData.hairColor}</span>
                            </div>
                            <div style="display: grid;color:black;text-align:center;margin-inline:1rem; grid-template-columns: ${modelData.sexe === "Femme"
                ? "repeat(8, 1fr)"
                : "repeat(6, 1fr)"
            };">
                                <span style="color:black;">Height</span>
                                <span style="color:black;">Weight</span>
                                <span style="color:black;${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">Waist</span>
                                <span style="color:black;">Hip</span>
                                <span style="color:black;${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">Bust</span>
                                <span style="color:black;">Shoe</span>
                                <span style="color:black;">Eyes</span>
                                <span style="color:black;">Hair</span>
                            </div>

                            <div style="background:#a2725e;color:white;border-radius:10px;padding-bottom:12px;padding-top:5px;margin-block:10px;margin-inline:1rem;display: grid;text-align:center; grid-template-columns: ${modelData.sexe === "Femme"
                ? "repeat(8, 1fr)"
                : "repeat(6, 1fr)"
            };">
                                <span style="margin-top:-.5rem;">${modelData.heightUs}</span>
                                <span style="margin-top:-.5rem;">${modelData.weightUs}</span>
                                <span style="margin-top:-.5rem; ${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">${modelData.waistUs}</span>
                                <span style="margin-top:-.5rem;">${modelData.hipsUs}</span>
                                <span style="margin-top:-.5rem;${modelData.sexe === "Femme"
                ? ""
                : "display:none"
            };">${modelData.bustUs}</span>
                                <span style="margin-top:-.5rem;">${modelData.shoeSizeUs}</span>
                                <span style="margin-top:-.5rem;">${translatedEyeColor}</span>
                                <span style="margin-top:-.5rem;">${translatedHairColor}</span>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div
                                    style="
                                        display: flex;
                                        justify-content: space-between;
                                        border-top: 1px solid hsl(2.45deg 44.14% 21.76%);
                                        margin-inline: 1rem;
                                        margin-top: .25rem;
                                        padding-bottom: 1rem;
                                    "
                                >
                                    <div style="color: black;">
                                        <b style="color: black;">Langues:</b> ${modelData.languages.join(", ")}
                                    </div>
                                    <div style="color: black;">
                                        <b style="color: black;">Piercings:</b> ${modelData.piercings}
                                    </div>
                                    <div style="color: black;">
                                        <b style="color: black;">Tatouages:</b> ${modelData.tattoos}
                                    </div>
                                    <div style="color: black;">
                                        <b style="color: black;">Sport:</b> ${modelData.sport}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="position: relative; padding-top: 1rem">
                                    <div>
                                        <div
                                            style="
                                                width: 100%;
                                                height: 1px;
                                                background-color: black;
                                                margin: 5px 0;
                                            "
                                        ></div>
                                        <div
                                            style="
                                                width: 100%;
                                                height: 1px;
                                                background-color: black;
                                                margin: 5px 0;
                                            "
                                        ></div>
                                    </div>
                                    <p
                                        style="
                                            font-weight: bold;
                                            position: absolute;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                            top: 50%;
                                            background-color: white;
                                            padding: 0.25rem 0.5rem;
                                            z-index: 9;
                                            font-size: 1.1rem;
                                            margin-top: 0.15rem;
                                            color: black;
                                        "
                                    >
                                        Dysnasty Shawn
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <tr>
                        <td>
                            <div
                                style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    position: relative;
                                    text-align: center;
                                "
                            >
                                <img
                                    src="${modelData.agencyLogo}"
                                    alt="Agency Logo"
                                    style="
                                        width: 100px;
                                        height: auto;
                                        aspect-ratio: 1;
                                        object-fit: cover;
                                        border-radius: 100%;
                                        position: absolute;
                                        left: 0;
                                        top: 50%;
                                        transform: translateY(-70%);
                                        background:white;
                                    "
                                />
                                <div class="contact-info">
                                    <p style="font-size: 1.1rem; color: black;">
                                        Shawn Agency | Agence de mannequinat et
                                        publicité
                                    </p>
                                    <p style="font-size: 1.1rem; color: black;">
                                        ${modelData.agencyAddress}
                                    </p>
                                    <p style="font-size: 1.1rem; color: black;">
                                        ${modelData.agencyContact}
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </table>
            `;
        return Promise.resolve(htmlString)
    }

    async function generatePDFFromData(modelData) {
        const formattedData = formatModelData(modelData);
        const htmlString = await generateModelCompCard(formattedData);

        const container = document.createElement('div');
        container.innerHTML = htmlString;
        document.body.appendChild(container);

        const options = {
            margin: 0,
            filename: `${formattedData.name}-comp-card.pdf`,
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 2,
                useCORS: true,
                letterRendering: true,
                logging: false,
                scrollY: -window.scrollY,
                windowWidth: 210 * 2.83465,
                windowHeight: 297 * 2.83465
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait',
                compress: false
            }
        };

        try {
            const imagePromises = Array.from(container.getElementsByTagName('img'))
                .map(img => new Promise((resolve, reject) => {
                    if (img.complete) {
                        resolve();
                    } else {
                        img.onload = resolve;
                        img.onerror = reject;
                    }
                }));

            await Promise.all(imagePromises);

            // Generate PDF
            await html2pdf()
                .set(options)
                .from(container)
                .save();
        } catch (error) {
            console.error('PDF generation failed:', error);
            if (load_toast) {
                load_toast.classList.add("translate-y-[150%]");
            }
            throw error;
        } finally {
            document.body.removeChild(container);
            if (load_toast) {
                load_toast.classList.add("translate-y-[150%]");
            }
        }
    }

    // const slugToSpace = document.querySelector('.slugToSpace');
    // if (slugToSpace) {
    //     const slugToSpaceContent = slugToSpace.textContent;
    //     slugToSpace.textContent = slugToSpaceContent.replace(/-/g, ' ');
    // }


    const measurementFields = {
        total_height: document.querySelector('.total_height'),
        poids: document.querySelector('.poids'),
        waist_circumference: document.querySelector('.waist_circumference'),
        hips_circumference: document.querySelector('.tour_de_hanches'),
        chest_circumference: document.querySelector('.chest_circumference'),
        pointure: document.querySelector('.pointure'),
    };

    const usFields = {
        height: document.querySelector('.total_heightUs'),
        weight: document.querySelector('.poidsUs'),
        waist: document.querySelector('.waist_circumferenceUs'),
        hips: document.querySelector('.tour_de_hanchesUs'),
        bust: document.querySelector('.chest_circumferenceUs'),
        shoeSize: document.querySelector('.pointureUs'),
    };

    const measurements = {};
    for (const [key, field] of Object.entries(measurementFields)) {
        if (field) {
            const value = field.textContent.trim();
            measurements[key] = value === 'N/A' ? null : parseFloat(value);
        }
    }

    const sexe = document.querySelector('[data-sexe]')?.dataset.sexe || 'Femme';

    const ukMeasurements = convertMeasurementsToUK(measurements, sexe);

    for (const [key, field] of Object.entries(usFields)) {
        if (field) {
            field.textContent = ukMeasurements[key] || 'N/A';
        }
    }

    const translateConvertExtra = async () => {
        const couleur_cheveuxF = document.querySelector('.couleur_cheveux');
        const couleur_yeux = document.querySelector('.couleur_yeux');

        if (!couleur_cheveuxF || !couleur_yeux) return;

        const translatedEyeColor = await translate(couleur_yeux.textContent, 'en');
        const translatedHairColor = await translate(couleur_cheveuxF.textContent, 'en');
        document.querySelector(".couleur_cheveuxUs").textContent = translatedHairColor;
        document.querySelector(".couleur_yeuxUs").textContent = translatedEyeColor;
    }
    translateConvertExtra();


    // const modelNameContents = document.querySelectorAll('.Model-name');
    // modelNameContents.forEach(element => {
    //     let text = element.textContent.trim();

    //     let words = text.split(' ');

    //     if (words.length > 0) {
    //         words[0] = words[0].charAt(0).toUpperCase() + words[0].slice(1).toLowerCase();

    //         for (let i = 1; i < words.length; i++) {
    //             words[i] = words[i].toUpperCase();
    //         }

    //         element.textContent = words.join(' ');
    //     }
    // });

    function formatModelName(text) {
        let words = text.trim().split(' ');

        if (words.length > 0) {
            words[0] = words[0].charAt(0).toUpperCase() + words[0].slice(1).toLowerCase();

            for (let i = 1; i < words.length; i++) {
                words[i] = words[i].toUpperCase();
            }

            return words.join(' ');
        }
        return text;
    }

    const modelNameContent = document.querySelector('.Model-name');
    if (modelNameContent) {
        modelNameContent.textContent = formatModelName(modelNameContent.textContent);
    }




    // Filtre dates disponibilite
    window.addEventListener('load', function () {
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        if (!dateDebut || !dateFin) return;
        const today = new Date().toISOString().split('T')[0];
        dateDebut.min = today;
        dateFin.min = today;

        dateDebut.addEventListener('change', function () {
            if (dateDebut.value) {

                dateFin.min = dateDebut.value;

                if (dateFin.value && dateFin.value < dateDebut.value) {
                    dateFin.value = dateDebut.value;
                }
            } else {

                dateFin.min = today;
            }
        });
    });
});




document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('delete-confirm-modal');

    if (!modal) return;

    const modalContent = modal.querySelector('.alert-dialog-content');
    const deleteForm = modal.querySelector('.delte-model-form');
    const closeButtons = modal.querySelectorAll('.close-dialog-btn');

    const userRole = modal.dataset.userRole;

    const unauthorizedModal = document.getElementById('unauthorized-modal');
    const unauthorizedContent = unauthorizedModal.querySelector('.unauthorized-content');

    function openUnauthorizedModal() {
        unauthorizedModal.classList.remove('hidden');
        unauthorizedModal.classList.add('flex');
        setTimeout(() => unauthorizedContent.classList.remove('opacity-0', 'translate-y-20'), 10);
    }

    function closeUnauthorizedModal() {
        unauthorizedContent.classList.add('opacity-0', 'translate-y-20');
        setTimeout(() => {
            unauthorizedModal.classList.add('hidden');
            unauthorizedModal.classList.remove('flex');
        }, 300);
    }

    unauthorizedModal.querySelectorAll('.close-unauthorized').forEach(btn => {
        btn.addEventListener('click', closeUnauthorizedModal);
    });

    // Ouvrir la modale de confirmation pour TOUS les rôles
    document.querySelectorAll('.delete-member-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          
            const action = this.dataset.action;
            if (deleteForm) deleteForm.action = action;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => modalContent.classList.remove('opacity-0', 'translate-y-20'), 10);
        });
    });

    // Fermer la modale de confirmation
    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            modalContent.classList.add('opacity-0', 'translate-y-20');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        });
    });

    if (deleteForm) {
        deleteForm.addEventListener('submit', function (e) {
            if (userRole === 'bookeuse') {
                e.preventDefault();

                modalContent.classList.add('opacity-0', 'translate-y-20');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    openUnauthorizedModal();
                }, 300);
            }
        });
    }
});




document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('contactForm');
    const badge = document.getElementById('demandes-badge');

    if (!badge) return;

    function updateBadge() {
        fetch('/api/demandes/count', {
            headers: { 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                badge.innerText = data.count;
                badge.style.display = data.count > 0 ? 'inline-block' : 'none';
            })
            .catch(err => console.error(err));
    }

    setInterval(updateBadge, 1000);

    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    form.reset();

                    badge.innerText = data.count;
                    badge.style.display = data.count > 0 ? 'inline-block' : 'none';

                } else {
                    alert('Erreur');
                }

            })
            .catch(err => console.error(err));
    });

});







document.addEventListener('DOMContentLoaded', function () {
    const calendarTable = document.getElementById('calendar-table');
    const calendarBody = document.getElementById('calendar-body');
    const monthTitleEl = document.getElementById('month-title');
    const form = document.querySelector('form');
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const resetIcon = document.getElementById('reset-icon');

    let isDragging = false;
    let startDate = null;
    let currentDate = new Date();

    // ====================== Initialisation du mois selon les paramètres URL ======================
    function initCurrentDateFromForm() {
        if (dateFinInput && dateFinInput.value) {
            const fin = new Date(dateFinInput.value);
            if (!isNaN(fin.getTime())) {
                currentDate = new Date(fin.getFullYear(), fin.getMonth(), 1);
                return;
            }
        }
        if (dateDebutInput && dateDebutInput.value) {
            const debut = new Date(dateDebutInput.value);
            if (!isNaN(debut.getTime())) {
                currentDate = new Date(debut.getFullYear(), debut.getMonth(), 1);
            }
        }
    }

    initCurrentDateFromForm();

    // ===========================================================================================

    function createLocalDate(y, m, d) {
        return new Date(y, m, d, 0, 0, 0, 0);
    }

    function formatLocalDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function formatMonthTitle(date) {
        const month = date.toLocaleString('fr-FR', { month: 'long' });
        return `${month.charAt(0).toUpperCase() + month.slice(1)} ${date.getFullYear()}`;
    }

    function clearVisualSelection() {
        document.querySelectorAll('.circle-day-black, .range-day')
            .forEach(el => el.classList.remove('circle-day-black', 'range-day'));
        startDate = null;
    }

    function applyRange(start, end) {
        document.querySelectorAll('.circle-day, .range-day')
            .forEach(el => el.classList.remove('circle-day', 'range-day'));

        document.querySelectorAll('.day-cell').forEach(cell => {
            const y = parseInt(cell.getAttribute('data-year'));
            const m = parseInt(cell.getAttribute('data-month'));
            const d = parseInt(cell.getAttribute('data-day'));
            const cellDate = createLocalDate(y, m, d);

            if (cellDate.getTime() === start.getTime() || cellDate.getTime() === end.getTime()) {
                cell.classList.add('circle-day-black');
            } else if (cellDate > start && cellDate < end) {
                cell.classList.add('range-day');
            }
        });

        if (dateDebutInput) dateDebutInput.value = formatLocalDate(start);
        if (dateFinInput) dateFinInput.value = formatLocalDate(end);
    }

    function mergeAvailabilities(events) {
        if (events.length === 0) return [];

        let intervals = events.map(event => {
            const [sy, sm, sd] = event.start.split('-').map(Number);
            const [ey, em, ed] = event.end.split('-').map(Number);

            return {
                start: createLocalDate(sy, sm - 1, sd),
                end: createLocalDate(ey, em - 1, ed),
                models: [{
                    title: event.title,
                    start: event.start,
                    end: event.end,
                    slug: event.slug || event.title  // slug depuis API, sinon fallback sur title
                }]
            };
        });

        intervals.sort((a, b) => a.start.getTime() - b.start.getTime());

        let merged = [];
        let current = {
            start: intervals[0].start,
            end: intervals[0].end,
            models: [...intervals[0].models]
        };
        const dayMs = 86400000;

        for (let i = 1; i < intervals.length; i++) {
            const next = intervals[i];
            if (next.start.getTime() <= current.end.getTime() + dayMs) {
                if (next.end.getTime() > current.end.getTime()) current.end = next.end;
                current.models.push(...next.models);
            } else {
                merged.push(current);
                current = { start: next.start, end: next.end, models: [...next.models] };
            }
        }
        merged.push(current);
        return merged;
    }

    // ====================== POPUP ======================
    window.showAvailabilityPopup = function (models, startDate, endDate) {
        const popup = document.getElementById('availability-popup');
        if (!popup) return;
        const container = document.getElementById('popup-models');
        container.innerHTML = '';

        const startStr = formatLocalDate(startDate);
        const endStr = formatLocalDate(endDate);

        let html = `<div style="margin-bottom: 15px; font-weight: 600;">
            Période entre : ${startStr} au ${endStr}
        </div>
        <h4 style="margin-bottom: 12px; color: #000;">Modèles inclus dans cette période :</h4>
        <ul style="padding: 0; margin: 0; list-style: none;">`;

        models.forEach(model => {
            const modelSlug = model.slug || model.title;
            const href = modelSlug ? `/${modelSlug}` : 'javascript:void(0);';

            html += `<li style="background: #f8f9fa; margin-bottom: 10px; padding: 12px; border-radius: 6px; border-left: 5px solid #228b22;">
                <a href="${href}" style="color: #228b22; font-weight: 600; text-decoration: none;" target="_blank">
                    ${model.title}
                </a><br>
                <small style="color: #555;">du ${model.start} au ${model.end}</small>
            </li>`;
        });

        html += '</ul>';
        container.innerHTML = html;
        popup.classList.add('show');
    };

    window.hideAvailabilityPopup = function () {
        const popup = document.getElementById('availability-popup');
        if (popup) popup.classList.remove('show');
    };

    // ====================== CHARGEMENT DISPONIBILITÉS ======================
    async function loadAvailabilities(year, month) {
        const start = `${year}-${String(month + 1).padStart(2, '0')}-01`;
        const end = `${year}-${String(month + 1).padStart(2, '0')}-31`;
        const dateDebut = dateDebutInput ? dateDebutInput.value : '';
        const dateFin = dateFinInput ? dateFinInput.value : '';

        let url = `/calendrier/disponibilites?start=${start}&end=${end}`;
        if (dateDebut && dateFin) {
            url += `&disponibilite_debut=${dateDebut}&disponibilite_fin=${dateFin}`;
        }

        const res = await fetch(url);
        return await res.json();
    }

    // ====================== RENDU CALENDRIER ======================
    async function renderCalendar(date) {
        const prevHeight = calendarBody.offsetHeight;
        calendarTable.style.transition = 'opacity 0.2s ease, height 0.2s ease';
        calendarTable.style.height = prevHeight + 'px';
        calendarTable.style.opacity = 0;

        setTimeout(async () => {
            calendarBody.innerHTML = '';
            monthTitleEl.textContent = formatMonthTitle(date);

            const year = date.getFullYear();
            const month = date.getMonth();

            const firstDayOfMonth = new Date(year, month, 1);
            const lastDayOfMonth = new Date(year, month + 1, 0);

            let firstDayWeekday = firstDayOfMonth.getDay();
            let offset = firstDayWeekday === 0 ? 6 : firstDayWeekday - 1;

            const events = await loadAvailabilities(year, month);
            const mergedBlocks = mergeAvailabilities(events);

            let currentRow = document.createElement('tr');

            // ----- Jours du mois précédent -----
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            for (let i = offset - 1; i >= 0; i--) {
                const day = prevMonthLastDay - i;
                const cell = document.createElement('td');
                cell.className = 'day-cell prev-month';

                const cellDate = createLocalDate(year, month - 1, day);
                cell.setAttribute('data-year', year);
                cell.setAttribute('data-month', month - 1);
                cell.setAttribute('data-day', day);

                const span = document.createElement('span');
                span.textContent = day;
                span.style.opacity = '0.5';
                cell.appendChild(span);

                cell.onmousedown = () => {
                    clearVisualSelection();
                    startDate = cellDate;
                    cell.classList.add('circle-day-black');
                    isDragging = true;
                    if (dateDebutInput) dateDebutInput.value = formatLocalDate(startDate);
                    if (dateFinInput) dateFinInput.value = formatLocalDate(startDate);
                };
                cell.onmouseover = () => {
                    if (!isDragging || !startDate) return;
                    const s = startDate < cellDate ? startDate : cellDate;
                    const e = startDate > cellDate ? startDate : cellDate;
                    applyRange(s, e);
                };

                currentRow.appendChild(cell);
            }

            const today = createLocalDate(
                new Date().getFullYear(),
                new Date().getMonth(),
                new Date().getDate()
            );

            // ----- Jours du mois courant -----
            for (let d = 1; d <= lastDayOfMonth.getDate(); d++) {
                if (currentRow.children.length === 7) {
                    calendarBody.appendChild(currentRow);
                    currentRow = document.createElement('tr');
                }

                const cell = document.createElement('td');
                cell.className = 'day-cell';

                const cellDate = createLocalDate(year, month, d);
                cell.setAttribute('data-year', year);
                cell.setAttribute('data-month', month);
                cell.setAttribute('data-day', d);

                const span = document.createElement('span');
                span.textContent = d;
                cell.appendChild(span);

                // Cherche les blocs fusionnés qui couvrent cette cellule
                const cellMergedBlocks = mergedBlocks.filter(merged =>
                    cellDate >= merged.start && cellDate <= merged.end
                );

                if (cellMergedBlocks.length) {
                    const merged = cellMergedBlocks[0];
                    const startTime = merged.start.getTime();
                    const endTime = merged.end.getTime();
                    const numModels = merged.models.length;

                    const bar = document.createElement('div');
                    bar.classList.add('availability-bar');
                    bar.style.backgroundColor = 'rgba(34, 139, 34, 0.25)';

                    // Texte UNIQUEMENT sur la cellule de départ, centré et bold (via CSS)
                    if (cellDate.getTime() === startTime) {
                        bar.textContent = numModels === 1
                            ? merged.models[0].title
                            : `${numModels}modèles `;

                        cell.classList.add('availability-start');
                    }

                    if (cellDate.getTime() === endTime) {
                        cell.classList.add('availability-end');
                    }

                    cell.appendChild(bar);

                    bar.addEventListener('click', (e) => {
                        e.stopPropagation();
                        showAvailabilityPopup(merged.models, merged.start, merged.end);
                    });
                }

                if (cellDate.getTime() === today.getTime()) cell.classList.add('today');

                cell.onmousedown = () => {
                    clearVisualSelection();
                    startDate = cellDate;
                    cell.classList.add('circle-day-black');
                    isDragging = true;
                    if (dateDebutInput) dateDebutInput.value = formatLocalDate(startDate);
                    if (dateFinInput) dateFinInput.value = formatLocalDate(startDate);
                };
                cell.onmouseover = () => {
                    if (!isDragging || !startDate) return;
                    const s = startDate < cellDate ? startDate : cellDate;
                    const e = startDate > cellDate ? startDate : cellDate;
                    applyRange(s, e);
                };

                currentRow.appendChild(cell);
            }

            // ----- Jours du mois suivant -----
            let nextMonthDay = 1;
            while (currentRow.children.length < 7) {
                const cell = document.createElement('td');
                cell.className = 'day-cell next-month';

                const cellDate = createLocalDate(year, month + 1, nextMonthDay);
                cell.setAttribute('data-year', year);
                cell.setAttribute('data-month', month + 1);
                cell.setAttribute('data-day', nextMonthDay);

                const span = document.createElement('span');
                span.textContent = nextMonthDay++;
                span.style.opacity = '0.5';
                cell.appendChild(span);

                cell.onmousedown = () => {
                    clearVisualSelection();
                    startDate = cellDate;
                    cell.classList.add('circle-day');
                    isDragging = true;
                    if (dateDebutInput) dateDebutInput.value = formatLocalDate(startDate);
                    if (dateFinInput) dateFinInput.value = formatLocalDate(startDate);
                };
                cell.onmouseover = () => {
                    if (!isDragging || !startDate) return;
                    const s = startDate < cellDate ? startDate : cellDate;
                    const e = startDate > cellDate ? startDate : cellDate;
                    applyRange(s, e);
                };

                currentRow.appendChild(cell);
            }

            calendarBody.appendChild(currentRow);

            calendarTable.style.height = 'auto';
            calendarTable.style.opacity = 1;
        }, 50);
    }

    // ====================== RÉINITIALISER ======================
    resetIcon.addEventListener('click', () => {
        if (dateDebutInput) dateDebutInput.value = '';
        if (dateFinInput) dateFinInput.value = '';
        clearVisualSelection();
        renderCalendar(currentDate);
    });

    // ====================== NAVIGATION ======================
    document.getElementById('prev-btn').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    document.getElementById('next-btn').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    document.addEventListener('mouseup', () => { isDragging = false; });

    if (dateDebutInput) dateDebutInput.addEventListener('change', () => renderCalendar(currentDate));
    if (dateFinInput) dateFinInput.addEventListener('change', () => renderCalendar(currentDate));

    // ====================== VALIDATION FORMULAIRE ======================
    form.addEventListener('submit', function (e) {
        if (!dateDebutInput?.value || !dateFinInput?.value) {
            e.preventDefault();
            alert('Veuillez sélectionner une plage de dates dans le calendrier.');
            return false;
        }

        const debut = new Date(dateDebutInput.value);
        const fin = new Date(dateFinInput.value);

        if (fin < debut) {
            e.preventDefault();
            alert('La date de fin doit être supérieure ou égale à la date de début.');
            return false;
        }
    });

    // ====================== HAMBURGER MENU ======================
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileReset = document.getElementById('mobile-reset-icon');

    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
        });
    }

    const mobileDebut = document.getElementById('mobile_date_debut');
    const mobileFin = document.getElementById('mobile_date_fin');

    if (mobileDebut && dateDebutInput) mobileDebut.addEventListener('change', () => dateDebutInput.value = mobileDebut.value);
    if (mobileFin && dateFinInput) mobileFin.addEventListener('change', () => dateFinInput.value = mobileFin.value);

    if (mobileReset) {
        mobileReset.addEventListener('click', () => {
            if (mobileDebut) mobileDebut.value = '';
            if (mobileFin) mobileFin.value = '';
            if (dateDebutInput) dateDebutInput.value = '';
            if (dateFinInput) dateFinInput.value = '';
            clearVisualSelection();
            renderCalendar(currentDate);
            mobileMenu.classList.remove('show');
        });
    }

    renderCalendar(currentDate);
});






