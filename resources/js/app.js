import './bootstrap';

import Alpine from 'alpinejs';
import TomSelect from 'tom-select';

window.Alpine = Alpine;
window.TomSelect = TomSelect;

Alpine.start();

// Initialize Tom Select on elements
document.addEventListener('DOMContentLoaded', function() {
    // Common Tom Select configuration
    const tomSelectConfig = {
        plugins: ['remove_button'],
        maxItems: null,
        create: false,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    };

    // Initialize language select with Tom Select
    const languageSelect = document.getElementById('languages-select');
    if (languageSelect) {
        new TomSelect(languageSelect, {
            ...tomSelectConfig,
            placeholder: 'Search and select languages...'
        });
    }

    // Initialize expertise areas select with Tom Select
    const expertiseSelect = document.getElementById('expertise-areas-select');
    if (expertiseSelect) {
        new TomSelect(expertiseSelect, {
            ...tomSelectConfig,
            placeholder: 'Search and select expertise areas...'
        });
    }

    // Initialize regions select with Tom Select
    const regionsSelect = document.getElementById('regions-select');
    if (regionsSelect) {
        new TomSelect(regionsSelect, {
            ...tomSelectConfig,
            placeholder: 'Search and select regions...'
        });
    }
});
