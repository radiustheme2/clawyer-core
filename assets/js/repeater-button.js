function repeaterButtonText(){
    // Function define করুন
    function addButtonTexts() {
        const plusButtons = document.querySelectorAll('.anticon.anticon-plus-circle');
        plusButtons.forEach(function(button) {
            if (!button.querySelector('.btn-text')) {
                button.querySelector('svg').style.display = 'none';
                button.insertAdjacentHTML('beforeend', '<span class="btn-text"><i class="icon-plus-2"></i>' + ClawyerCoreObj.newItem + '</span>');
            }
        });
    }

    // Initial call
    addButtonTexts();

    // Every 500ms check করুন
    setInterval(addButtonTexts, 500);

    // Document এ click event এর পর run করুন
    document.addEventListener('click', function() {
        setTimeout(addButtonTexts, 200);
    });
}

repeaterButtonText();