import './bootstrap';

const normalizeSearchText = (value) => value
	.toLowerCase()
	.normalize('NFD')
	.replace(/[\u0300-\u036f]/g, '');

const setupCategoryComboboxes = () => {
	document.querySelectorAll('[data-category-combobox]').forEach((combobox) => {
		if (combobox.dataset.ready === 'true') {
			return;
		}

		const select = combobox.querySelector('[data-category-native]');
		const enhanced = combobox.querySelector('[data-category-enhanced]');
		const toggle = combobox.querySelector('[data-category-toggle]');
		const panel = combobox.querySelector('[data-category-panel]');
		const search = combobox.querySelector('[data-category-search]');
		const current = combobox.querySelector('[data-category-current]');
		const empty = combobox.querySelector('[data-category-empty]');
		const options = Array.from(combobox.querySelectorAll('[data-category-option]'));

		if (! select || ! enhanced || ! toggle || ! panel || ! search || ! current || options.length === 0) {
			return;
		}

		const close = () => {
			panel.classList.add('hidden');
			toggle.setAttribute('aria-expanded', 'false');
		};

		const filterOptions = () => {
			const query = normalizeSearchText(search.value.trim());
			let visibleOptions = 0;

			options.forEach((option) => {
				const label = normalizeSearchText(option.dataset.label || option.textContent || '');
				const isVisible = label.includes(query);

				option.closest('li')?.classList.toggle('hidden', ! isVisible);
				visibleOptions += isVisible ? 1 : 0;
			});

			empty?.classList.toggle('hidden', visibleOptions > 0);
		};

		const open = () => {
			panel.classList.remove('hidden');
			toggle.setAttribute('aria-expanded', 'true');
			search.value = '';
			filterOptions();
			search.focus();
		};

		const setSelectedOption = (option) => {
			select.value = option.dataset.value || '';
			current.textContent = option.dataset.label || option.textContent.trim();
			current.classList.remove('text-(--cb-outline)');
			current.classList.add('text-(--cb-text)');

			options.forEach((item) => {
				item.setAttribute('aria-selected', item === option ? 'true' : 'false');
			});

			select.dispatchEvent(new Event('change', { bubbles: true }));
			close();
			toggle.focus();
		};

		combobox.dataset.ready = 'true';
		select.classList.add('hidden');
		enhanced.classList.remove('hidden');

		toggle.addEventListener('click', () => {
			const isOpen = toggle.getAttribute('aria-expanded') === 'true';
			isOpen ? close() : open();
		});

		search.addEventListener('input', filterOptions);

		search.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				close();
				toggle.focus();
			}

			if (event.key === 'Enter') {
				const firstVisibleOption = options.find((option) => ! option.closest('li')?.classList.contains('hidden'));

				if (firstVisibleOption) {
					event.preventDefault();
					setSelectedOption(firstVisibleOption);
				}
			}
		});

		options.forEach((option) => {
			option.addEventListener('click', () => setSelectedOption(option));
		});

		document.addEventListener('click', (event) => {
			if (! combobox.contains(event.target)) {
				close();
			}
		});
	});
};

const setupImagePreviews = () => {
	document.querySelectorAll('[data-image-input]').forEach((input) => {
		if (input.dataset.ready === 'true') {
			return;
		}

		const preview = document.getElementById(input.dataset.previewTarget || '');
		const image = preview?.querySelector('[data-preview-image]');

		if (! preview || ! image) {
			return;
		}

		input.dataset.ready = 'true';

		input.addEventListener('change', () => {
			if (input.dataset.previewUrl) {
				URL.revokeObjectURL(input.dataset.previewUrl);
				delete input.dataset.previewUrl;
			}

			const [file] = input.files || [];

			if (! file || ! file.type.startsWith('image/')) {
				image.removeAttribute('src');
				preview.classList.add('hidden');
				return;
			}

			const previewUrl = URL.createObjectURL(file);

			input.dataset.previewUrl = previewUrl;
			image.src = previewUrl;
			preview.classList.remove('hidden');
		});
	});
};

const setupLocationPickers = () => {
	document.querySelectorAll('[data-location-picker]').forEach((picker) => {
		if (picker.dataset.ready === 'true') {
			return;
		}

		const button = picker.querySelector('[data-location-button]');
		const latitudeInput = picker.querySelector('[data-location-latitude]');
		const longitudeInput = picker.querySelector('[data-location-longitude]');
		const status = picker.querySelector('[data-location-status]');

		if (! button || ! latitudeInput || ! longitudeInput || ! status) {
			return;
		}

		const setStatus = (message, isError = false) => {
			status.textContent = message;
			status.classList.remove('hidden', 'text-[#93000a]', 'text-(--cb-muted)');
			status.classList.add(isError ? 'text-[#93000a]' : 'text-(--cb-muted)');
		};

		picker.dataset.ready = 'true';

		button.addEventListener('click', () => {
			if (! navigator.geolocation) {
				setStatus('Tu navegador no permite obtener la ubicación automática.', true);
				return;
			}

			button.disabled = true;
			setStatus('Obteniendo ubicación...');

			navigator.geolocation.getCurrentPosition(
				(position) => {
					latitudeInput.value = position.coords.latitude.toFixed(6);
					longitudeInput.value = position.coords.longitude.toFixed(6);
					button.disabled = false;
					setStatus('Ubicación agregada al formulario.');
				},
				() => {
					button.disabled = false;
					setStatus('No se pudo obtener la ubicación. Podés cargar las coordenadas manualmente.', true);
				},
				{
					enableHighAccuracy: true,
					timeout: 10000,
					maximumAge: 60000,
				},
			);
		});
	});
};

const setupPublicInteractions = () => {
	setupCategoryComboboxes();
	setupImagePreviews();
	setupLocationPickers();
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', setupPublicInteractions);
} else {
	setupPublicInteractions();
}
