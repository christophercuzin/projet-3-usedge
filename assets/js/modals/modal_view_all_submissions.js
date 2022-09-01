const viewAllSubmissionsButtons = document.getElementsByClassName('view_all_submissions');
const modalViewAllSubmissions = document.getElementsByClassName('modal_view_all_submissions');
const  modalButtonCloses = document.getElementsByClassName('request-details-close');
const buttonsViewResearchRequest = document.getElementsByClassName('view-request-button');
const modalInterviewPlanningRequests = document.getElementsByClassName('modal-interview-planning-request');
const buttoninterviewPlanningModalCloses = document.getElementsByClassName('interview-planning-header-close');

for (const modalViewAllSubmission of modalViewAllSubmissions) {
    for (const viewAllSubmissionsButton of viewAllSubmissionsButtons) {
        viewAllSubmissionsButton.addEventListener('click', () => {
            modalViewAllSubmission.classList.add('modal_view_all_submissions_display');
        });
    }
    for (const modalButtonClose of modalButtonCloses) {
        modalButtonClose.addEventListener('click', () => {
            modalViewAllSubmission.classList.remove('modal_view_all_submissions_display');
            modalViewAllSubmission.classList.add('modal_view_all_submissions_close');
            setTimeout(() => {
                modalViewAllSubmission.classList.remove('modal_view_all_submissions_close')
            }, 600)
        })
        
    }
    
}

for (const buttonViewResearchRequest of buttonsViewResearchRequest) {
    buttonViewResearchRequest.addEventListener('click', () => {
        const idOfButtonViewResearchRequest = buttonViewResearchRequest.getAttribute('id');
        for (const modalInterviewPlanningRequest of modalInterviewPlanningRequests) {
            const idOfmodalInterviewPlanningRequest = modalInterviewPlanningRequest.getAttribute('id');
            if (idOfmodalInterviewPlanningRequest != idOfButtonViewResearchRequest) {
                modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-display');
            }
            if (idOfmodalInterviewPlanningRequest === idOfButtonViewResearchRequest) {
                modalInterviewPlanningRequest.classList.add('modal-interview-planning-request-display');
            }
        }
    });
}

for (const buttoninterviewPlanningModalClose of buttoninterviewPlanningModalCloses) {
    buttoninterviewPlanningModalClose.addEventListener('click', () => {
        for (const modalInterviewPlanningRequest of modalInterviewPlanningRequests) {
            modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-display');
            modalInterviewPlanningRequest.classList.add('modal-interview-planning-request-close');
            setTimeout(() => {
                modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-close')
            }, 600)
        }
    });
}

