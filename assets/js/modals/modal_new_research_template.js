import {iconList} from './modules/icons_module';

function changeStatusColor(selectStatusLists) {
    for (const selectStatusList of selectStatusLists) {
        const valueSelectStatusList = selectStatusList.value;
    
        selectStatusList.classList.remove('bg-green-dot', 'bg-grey-dot', 'bg-red-dot');

        switch (valueSelectStatusList) {
        case "active":
            selectStatusList.classList.add('bg-green-dot');
            break;
        case "draft":
            selectStatusList.classList.add('bg-grey-dot');
            break;
        case "dropped":
            selectStatusList.classList.add('bg-red-dot');
            break;
        case "archive":
            selectStatusList.classList.add('bg-grey-dot');
            break;
        }
    }
}

if (document.getElementById('add-template-button')) {

    // Careful : for first const, check real button id when integration !
    const addTemplateModalOpenButton = document.getElementById('add-template-button');
    const addTemplateModal = document.getElementById('template-details-modal');
    const addTemplateModalCloseButton = document.getElementById('template-details-close');
    const iconPopupOpenButton = document.getElementById('icon-choice-button');
    const iconPopup = document.querySelector('.modal-icon-select-popup-hidden');
    const iconPopupCloseButton = document.getElementById('template-icon-close');
    const submitButton = document.querySelector('.new-template-button');
    const modalTextInputs = document.querySelectorAll('.modal-input');
    const templateNameInput = document.getElementById('research_template_name');
    const templateDescriptionInput = document.getElementById('research_template_description');
    const templatecoachSelect = document.getElementById('research_template_coach');
    const iconChoicePicture = document.getElementById('icon-choice-picture');
    const editTemplateLinks = document.getElementsByClassName('edit-template-link');
    const editTemplateModals = document.getElementsByClassName('edit-template-details-modal');
    const editTemplateModalCloseButtons = document.getElementsByClassName('edit-template-details-close');
    const editIconPopupOpenButtons = document.getElementsByClassName('edit-icon-choice-button');
    const editIconPopups = document.querySelectorAll('.modal-edit-icon-select-popup-hidden');
    const editIconChoicePictures = document.querySelectorAll('.edit-icon-choice-picture')
    const editIconPopupCloseButtons = document.getElementsByClassName('edit-template-icon-close');
    const selectStatusLists = document.getElementsByClassName('edit-select-status');

    // Function used to open the modal
    addTemplateModalOpenButton.addEventListener('click', () => {
        addTemplateModal.classList.add('template-details-modal-display');
    });

    // Function used to close the modal and initialise values
    addTemplateModalCloseButton.addEventListener('click', () => {
        iconChoicePicture.src = new URL('../../images/icons/template_icon_plus.png', import.meta.url);
        templateNameInput.value = '';
        templateDescriptionInput.value = '';
        templatecoachSelect.value = '';
        addTemplateModal.classList.remove('template-details-modal-display');
        addTemplateModal.classList.add('template-details-modal-close');
        setTimeout(() => {
            addTemplateModal.classList.remove('template-details-modal-close');
        }, 600)
        iconPopup.classList.remove('modal-icon-select-popup');
        for (let iconNumber = 0; iconNumber < 6; iconNumber++) {
            let iconSubmitButton = document.getElementById(`research_template_icon_${iconNumber}`);
            iconSubmitButton.checked = false;
        }
    });

    // Function used to open the icon pop-up
    iconPopupOpenButton.addEventListener('click', () => {
        iconPopup.classList.toggle('modal-icon-select-popup');
    });

    // Function used to close the icon pop-up
    iconPopupCloseButton.addEventListener('click', () => {
        iconPopup.classList.remove('modal-icon-select-popup');
    });

    // Function used to change icon display when selected in the list
    for (let iconNumber = 0; iconNumber < 6; iconNumber++) {
        let iconSubmitButton = document.getElementById(`research_template_icon_${iconNumber}`);
        iconSubmitButton.addEventListener('click', () => {
            iconChoicePicture.src = iconList[iconNumber];
            iconPopup.classList.remove('modal-icon-select-popup');
        })
    }

    // Function used to check some front validations before form submission
    submitButton.addEventListener('click', (event) => {
        let count = 0;
        for (let iconNumber = 0; iconNumber < 6; iconNumber++) {
            let iconSubmitButton = document.getElementById(`research_template_icon_${iconNumber}`)
            if (iconSubmitButton.checked == true) {
                break;
            } else if (iconSubmitButton.checked == false && count == 5) {
                alert('Please select an icon in the list.');
            }
            count += 1;
        }
        for (const modalTextInput of modalTextInputs) {
            if (modalTextInput.value.length > 255) {
                alert('Maximum length is 255 characters.');
                event.preventDefault();
            }
        }
    });

    // Function used to open the modal to edit research template
    for (const editTemplateLink of editTemplateLinks) {
        for (const editTemplateModal of editTemplateModals) {
            editTemplateLink.addEventListener('click', () => {
                editTemplateModal.classList.add('edit-template-details-modal-display');
            });
        }
    }

    // Function used to close the edit modal
    for (const editTemplateModal of editTemplateModals) {
        for (const editTemplateModalCloseButton of editTemplateModalCloseButtons) {
            editTemplateModalCloseButton.addEventListener('click', () => {
                editTemplateModal.classList.remove('edit-template-details-modal-display');
                editTemplateModal.classList.add('edit-template-details-modal-close');
                setTimeout(() => {
                    editTemplateModal.classList.remove('edit-template-details-modal-close');
                }, 600) 
            });
        }
    }

    // Function used to open the edit icon pop-up
    
    for (const editIconPopup of editIconPopups) {
        for (const editIconPopupOpenButton of editIconPopupOpenButtons) {
            editIconPopupOpenButton.addEventListener('click', () => {
                editIconPopup.classList.toggle('modal-edit-icon-select-popup');
                
            });
        }
        // Function used to close the icon pop-up
        for (const editIconPopupCloseButton of editIconPopupCloseButtons) {
            editIconPopupCloseButton.addEventListener('click', () => {
                editIconPopup.classList.remove('modal-edit-icon-select-popup');
            });
        }

        let i = 0;
        for (const editIconChoicePicture of editIconChoicePictures) {
            let id = document.getElementById('template_id_' + i).value;
            for (let iconNumber = 0; iconNumber < 6; iconNumber++) {
                let iconSubmitButton = document.getElementById(`edit_research_template_icon_${iconNumber}_` + id);
                iconSubmitButton.addEventListener('click', () => {
                    editIconChoicePicture.src = iconList[iconNumber];
                    editIconPopup.classList.remove('modal-edit-icon-select-popup');
                })
            }
            i++
        }
 
    }
    changeStatusColor(selectStatusLists);

    for (const selectStatusList of selectStatusLists) {
        selectStatusList.addEventListener('change', function () {
            changeStatusColor(selectStatusLists);
        });
    }
     
}