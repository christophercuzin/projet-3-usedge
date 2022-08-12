const viewAllSubmissionsButtons = document.getElementsByClassName('view_all_submissions');
const modalViewAllSubmissions = document.getElementsByClassName('modal_view_all_submissions');

for (const modalViewAllSubmission of modalViewAllSubmissions) {
    for (const viewAllSubmissionsButton of viewAllSubmissionsButtons) {
        viewAllSubmissionsButton.addEventListener('click', () => {
            
            modalViewAllSubmission.classList.add('modal_view_all_submissions_display');
            
        });
    }
}