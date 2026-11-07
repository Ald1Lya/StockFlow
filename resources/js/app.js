document.querySelectorAll('[data-search-select]').forEach((container) => {
    const input = container.querySelector('[data-search-select-input]');
    const value = container.querySelector('[data-search-select-value]');
    const results = container.querySelector('[data-search-select-results]');
    const status = container.querySelector('[data-search-select-status]');
    const label = container.dataset.searchLabel;
    const allowEmpty = container.dataset.searchAllowEmpty === 'true';
    let activeIndex = -1;
    let searchTimeout;
    let requestController;
    let requestVersion = 0;

    const optionButtons = () => Array.from(results.querySelectorAll('[role="option"]'));

    const closeResults = () => {
        window.clearTimeout(searchTimeout);
        requestVersion += 1;
        requestController?.abort();
        results.classList.add('hidden');
        input.setAttribute('aria-expanded', 'false');
        input.removeAttribute('aria-activedescendant');
        activeIndex = -1;
    };

    const updateValidity = () => {
        const hasUnselectedText = input.value.trim() !== '' && value.value === '';

        input.setCustomValidity(
            hasUnselectedText ? `Pilih ${label} dari hasil pencarian.` : '',
        );
    };

    const selectOption = (option) => {
        value.value = option?.id ?? '';
        input.value = option?.name ?? '';
        updateValidity();
        closeResults();
    };

    const renderOptions = (options, hasMore) => {
        results.replaceChildren();
        activeIndex = -1;
        let optionId = 0;

        const addOption = (option, text, selected = false) => {
            const button = document.createElement('button');

            button.id = `${input.id}-option-${optionId++}`;
            button.type = 'button';
            button.setAttribute('role', 'option');
            button.setAttribute('aria-selected', String(selected));
            button.className = 'w-full cursor-pointer px-3 py-2 text-left text-[13px] text-[#3a352d] hover:bg-[#f7f4ee] focus:bg-[#f7f4ee] focus:outline-none';
            button.textContent = text;
            button.addEventListener('pointerdown', (event) => {
                event.preventDefault();
            });
            button.addEventListener('click', () => selectOption(option));
            results.append(button);
        };

        if (allowEmpty) {
            addOption(null, 'Tanpa brand', value.value === '');
        }

        options.forEach((option) => {
            addOption(option, option.name, String(option.id) === value.value);
        });

        const resultCount = options.length;

        if (resultCount === 0 && !allowEmpty) {
            status.textContent = 'Tidak ada hasil. Coba kata pencarian lain.';
        } else if (hasMore) {
            status.textContent = 'Menampilkan 10 hasil teratas. Tambahkan kata pencarian agar lebih spesifik.';
        } else {
            status.textContent = `${resultCount} hasil ditemukan.`;
        }

        results.classList.remove('hidden');
        input.setAttribute('aria-expanded', 'true');
    };

    const searchOptions = (delay = 250) => {
        window.clearTimeout(searchTimeout);
        requestController?.abort();
        const currentVersion = ++requestVersion;

        results.replaceChildren();
        results.classList.remove('hidden');
        input.setAttribute('aria-expanded', 'true');
        status.textContent = 'Mencari...';

        searchTimeout = window.setTimeout(async () => {
            requestController = new AbortController();
            const parameters = new URLSearchParams({
                type: container.dataset.searchType,
                search: input.value.trim(),
            });

            try {
                const response = await fetch(`${container.dataset.searchUrl}?${parameters}`, {
                    headers: { Accept: 'application/json' },
                    signal: requestController.signal,
                });

                if (!response.ok) {
                    throw new Error(`Pencarian ${label} gagal (${response.status}).`);
                }

                const payload = await response.json();

                if (currentVersion === requestVersion) {
                    renderOptions(payload.data, payload.has_more);
                }
            } catch (error) {
                if (error.name === 'AbortError' || currentVersion !== requestVersion) {
                    return;
                }

                status.textContent = `Pencarian ${label} gagal. Coba lagi.`;
                console.error(error);
            }
        }, delay);
    };

    input.addEventListener('focus', () => searchOptions(0));
    input.addEventListener('blur', () => {
        window.setTimeout(closeResults, 150);
    });
    input.addEventListener('input', () => {
        value.value = '';
        updateValidity();
        searchOptions();
    });
    input.addEventListener('keydown', (event) => {
        const buttons = optionButtons();

        if (event.key === 'ArrowDown' && buttons.length > 0) {
            event.preventDefault();
            activeIndex = Math.min(activeIndex + 1, buttons.length - 1);
        } else if (event.key === 'ArrowUp' && buttons.length > 0) {
            event.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
        } else if (event.key === 'Enter' && activeIndex >= 0) {
            event.preventDefault();
            buttons[activeIndex]?.click();
        } else if (event.key === 'Escape') {
            closeResults();
        } else {
            return;
        }

        buttons.forEach((button, index) => {
            button.setAttribute('aria-selected', String(index === activeIndex));
        });

        const activeOption = buttons[activeIndex];

        if (activeOption) {
            input.setAttribute('aria-activedescendant', activeOption.id || '');
            activeOption.scrollIntoView({ block: 'nearest' });
        } else {
            input.removeAttribute('aria-activedescendant');
        }
    });

    updateValidity();
});
