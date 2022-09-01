if (document.getElementById('research-template-list-card')) {
    const inputCopyLinks = document.querySelectorAll('.copy-link');
    const buttonCopyLinks = document.querySelectorAll('.research-template-list-card-link-button');

    for (const buttonCopyLink of buttonCopyLinks) {
        buttonCopyLink.addEventListener('click', () => {
            const textContent = buttonCopyLink.innerHTML;
            const idOfButtonCopyLink = buttonCopyLink.getAttribute('data-id');
            for (const inputCopyLink of inputCopyLinks) {
                const idOfInputCopyLink = inputCopyLink.getAttribute('data-id');
                if (idOfButtonCopyLink === idOfInputCopyLink) {
                    const link = inputCopyLink.value;
                    navigator.clipboard.writeText(link).then(() => {
                        buttonCopyLink.innerHTML = 'The link was successfully copied !';
                    });
                }
            }
            setTimeout(function() {
                buttonCopyLink.innerHTML = textContent;
            },5000);
        })

    }
}