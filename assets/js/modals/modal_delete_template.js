const buttonDeleteTemplates = document.getElementsByClassName("action_modal");
const actionsMenuContainerArchives = document.getElementsByClassName("modal_delete_template");
const fullScreenActionMenuContainerModalCloseArchives =
    document.getElementsByClassName("no_keep_the_template");
let i = 1;
for (const buttonDeleteTemplate of buttonDeleteTemplates) {
    for (const actionsMenuContainerArchive of actionsMenuContainerArchives) {
        buttonDeleteTemplate.addEventListener("click", function () {
            actionsMenuContainerArchive.classList.add(
                "actions-menu-container-flex_archive"
            );
        });
        
        for (const fullScreenActionMenuContainerModalCloseArchive of fullScreenActionMenuContainerModalCloseArchives) {
            const actionsMenuContainer = document.getElementById('actions-menu-container' + i);
            const fullScreenActionMenuContainerModalClose = document.getElementById('full-screen-action-menu-container-modal-close' + i);
            fullScreenActionMenuContainerModalCloseArchive.addEventListener("click", function () {
                actionsMenuContainerArchive.classList.remove("actions-menu-container-flex_archive");
                actionsMenuContainer.classList.remove('actions-menu-container-flex');
                fullScreenActionMenuContainerModalClose.classList.remove('full-screen-action-menu-container-modal-close-display');
                
            });
            i++
        }
    }
    
}