console.log('loopis-admin-validate.js loaded');

wp.domReady(() => {

    const { subscribe, select, dispatch } = wp.data;

    let locked = false;

    subscribe(() => {

        const isSaving = select('core/editor').isSavingPost();

        if (!isSaving) return;

        const inputs = document.querySelectorAll('.loopis-url');

        let valid = true;

        inputs.forEach(input => {
            const v = input.value.trim();

            if (v !== '' && !v.startsWith('https://')) {
                input.style.border = '2px solid red';
                valid = false;
            } else {
                input.style.border = '';
            }
        });

        if (!valid && !locked) {
            locked = true;

            dispatch('core/editor').lockPostSaving('loopis-url');

            alert('Only https:// URLs allowed');

            setTimeout(() => {
                dispatch('core/editor').unlockPostSaving('loopis-url');
                locked = false;
            }, 1000);
        }

    });

});