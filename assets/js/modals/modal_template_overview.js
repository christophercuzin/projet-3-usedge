const templateOverviewButtons = document.getElementsByClassName('template_overview');
const fullScreenTemplateOverviewModalCloses = document.getElementsByClassName('full-screen-template-overview-modal-close');
const requestDetailsCloseButtons = document.getElementsByClassName('template-overview-close');
const body = document.getElementById('body');

for (const templateOverviewButton of templateOverviewButtons) {
    templateOverviewButton.addEventListener('click', () => {
        for (const fullScreenTemplateOverviewModalClose of fullScreenTemplateOverviewModalCloses) {
            fullScreenTemplateOverviewModalClose.classList.add('full-screen-template-overview-modal-display');
            body.classList.add('hide-body-request-overflow');
            for (const requestDetailsCloseButton of requestDetailsCloseButtons) {
                requestDetailsCloseButton.addEventListener('click', () => {
                    fullScreenTemplateOverviewModalClose.classList.remove('full-screen-template-overview-modal-display');
                    body.classList.remove('hide-body-request-overflow');
                })
            }
            
        }
        
    });
}
    
    

    

