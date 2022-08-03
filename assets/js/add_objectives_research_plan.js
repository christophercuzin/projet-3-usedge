if (document.getElementById('button-add-objectives')) {

    //Function to open the modal for add objectives
    const buttonAddObjectives = document.getElementById('button-add-objectives');
    const sendResearchPlanButton = document.getElementById('send-research-plan');
    const addObjectivesPandSave = document.getElementById('add-objectives-p-and-save');
    const objectivesCount = document.getElementById('objectives-count');
    
    buttonAddObjectives.addEventListener('click', () => {

        buttonAddObjectives.classList.remove('button-add-objectives');
        buttonAddObjectives.classList.add('button-select-objectives-display');
        
        const addObjectives = document.getElementById('add-objectives');
        addObjectives.classList.remove('add-objectives');
        addObjectives.classList.add('add-section-new-objectives');

        sendResearchPlanButton.classList.add('send-research-plan-disabled');
        sendResearchPlanButton.classList.remove('send-research-plan');
        sendResearchPlanButton.setAttribute('disabled', 'disabled');
    });

    //Function to add objectives in checkbox when the coach clic on enter
    const inputCheckbox = document.getElementById('input-objectives');
    
    inputCheckbox.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            const inputCheckbox = document.getElementById('input-objectives');
            const checkboxCase = document.getElementById('input-checkbox-objectives');
            const checkboxValue = document.getElementById('input-checkbox-text-objectives');
            const divCheckbox = document.getElementById('new-input-checkbox');
            const label = document.getElementById('newDivLabelDisplay1');

            let valueInput = inputCheckbox.value;
            let newDivParagraph = document.createElement("p");
            

            let newInputHidden = document.createElement("input");
            
            newInputHidden.setAttribute('class', 'all-input-hidden');
            newInputHidden.setAttribute('value', valueInput);
 
            checkboxCase.classList.add('input-example-display');
            checkboxValue.classList.add('input-example-display');

            let newCheckbox = document.createElement("input");
            newCheckbox.setAttribute("type", "checkbox");
            newCheckbox.setAttribute("class", "newDivCheck");
            newCheckbox.setAttribute("disabled", "disabled");

            let newLabel = document.createElement("label");
            newLabel.setAttribute("for", "checkbox");
            
        
            newLabel.innerHTML = valueInput;

            if (label) {
                newInputHidden.setAttribute('type', 'text');
                newDivParagraph.setAttribute('class', 'all-div-input-edit');
                newLabel.setAttribute("class", "newDivLabelDisplay");
            } else {
                newInputHidden.setAttribute('type', 'hidden');
                newDivParagraph.setAttribute('class', 'all-div-input');
                newLabel.setAttribute("class", "newDivLabel");
            }

             

            divCheckbox.appendChild(newDivParagraph);

            newDivParagraph.appendChild(newCheckbox);
            newDivParagraph.appendChild(newLabel);
            newDivParagraph.appendChild(newInputHidden);

            inputCheckbox.value = '';
            const collection = document.querySelectorAll('.all-div-input-edit');
            let j =1;
            let k = 1;
            for (let i = 0; i < collection.length; i++) {
                const elem = collection[i];
                newLabel.setAttribute('id', 'newDivLabelDisplay' + j);
                elem.onclick = function(event) {
                    let target =  event.target;
                    if (target === elem) {
                        target.remove();
                        
                    }
                    if (target === elem) {
                        const labels = document.querySelectorAll('.newDivLabelDisplay');
                        for (const label of labels) {
                            label.setAttribute('id', 'newDivLabelDisplay' + k);
                            k++ 
                        }
                    }
                };
                j++
            }
        }
    }, true);

    //Function to validate all objectives
    const buttonSaveObjectives = document.getElementById('button-save-objectives');
    const addObjectivesP = document.getElementById('add-objectives-p');
    
    buttonSaveObjectives.addEventListener('click', () => {
        const allInputHiddenSelected = document.querySelectorAll('.all-input-hidden');
        const collection = document.querySelectorAll('.all-div-input-edit');

        for (let i = 0; i < collection.length; i++) {
            const elem = collection[i];
            elem.classList.remove('all-div-input-edit');
            elem.classList.add('all-div-input');
            elem.onclick = function(event) {
                let target =  event.target;
                if (target === elem) {
                    event.preventDefault();
                }
            }; 
        }

        let objectives = 1;
        objectivesCount.value = 0;
        allInputHiddenSelected.forEach(input => {
            const label = document.getElementById('newDivLabelDisplay' + objectives);
            input.setAttribute('name', 'research-plan-objectives-' + objectives);
            input.setAttribute('type', "hidden");
            objectivesCount.value = objectives;
            if (label) {
                label.classList.add('newDivLabel');
                label.classList.remove('newDivLabelDisplay');
                label.innerHTML = input.value;
            }
            objectives++;
        });

        

        addObjectivesP.classList.remove('add-objectives-p');
        addObjectivesP.classList.add('add-objectives-p-none');
        addObjectivesPandSave.classList.remove('add-objectives-p-and-save-display');
        addObjectivesPandSave.classList.add('add-objectives-p-and-save');
        inputCheckbox.classList.remove('input-objectives');
        inputCheckbox.classList.add('input-objectives-display');
        buttonSaveObjectives.classList.remove('button-save-objectives');
        buttonSaveObjectives.classList.add('button-save-objectives-display');

        sendResearchPlanButton.classList.remove('send-research-plan-disabled');
        sendResearchPlanButton.classList.add('send-research-plan');
        sendResearchPlanButton.removeAttribute('disabled');
    });

    //Function to edit the objectives
    const editButton = document.getElementById('edit-objectives-icon');

    editButton.addEventListener('click', () => {

        const addObjectives = document.getElementById('add-objectives');
        const allLabel = document.querySelectorAll('.newDivLabel');
        const allInputHiddenSelected = document.querySelectorAll('.all-input-hidden');

        sendResearchPlanButton.classList.add('send-research-plan-disabled');
        sendResearchPlanButton.classList.remove('send-research-plan');
        sendResearchPlanButton.setAttribute('disabled', 'disabled');

        addObjectives.classList.remove('add-objectives');
        addObjectives.classList.add('add-section-new-objectives');

        inputCheckbox.classList.add('input-objectives');
        inputCheckbox.classList.remove('input-objectives-display');
        
        let i = 1;
        for (const label of allLabel) {
            label.classList.remove('newDivLabel');
            label.classList.add('newDivLabelDisplay');
            label.setAttribute('id', 'newDivLabelDisplay' + i);
            i++
        }

        allInputHiddenSelected.forEach(input => {
            input.setAttribute('type', "text");
        });

        const collection = document.querySelectorAll('.all-div-input');
        let j =1;
        for (let i = 0; i < collection.length; i++) {
            const elem = collection[i];
            elem.classList.add('all-div-input-edit');
            elem.classList.remove('all-div-input');
            elem.onclick = function(event) {
                let target =  event.target;
                if (target === elem) {
                    target.remove();
                }
                if (target === elem) {
                    const labels = document.querySelectorAll('.newDivLabelDisplay');
                    for (const label of labels) {
                        label.setAttribute('id', 'newDivLabelDisplay' + j);
                        j++
                    }
                }
                
            };
            
        } 
            
        buttonSaveObjectives.classList.remove('button-save-objectives-display');
        buttonSaveObjectives.classList.add('button-save-objectives');
    });
}