if (document.getElementById('add-research-request-header')) {

    const bodyWithSideBar = document.querySelector('body');
    const newResearchRequestHeaderButton = document.getElementById('add-research-request-header-button');
    const newResearchRequestFormButton = document.getElementById('add-research-request-form-button');
    const inputComponentId = document.getElementsByClassName('request-component-id');
    const requiredEvaluationScale = document.querySelectorAll('.ratinginput');

    bodyWithSideBar.classList.remove('body');
    bodyWithSideBar.classList.add('body-without-sidebar');

    if (document.getElementById('research-open-question-text-area')) {
        const textareas = document.querySelectorAll('.research-open-question-text-area');
        
        textareas.forEach(textarea => {
            textarea.addEventListener('keydown', autosize);
             
            function autosize(){
                var el = this;
                setTimeout(function() {
                    el.style.cssText = 'height:' + el.scrollHeight + 'px';
                },0);
            }
        });
    }

    newResearchRequestHeaderButton.addEventListener('click', (e) => {
        const statusInput = document.getElementById('research-request-status');
        statusInput.value = 'Draft';
        const requiredInputs = document.querySelectorAll('.request-answer-input');
        for (const requiredInput of requiredInputs) {
            requiredInput.removeAttribute('required');
        }

    });

    newResearchRequestFormButton.addEventListener('click', (e) => {
        for (const componentId of inputComponentId) {
            const id = componentId.value
            const requiredCheckbox = document.querySelectorAll('.required' + id);
            if (requiredCheckbox[0]) {
                let countCheckboxRequired = 0;
                requiredCheckbox.forEach(checkbox => {
                    if (checkbox.checked == true) {
                        countCheckboxRequired++;
                    }
                });
                if (countCheckboxRequired === 0) {
                    
                    alert('All stared fields are mandatory');
                    e.preventDefault();
                }
            }
        }

        if (requiredEvaluationScale[0]) {
            let countEvalScaleRequired = 0;
            requiredEvaluationScale.forEach(evaluationScale => {
                if (evaluationScale.checked == true) {
                    countEvalScaleRequired++;
                }
            });
            if (countEvalScaleRequired === 0) {
                
                alert('All stared fields are mandatory');
                e.preventDefault();
            }
        }
    });
}