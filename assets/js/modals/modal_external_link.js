/*Action to call all classes to active the section modal*/
/*const modalExternalLinkContainer = document.querySelector('.modal-external-link-container');
const modalExternalLinkTriggers = document.querySelectorAll('.modal-external-link-trigger');

modalExternalLinkTriggers.forEach(trigger => trigger.addEventListener('click', toggleExternalLinkModal))
function toggleExternalLinkModal() {
    modalExternalLinkContainer.classList.toggle('active');
}*/

if (document.getElementById('add-external-link-button')) {
    const addExternalLinkModalOpenButton = document.getElementById('add-external-link-button');
    const addExternalLinkModal = document.getElementById('add-external-link-modal');
    const externalLinkDraggableHandle = document.getElementById('external-link-draggable-handle');
    const FullScreenExternalLinkModalClose= document.getElementById('full-screen-external-link-modal-close');
    const externalLinkModalCloseButton = document.getElementById('external-link-modal-close');
    const inputExternalLinkTitle = document.getElementById('input-external-link-title');
    const inputMandatoryExternalLink = document.getElementById('input-mandatory-external-link');
    const body = document.getElementById('body');
    const draggable = require('draggable');
    const draggableOptions = {handle: externalLinkDraggableHandle}

    // Function used to open the modal
    addExternalLinkModalOpenButton.addEventListener('click', () => {
        FullScreenExternalLinkModalClose.classList.add('full-screen-external-link-modal-display');
        body.classList.add('hide-body-overflow-external-link');
        new draggable(addExternalLinkModal, draggableOptions);
        // Function used to close the modal when click outside of the modal
        window.onclick = function(event) {
            if (event.target == FullScreenExternalLinkModalClose) {
                FullScreenExternalLinkModalClose.classList.remove('full-screen-external-link-modal-display');
                body.classList.remove('hide-body-overflow-external-link');
            }
        };
    });

    // Function used to close the modal when click on close button and initialise values
    externalLinkModalCloseButton.addEventListener('click', () => {
        inputExternalLinkTitle.value = '';
        FullScreenExternalLinkModalClose.classList.remove('full-screen-external-link-modal-display');
        body.classList.remove('hide-body-overflow-external-link');
        inputMandatoryExternalLink.checked = false;
    });    
}