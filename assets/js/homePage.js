//function use to change the status of the research plan when validate
// or requir a review

function asyncForm() {
    const validatePlanForms = document.getElementsByClassName('validate-plan-form');
    for (const validatePlanForm of validatePlanForms) {
        if(validatePlanForm) {
            validatePlanForm.addEventListener('submit', (event) =>{
                const idOfValidatePlanForm =  validatePlanForm.getAttribute('data-id');
                event.preventDefault();
                const form = new FormData(validatePlanForm);
                fetch('/', {
                    method: 'POST',
                    body: form
                })
                    .then(function(){
                        if (document.getElementById('plan_status').value != 'Review needed') {
                            loadPlanStatus(idOfValidatePlanForm)
                            loadPlanButton(idOfValidatePlanForm)
                            loadTitle(idOfValidatePlanForm);
                            loadValidateContainer(idOfValidatePlanForm)
                        } else {
                            loadPlanStatus(idOfValidatePlanForm)
                            loadPlanButton(idOfValidatePlanForm)
                            loadValidateContainer(idOfValidatePlanForm)
                            setTimeout(asyncForm, 1000)
                        } 
                    })
            })
        }
    }
    const planStatusInputs = document.getElementsByClassName('plan_status');
    const validatePlanButtons = document.querySelectorAll('.validate-plan-button');
    for (const validatePlanButton of validatePlanButtons) {
        validatePlanButton.addEventListener('click', () => {
            for (const planStatusInput of planStatusInputs) {
                planStatusInput.value = 'Validated';
            }
        })
    }
    const askAReviewButtons = document.getElementsByClassName('ask-review-button');
    for (const askAReviewButton of askAReviewButtons) {
        askAReviewButton.addEventListener('click', () => {
            for (const planStatusInput of planStatusInputs) {
                planStatusInput.value = 'Review needed';
            }
        })
    }
}


function loadTitle(idOfvalidatePlanButton) {
    const tbodys = document.getElementsByClassName('details-research-plan-title' + idOfvalidatePlanButton);
    for (const tbody of tbodys) {
        const idOfTbody = tbody.getAttribute('data-id');
        if (idOfvalidatePlanButton === idOfTbody) {
            fetch('/modal/' + idOfvalidatePlanButton)
                .then(response => response.text()
                    .then(content => tbody.innerHTML = content))
            ;
        }
    }            
}

function loadValidateContainer(idOfvalidatePlanButton) {
    const bodys = document.getElementsByClassName('validate-plan-container' + idOfvalidatePlanButton);
    for (const body of bodys) {
        const idOfBody = body.getAttribute('data-id');
        if (idOfvalidatePlanButton === idOfBody) {
            fetch('/validate/' + idOfvalidatePlanButton)
                .then(response => response.text()
                    .then(content => body.innerHTML = content))
            ;
            
        }
    }             
}

function loadPlanStatus(idOfvalidatePlanButton) {
    const statusBodys = document.getElementsByClassName('plan-status' + idOfvalidatePlanButton);
    for (const statusBody of statusBodys) {
        const idOfStatusBody = statusBody.getAttribute('data-id');
        if (idOfvalidatePlanButton === idOfStatusBody) {
            fetch('/plan/status/' + idOfvalidatePlanButton)
                .then(response => response.text()
                    .then(content => statusBody.innerHTML = content))
            ;
        }
    } 
}

function loadPlanButton(idOfvalidatePlanButton) {
    const buttonBodys = document.getElementsByClassName('plan-button' + idOfvalidatePlanButton);
    for (const buttonBody of buttonBodys) {
        const idOfButtonBody = buttonBody.getAttribute('data-id');
        if (idOfvalidatePlanButton === idOfButtonBody) {
            fetch('/button/' + idOfvalidatePlanButton)
                .then(response => response.text()
                    .then(content => buttonBody.innerHTML = content))
            ;
        }
    } 
}


if (document.getElementById('reasearch-plans')) {

    const researchPlans = document.getElementById('reasearch-plans');
    const researchRequests = document.getElementById('reasearch-requests');
    const requestsList = document.getElementById('requests-list');
    const plansListNone = document.getElementById('plans-list-none');
    const viewRequests = document.getElementsByClassName('view-requests');
    const viewPlans = document.getElementsByClassName('view-plans');
    const requestsProject = document.getElementById('requests-project');
    const requestsCoachAssigned = document.getElementById('requests-coach-assigned');
    const plansProject = document.getElementById('plans-project');
    const plansAssignedRequest = document.getElementById('plans-assigned-request');
    const createRequestButton = document.getElementById('create-request');
    const researchCenterAvailableTemplates = document.getElementById('research-center-available-templates');
    const researchCenterAvailableTemplatesClose = document.getElementById('available-templates-header-close');
    const researchTemplateActiveCardButtons = document.querySelectorAll('.research-template-list-active-card-link-button');
    const researchRequestModals = document.querySelectorAll('.new-research-request-modal');
    const researchRequestModalClose = document.querySelectorAll('.new-research-request-modal-close');
    const modalInterviewPlanningRequests = document.getElementsByClassName('modal-interview-planning-request');
    const buttoninterviewPlanningModalCloses = document.getElementsByClassName('interview-planning-header-close');
    const buttonsViewResearchRequest = document.getElementsByClassName('request-details-button');
    const linksViewResearchRequest = document.getElementsByClassName('request-details-link');
    const planTableScroll = document.getElementById('plan-table-scroll');
    const researchPlanDetailsButtons = document.querySelectorAll('.research-plan-details-button');
    const openResearchPlanDetailsButtons = document.querySelectorAll('.open-research-plan-details-button');
    const researchPlanDetailsModals = document.querySelectorAll('.research-center-details-research-plan');
    const researchPlanDetailsModalsClose = document.querySelectorAll('.details-research-plan-header-close');
    const researchPlanDetailsReturnLinks = document.querySelectorAll('.details-research-plan-header-return-icon');

    researchRequests.onchange = function () {
        researchPlans.checked = true;
        if (researchRequests.checked == true && researchPlans.checked == true) {
            plansListNone.className = 'share-plans-list';
            requestsList.className = 'share-requests-list';

            for (const viewRequest of viewRequests) {
                viewRequest.classList.remove('view-details');
                viewRequest.classList.add('view-details-none');
            }
            for (const viewPlan of viewPlans) {
                viewPlan.classList.add('view-details-none');
                viewPlan.classList.remove('view-details');
            }

            requestsProject.className = 'sort-none';
            requestsCoachAssigned.className = 'sort-none';
            plansProject.className = 'sort-none';
            plansAssignedRequest.className = 'sort-none';

        } else {
            requestsList.className = 'requests-list-none ';
            plansListNone.className = 'plans-list';

            for (const viewRequest of viewRequests) {
                viewRequest.classList.remove('view-details-none');
                viewRequest.classList.add('view-details');
            }
            for (const viewPlan of viewPlans) {
                viewPlan.classList.remove('view-details-none');
                viewPlan.classList.add('view-details');
            }
            requestsProject.className = 'sort';
            requestsCoachAssigned.className = 'sort';
            plansProject.className = 'sort';
            plansAssignedRequest.className = 'sort';
        }
    }
    researchPlans.onchange = function () {
        researchRequests.checked = true;
        if (researchRequests.checked == true && researchPlans.checked == true) {
            plansListNone.className = 'share-plans-list';
            requestsList.className = 'share-requests-list';

            for (const viewRequest of viewRequests) {
                viewRequest.classList.remove('view-details');
                viewRequest.classList.add('view-details-none');
            }
            for (const viewPlan of viewPlans) {
                viewPlan.classList.add('view-details-none');
                viewPlan.classList.remove('view-details');
            }
            requestsProject.className = 'sort-none';
            requestsCoachAssigned.className = 'sort-none';
            plansProject.className = 'sort-none';
            plansAssignedRequest.className = 'sort-none';
        } else {
            requestsList.className = 'requests-list';
            plansListNone.className = 'plans-list-none';

            for (const viewRequest of viewRequests) {
                viewRequest.classList.remove('view-details-none');
                viewRequest.classList.add('view-details');
            }
            for (const viewPlan of viewPlans) {
                viewPlan.classList.remove('view-details-none');
                viewPlan.classList.add('view-details');
            }
            requestsProject.className = 'sort';
            requestsCoachAssigned.className = 'sort';
            plansProject.className = 'sort';
            plansAssignedRequest.className = 'sort';
        }
    }

    // function used to open the availables templates popup
    createRequestButton.addEventListener('click', () => {
        researchCenterAvailableTemplates.classList.add('research-center-available-templates-display');
        for (const modalInterviewPlanningRequest of modalInterviewPlanningRequests) {
            modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-display');
        }
    });

    // function used to close the availables templates popup
    researchCenterAvailableTemplatesClose.addEventListener('click', () => {
        researchCenterAvailableTemplates.classList.remove('research-center-available-templates-display');
    });

    // function used to open and close research request creation modals
    for (let i = 0; i < researchTemplateActiveCardButtons.length; i++) {
        researchTemplateActiveCardButtons[i].addEventListener('click', () => {
            researchRequestModals[i].classList.add('new-research-request-modal-display');
        });
        researchRequestModalClose[i].addEventListener('click', () => {
            researchRequestModals[i].classList.remove('new-research-request-modal-display');
        })
    }

    // function used to open the view of Interview planning requests
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

    // function used to open the view of Interview planning requests
    for (const linkViewResearchRequest of linksViewResearchRequest) {
        linkViewResearchRequest.addEventListener('click', () => {
            planTableScroll.classList.add('table-scroll-none');
            const idOfButtonViewResearchRequest = linkViewResearchRequest.getAttribute('id');
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


    // function used to close the view of Interview planning requests
    for (const buttoninterviewPlanningModalClose of buttoninterviewPlanningModalCloses) {
        buttoninterviewPlanningModalClose.addEventListener('click', () => {
            planTableScroll.classList.remove('table-scroll-none');
            const idOfButtoninterviewPlanningModalClose = buttoninterviewPlanningModalClose.getAttribute('data-id');
            for (const modalInterviewPlanningRequest of modalInterviewPlanningRequests) {
                const idOfmodalInterviewPlanningRequest = modalInterviewPlanningRequest.getAttribute('id');
                if (idOfButtoninterviewPlanningModalClose === idOfmodalInterviewPlanningRequest) {
                    modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-display');
                    modalInterviewPlanningRequest.classList.add('modal-interview-planning-request-close');
                    setTimeout(() => {
                        modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-close')
                    }, 600)
                }
            }
        });
    }

    // function used to open the view of research plan
    for (const researchPlanDetailsButton of researchPlanDetailsButtons) {
        researchPlanDetailsButton.addEventListener('click', () => {
            const idOfButtonViewResearchPlan = researchPlanDetailsButton.getAttribute('id');
            for (const researchPlanDetailsModal of researchPlanDetailsModals) {
                const idOfmodalResearchPlan = researchPlanDetailsModal.getAttribute('id');
                if (idOfmodalResearchPlan != idOfButtonViewResearchPlan) {
                    researchPlanDetailsModal.classList.remove('research-center-details-research-plan-display');
                }
                if (idOfmodalResearchPlan === idOfButtonViewResearchPlan) {
                    researchPlanDetailsModal.classList.add('research-center-details-research-plan-display');
                }
            }
        });
    }

    // Function used to close the research plan details modal
    for (const researchPlanDetailsModalClose of researchPlanDetailsModalsClose) {
        researchPlanDetailsModalClose.addEventListener('click', () => {
            for (const researchPlanDetailsModal of researchPlanDetailsModals) {
                researchPlanDetailsModal.classList.remove('research-center-details-research-plan-display');
            }
        })
    } 
        
    

    // function used to open the view of Interview planning requests after click on return button of research plan modal
    for (const researchPlanDetailsReturnLink of researchPlanDetailsReturnLinks) {
        researchPlanDetailsReturnLink.addEventListener('click', () => {
            const idOfButtonViewResearchPlanModal = researchPlanDetailsReturnLink.getAttribute('id');
            for (const modalInterviewPlanningRequest of modalInterviewPlanningRequests) {
                const idOfmodalInterviewPlanningRequest = modalInterviewPlanningRequest.getAttribute('id');
                if (idOfmodalInterviewPlanningRequest != idOfButtonViewResearchPlanModal) {
                    modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-display');
                }
                if (idOfmodalInterviewPlanningRequest === idOfButtonViewResearchPlanModal) {
                    modalInterviewPlanningRequest.classList.add('modal-interview-planning-request-display');
                    for (const researchPlanDetailsModal of researchPlanDetailsModals) {
                        researchPlanDetailsModal.classList.remove('research-center-details-research-plan-display');
                    }
                }
            }
        });
    }

    for (const openResearchPlanDetailsButton of openResearchPlanDetailsButtons) {
        openResearchPlanDetailsButton.addEventListener('click', () => {
            const idOfButtonViewResearchPlan = openResearchPlanDetailsButton.getAttribute('id');
            for (const researchPlanDetailsModal of researchPlanDetailsModals) {
                const idOfmodalResearchPlan = researchPlanDetailsModal.getAttribute('id');
                if (idOfmodalResearchPlan != idOfButtonViewResearchPlan) {
                    researchPlanDetailsModal.classList.remove('research-center-details-research-plan-display');
                }
                if (idOfmodalResearchPlan === idOfButtonViewResearchPlan) {
                    researchPlanDetailsModal.classList.add('research-center-details-research-plan-display');
                    for (const modalInterviewPlanningRequest of modalInterviewPlanningRequests) {
                        modalInterviewPlanningRequest.classList.remove('modal-interview-planning-request-display');
                    }
                }
            }
            
        });
    }


    //function use to change the status of the research plan when validate
    // or requir a review
    asyncForm();
}
