function manageSelectPersistence(selectId, storageKey) {
    const select = document.getElementById(selectId);

    if (!select) {
        console.error(`Element with ID '${selectId}' not found.`);
        return;
    }

    const savedValue = localStorage.getItem(storageKey);
    if (savedValue) {
        select.value = savedValue;
    }

    select.addEventListener('change', () => {
        localStorage.setItem(storageKey, select.value);
    });
}