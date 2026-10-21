function setDropZoneState(dropZone, isActive) {
    dropZone.dataset.active = isActive ? 'true' : 'false';
}

function updateProgress(root, progress) {
    const progressBar = root.querySelector('[data-upload-progress]');
    const status = root.querySelector('[data-upload-status]');
    const percentage = Math.max(0, Math.min(100, Number(progress) || 0));

    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
    }

    if (status) {
        status.textContent = percentage < 100
            ? `Subiendo imagen · ${Math.round(percentage)}%`
            : 'Extrayendo metadatos EXIF';
    }
}

function initializeUpload(root) {
    if (root.dataset.uploadInitialized === 'true') {
        return;
    }

    const dropZone = root.querySelector('[data-image-drop-zone]');
    const input = root.querySelector('[data-image-input]');

    if (! dropZone || ! input) {
        return;
    }

    root.dataset.uploadInitialized = 'true';

    ['dragenter', 'dragover'].forEach((eventName) => {
        dropZone.addEventListener(eventName, (event) => {
            event.preventDefault();
            setDropZoneState(dropZone, true);
        });
    });

    ['dragleave', 'dragend'].forEach((eventName) => {
        dropZone.addEventListener(eventName, () => {
            setDropZoneState(dropZone, false);
        });
    });

    dropZone.addEventListener('drop', (event) => {
        event.preventDefault();
        setDropZoneState(dropZone, false);

        const [file] = event.dataTransfer?.files ?? [];

        if (! file) {
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);
        input.files = transfer.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    input.addEventListener('livewire-upload-start', () => updateProgress(root, 0));
    input.addEventListener('livewire-upload-progress', (event) => {
        updateProgress(root, event.detail.progress);
    });
    input.addEventListener('livewire-upload-finish', () => updateProgress(root, 100));
    input.addEventListener('livewire-upload-error', () => updateProgress(root, 0));
    input.addEventListener('livewire-upload-cancel', () => updateProgress(root, 0));
}

export function initImageMetadataUploads(root = document) {
    root.querySelectorAll('[data-image-metadata-upload]').forEach(initializeUpload);
}
