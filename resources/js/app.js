import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


if (typeof window.validateFile !== 'function') {
    window.validateFile = function (input) {
        const file = input?.files?.[0];

        if (!file) {
            return;
        }

        const fileSize = file.size / 1024 / 1024;
        const fileType = file.type;

        if (fileSize > 5) {
            alert('Ukuran file terlalu besar. Maksimal 5MB');
            input.value = '';
            return;
        }

        if (!['image/jpeg', 'image/jpg', 'image/png'].includes(fileType)) {
            alert('Format file tidak didukung. Gunakan JPG atau PNG');
            input.value = '';
        }
    };
}

document.addEventListener('change', function (event) {
    const input = event.target;
    if (!(input instanceof HTMLInputElement)) {
        return;
    }

    if (input.type !== 'file') {
        return;
    }

    if (!input.matches('.js-validate-file, [data-validate-file="image"]')) {
        return;
    }

    window.validateFile(input);
});
        
