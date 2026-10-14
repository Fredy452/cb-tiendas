import './bootstrap';
import EmblaCarousel from 'embla-carousel';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const setupFlashAlerts = async () => {
	const container = document.querySelector('[data-flash-alerts]');

	if (! container) {
		return;
	}

	let alerts = [];

	try {
		alerts = JSON.parse(container.dataset.flashAlerts || '[]');
	} catch {
		return;
	}

	for (const alert of alerts) {
		const content = document.createElement('div');
		content.className = 'cb-swal-messages';

		(alert.messages || []).forEach((message) => {
			const paragraph = document.createElement('p');
			paragraph.textContent = message;
			content.appendChild(paragraph);
		});

		await Swal.fire({
			icon: alert.type,
			titleText: alert.title,
			html: content,
			confirmButtonText: 'Entendido',
			buttonsStyling: false,
			customClass: {
				popup: 'cb-swal-popup',
				title: 'cb-swal-title',
				htmlContainer: 'cb-swal-content',
				confirmButton: 'cb-swal-confirm',
			},
		});
	}
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

const setupRegistrationFileSizeValidation = () => {
	document.querySelectorAll('[data-store-registration-form]').forEach((form) => {
		if (form.dataset.ready === 'true') {
			return;
		}

		const fileInputs = Array.from(form.querySelectorAll('input[type="file"][data-max-size-kb]'));

		if (fileInputs.length === 0) {
			return;
		}

		const escapeHtml = (value) => {
			const div = document.createElement('div');
			div.textContent = String(value);
			return div.innerHTML;
		};

		const showErrors = async (messages) => {
			const content = document.createElement('div');
			content.className = 'cb-swal-messages';

			messages.forEach((message) => {
				const paragraph = document.createElement('p');
				paragraph.innerHTML = escapeHtml(message);
				content.appendChild(paragraph);
			});

			await Swal.fire({
				icon: 'error',
				titleText: 'Revisá los archivos cargados',
				html: content,
				confirmButtonText: 'Entendido',
				buttonsStyling: false,
				customClass: {
					popup: 'cb-swal-popup',
					title: 'cb-swal-title',
					htmlContainer: 'cb-swal-content',
					confirmButton: 'cb-swal-confirm',
				},
			});
		};

		const validateFiles = () => {
			const messages = [];

			fileInputs.forEach((input) => {
				const [file] = input.files || [];

				if (! file) {
					return;
				}

				const maxSizeKb = Number(input.dataset.maxSizeKb || 0);

				if (! maxSizeKb || file.size <= maxSizeKb * 1024) {
					return;
				}

				const label = input.labels?.[0]?.textContent?.trim() || input.name;
				messages.push(`El archivo ${label.toLowerCase()} no debe superar los ${maxSizeKb} KB.`);
			});

			return messages;
		};

		form.dataset.ready = 'true';

		form.addEventListener('submit', async (event) => {
			const messages = validateFiles();

			if (messages.length === 0) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();

			await showErrors(messages);
			fileInputs.find((input) => input.files?.length > 0)?.focus();
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

const setupFeaturedCarousels = () => {
	const setupCarousel = ({
		carouselSelector,
		viewportSelector,
	}) => {
		document.querySelectorAll(carouselSelector).forEach((carousel) => {
		if (carousel.dataset.ready === 'true') {
			return;
		}

		const viewport = carousel.querySelector(viewportSelector);
		const prevButton = carousel.querySelector('[data-carousel-prev]');
		const nextButton = carousel.querySelector('[data-carousel-next]');
		const toggleButton = carousel.querySelector('[data-carousel-toggle]');
		const toggleIcon = carousel.querySelector('[data-carousel-toggle-icon]');

		if (! viewport) {
			return;
		}

		carousel.dataset.ready = 'true';
		const embla = EmblaCarousel(viewport, {
			align: 'start',
			containScroll: 'trimSnaps',
			dragFree: false,
			loop: true,
			slidesToScroll: 1,
		});

		const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
		let autoplayTimer = null;
		let isPaused = reducedMotion.matches;

		const stopAutoplay = () => {
			if (autoplayTimer) {
				window.clearInterval(autoplayTimer);
				autoplayTimer = null;
			}
		};

		const startAutoplay = () => {
			if (isPaused || autoplayTimer) {
				return;
			}

			autoplayTimer = window.setInterval(() => embla.scrollNext(), 4000);
		};

		const updateToggle = () => {
			if (! toggleButton) {
				return;
			}

			toggleButton.disabled = reducedMotion.matches;
			toggleButton.setAttribute('aria-pressed', isPaused ? 'true' : 'false');
			toggleButton.setAttribute('aria-label', isPaused ? 'Reanudar avance automático' : 'Pausar avance automático');
			toggleButton.title = isPaused ? 'Reanudar avance automático' : 'Pausar avance automático';

			if (toggleIcon) {
				toggleIcon.textContent = isPaused ? 'play_arrow' : 'pause';
			}
		};

		updateToggle();
		startAutoplay();

		carousel.addEventListener('mouseenter', stopAutoplay);
		carousel.addEventListener('mouseleave', startAutoplay);
		carousel.addEventListener('focusin', stopAutoplay);
		carousel.addEventListener('focusout', (event) => {
			if (! carousel.contains(event.relatedTarget)) {
				startAutoplay();
			}
		});
		toggleButton?.addEventListener('click', () => {
			isPaused = ! isPaused;
			updateToggle();
			isPaused ? stopAutoplay() : startAutoplay();
		});
		reducedMotion.addEventListener('change', (event) => {
			if (event.matches) {
				isPaused = true;
				stopAutoplay();
			}

			updateToggle();
		});
		embla.on('destroy', stopAutoplay);

		const updateButtons = () => {
			if (! prevButton || ! nextButton) {
				return;
			}

			prevButton.disabled = ! embla.canScrollPrev();
			nextButton.disabled = ! embla.canScrollNext();
		};

		prevButton?.addEventListener('click', () => embla.scrollPrev());
		nextButton?.addEventListener('click', () => embla.scrollNext());

		embla.on('select', updateButtons);
		embla.on('reInit', updateButtons);
		updateButtons();
		});
	};

	setupCarousel({
		carouselSelector: '[data-featured-carousel]',
		viewportSelector: '[data-featured-viewport]',
	});

	setupCarousel({
		carouselSelector: '[data-home-carousel]',
		viewportSelector: '[data-home-viewport]',
	});
};

const setupRelatedCarousels = () => {
	document.querySelectorAll('[data-related-carousel]').forEach((carousel) => {
		if (carousel.dataset.ready === 'true') {
			return;
		}

		const viewport = carousel.querySelector('[data-related-viewport]');

		if (! viewport) {
			return;
		}

		carousel.dataset.ready = 'true';
		const embla = EmblaCarousel(viewport, {
			align: 'start',
			containScroll: 'trimSnaps',
			dragFree: false,
			loop: true,
			slidesToScroll: 1,
		});

	});
};

const setupCategoryFilters = () => {
	const grouped = new Map();

	document.querySelectorAll('input[data-category-checkbox]').forEach((checkbox) => {
		const value = String(checkbox.value);

		if (! grouped.has(value)) {
			grouped.set(value, []);
		}

		grouped.get(value).push(checkbox);
	});

	grouped.forEach((checkboxes) => {
		checkboxes.forEach((checkbox) => {
			if (checkbox.dataset.ready === 'true') {
				return;
			}

			checkbox.dataset.ready = 'true';
			checkbox.addEventListener('change', () => {
				const checkedState = checkbox.checked;

				checkboxes.forEach((item) => {
					item.checked = checkedState;
				});

				const selectedValues = [...document.querySelectorAll('input[data-category-checkbox]')]
					.filter((input) => input.checked)
					.map((input) => input.value)
					.filter((value, index, array) => array.indexOf(value) === index);

				const url = new URL(window.location.href);
				url.searchParams.delete('page');
				url.searchParams.delete('categories[]');

				selectedValues.forEach((value) => {
					url.searchParams.append('categories[]', value);
				});

				window.location.href = url.toString();
			});
		});
	});
};

const setupMobileCategoryPanels = () => {
	document.querySelectorAll('[data-mobile-categories-toggle]').forEach((toggle) => {
		if (toggle.dataset.ready === 'true') {
			return;
		}

		const panelId = toggle.getAttribute('aria-controls');
		const panel = panelId ? document.getElementById(panelId) : null;
		const layer = panel?.closest('[data-mobile-categories-layer]');
		const overlay = layer?.querySelector('[data-mobile-categories-overlay]');
		const closeButton = panel?.querySelector('[data-mobile-categories-close]');
		const icon = toggle.querySelector('[data-mobile-categories-icon]');

		if (! panel || ! layer) {
			return;
		}

		const setExpanded = (expanded) => {
			toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
			layer.classList.toggle('pointer-events-none', ! expanded);
			layer.classList.toggle('opacity-0', ! expanded);
			panel.classList.toggle('translate-x-full', ! expanded);
			document.body.classList.toggle('overflow-hidden', expanded);

			if (icon) {
				icon.textContent = expanded ? 'expand_less' : 'expand_more';
			}
		};

		toggle.dataset.ready = 'true';
		setExpanded(false);

		toggle.addEventListener('click', () => {
			const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
			setExpanded(! isExpanded);
		});

		overlay?.addEventListener('click', () => setExpanded(false));
		closeButton?.addEventListener('click', () => setExpanded(false));

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				setExpanded(false);
			}
		});
	});
};

const setupMobileNavDrawer = () => {
	document.querySelectorAll('[data-mobile-nav-toggle]').forEach((toggle) => {
		if (toggle.dataset.ready === 'true') {
			return;
		}

		const layer = document.querySelector('[data-mobile-nav-layer]');
		const drawer = layer?.querySelector('[data-mobile-nav-drawer]');
		const overlay = layer?.querySelector('[data-mobile-nav-overlay]');
		const closeButton = layer?.querySelector('[data-mobile-nav-close]');

		if (! layer || ! drawer || ! overlay || ! closeButton) {
			return;
		}

		const closeDrawer = (restoreFocus = true) => {
			toggle.setAttribute('aria-expanded', 'false');
			layer.classList.add('pointer-events-none');
			layer.style.opacity = '0';
			drawer.style.translate = '100%';
			document.body.classList.remove('overflow-hidden');

			if (restoreFocus) {
				toggle.focus();
			}
		};

		const openDrawer = () => {
			toggle.setAttribute('aria-expanded', 'true');
			layer.classList.remove('pointer-events-none');
			layer.style.opacity = '1';
			drawer.style.translate = '0';
			document.body.classList.add('overflow-hidden');
			closeButton.focus();
		};

		toggle.dataset.ready = 'true';
		closeDrawer(false);

		toggle.addEventListener('click', () => {
			const isOpen = toggle.getAttribute('aria-expanded') === 'true';
			isOpen ? closeDrawer() : openDrawer();
		});

		overlay.addEventListener('click', closeDrawer);
		closeButton.addEventListener('click', closeDrawer);

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				closeDrawer();
			}

			if (event.key !== 'Tab' || toggle.getAttribute('aria-expanded') !== 'true') {
				return;
			}

			const focusableElements = Array.from(drawer.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
			)).filter((element) => element.getClientRects().length > 0);
			const firstElement = focusableElements[0];
			const lastElement = focusableElements.at(-1);

			if (! firstElement || ! lastElement) {
				event.preventDefault();
				return;
			}

			if (event.shiftKey && document.activeElement === firstElement) {
				event.preventDefault();
				lastElement.focus();
			} else if (! event.shiftKey && document.activeElement === lastElement) {
				event.preventDefault();
				firstElement.focus();
			}
		});
	});
};

const setupSearchAutocomplete = () => {
	document.querySelectorAll('[data-search-wrapper]').forEach((wrapper) => {
		if (wrapper.dataset.ready === 'true') {
			return;
		}

		const input = wrapper.querySelector('[data-search-input]');
		const form = wrapper.querySelector('[data-search-form]');
		const panel = wrapper.querySelector('[data-search-panel]');

		if (! input || ! form || ! panel) {
			return;
		}

		const popularSection = panel.querySelector('[data-search-popular]');
		const popularList = panel.querySelector('[data-popular-list]');
		const popularEmpty = panel.querySelector('[data-popular-empty]');
		const loadingEl = panel.querySelector('[data-search-loading]');
		const emptyEl = panel.querySelector('[data-search-empty]');
		const queryEl = panel.querySelector('[data-search-query]');
		const resultsList = panel.querySelector('[data-search-results]');
		const apiUrl = panel.dataset.searchUrl || '/buscar/sugerencias';

		const STORAGE_KEY = 'cb_recent_searches';
		const MAX_RECENT = 5;

		let debounceTimer = null;
		let abortController = null;
		let activeIndex = -1;

		wrapper.dataset.ready = 'true';

		const getRecent = () => {
			try {
				return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
			} catch {
				return [];
			}
		};

		const saveRecent = (query) => {
			const q = query.trim();

			if (! q) {
				return;
			}

			const recent = getRecent().filter((item) => item !== q);
			recent.unshift(q);
			localStorage.setItem(STORAGE_KEY, JSON.stringify(recent.slice(0, MAX_RECENT)));
		};

		const escapeHtml = (str) => {
			const div = document.createElement('div');
			div.textContent = String(str);
			return div.innerHTML;
		};

		const highlightMatch = (text, query) => {
			const safe = escapeHtml(text);
			const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

			return safe.replace(new RegExp(`(${escapedQuery})`, 'gi'), '<mark class="cb-search-highlight">$1</mark>');
		};

		const resultOptions = () => Array.from(resultsList.querySelectorAll('[data-search-option]'))
			.filter((option) => option.getClientRects().length > 0);

		const setActiveOption = (index) => {
			const options = resultOptions();

			if (options.length === 0) {
				return;
			}

			activeIndex = (index + options.length) % options.length;
			options.forEach((option, optionIndex) => {
				option.setAttribute('aria-selected', optionIndex === activeIndex ? 'true' : 'false');
			});

			const activeOption = options[activeIndex];
			input.setAttribute('aria-activedescendant', activeOption.id);
			activeOption.scrollIntoView({ block: 'nearest' });
		};

		const showPanel = () => {
			panel.classList.remove('hidden');
			input.setAttribute('aria-expanded', 'true');
		};
		const hidePanel = () => {
			panel.classList.add('hidden');
			activeIndex = -1;
			input.setAttribute('aria-expanded', 'false');
			input.removeAttribute('aria-activedescendant');
		};

		const hideAllStates = () => {
			popularSection.classList.add('hidden');
			loadingEl.classList.add('hidden');
			emptyEl.classList.add('hidden');
			resultsList.classList.add('hidden');
		};

		const renderPopular = () => {
			const recent = getRecent();

			popularList.innerHTML = '';

			if (recent.length === 0) {
				popularEmpty.classList.remove('hidden');
			} else {
				popularEmpty.classList.add('hidden');

				recent.forEach((query) => {
					const li = document.createElement('li');
					const btn = document.createElement('button');

					btn.type = 'button';
					btn.className = 'cb-search-popular-item';
					btn.innerHTML = `<span class="material-symbols-outlined text-[18px] text-(--cb-outline)">schedule</span><span>${escapeHtml(query)}</span>`;

					btn.addEventListener('click', () => {
						input.value = query;
						saveRecent(query);
						form.submit();
					});

					li.appendChild(btn);
					popularList.appendChild(li);
				});
			}

			hideAllStates();
			popularSection.classList.remove('hidden');
			showPanel();
		};

		const renderResults = (results, query) => {
			resultsList.innerHTML = '';
			activeIndex = -1;
			input.removeAttribute('aria-activedescendant');

			results.forEach((store, index) => {
				const li = document.createElement('li');
				const a = document.createElement('a');

				a.href = store.url;
				a.className = 'cb-search-result-item';
				a.id = `${input.id}-option-${index}`;
				a.dataset.searchOption = 'true';
				a.setAttribute('role', 'option');
				a.setAttribute('aria-selected', 'false');

				const thumbHtml = store.thumbnail
					? `<img src="${escapeHtml(store.thumbnail)}" alt="${escapeHtml(store.name)}" loading="lazy">`
					: `<span class="material-symbols-outlined text-[22px] text-(--cb-outline)">storefront</span>`;

				a.innerHTML = `
					<div class="cb-search-result-thumb">${thumbHtml}</div>
					<div class="cb-search-result-body">
						<p class="cb-search-result-name">${highlightMatch(store.name, query)}</p>
						${store.category ? `<p class="cb-search-result-meta">Cat. ${escapeHtml(store.category)}</p>` : ''}
						${store.description ? `<p class="cb-search-result-desc">${escapeHtml(store.description)}</p>` : ''}
					</div>`;

				a.addEventListener('click', () => saveRecent(query));
				li.appendChild(a);
				resultsList.appendChild(li);
			});

			hideAllStates();
			resultsList.classList.remove('hidden');
			showPanel();
		};

		const fetchSuggestions = async (query) => {
			abortController?.abort();
			abortController = new AbortController();

			hideAllStates();
			loadingEl.classList.remove('hidden');
			showPanel();

			try {
				const res = await fetch(`${apiUrl}?q=${encodeURIComponent(query)}`, {
					signal: abortController.signal,
					headers: { Accept: 'application/json' },
				});
				const results = await res.json();

				if (results.length === 0) {
					hideAllStates();

					if (queryEl) {
						queryEl.textContent = query;
					}

					emptyEl.classList.remove('hidden');
					showPanel();
				} else {
					renderResults(results, query);
				}
			} catch (err) {
				if (err.name !== 'AbortError') {
					hidePanel();
				}
			}
		};

		input.addEventListener('focus', () => {
			if (! input.value.trim()) {
				renderPopular();
			}
		});

		input.addEventListener('input', () => {
			clearTimeout(debounceTimer);
			activeIndex = -1;
			input.removeAttribute('aria-activedescendant');
			const query = input.value.trim();

			if (! query) {
				renderPopular();
				return;
			}

			if (query.length < 2) {
				hidePanel();
				return;
			}

			debounceTimer = setTimeout(() => fetchSuggestions(query), 280);
		});

		input.addEventListener('keydown', (e) => {
			if (e.key === 'ArrowDown') {
				if (resultOptions().length > 0) {
					e.preventDefault();
					setActiveOption(activeIndex + 1);
				}
			}

			if (e.key === 'ArrowUp') {
				if (resultOptions().length > 0) {
					e.preventDefault();
					setActiveOption(activeIndex - 1);
				}
			}

			if (e.key === 'Enter' && activeIndex >= 0) {
				const activeOption = resultOptions()[activeIndex];

				if (activeOption) {
					e.preventDefault();
					activeOption.click();
				}
			}

			if (e.key === 'Escape') {
				hidePanel();
				input.blur();
			}
		});

		form.addEventListener('submit', () => {
			saveRecent(input.value.trim());
			hidePanel();
		});

		document.addEventListener('click', (e) => {
			if (! wrapper.contains(e.target)) {
				hidePanel();
			}
		});
	});
};

const setupPublicInteractions = () => {
	setupFlashAlerts();
	setupImagePreviews();
	setupRegistrationFileSizeValidation();
	setupLocationPickers();
	setupFeaturedCarousels();
	setupRelatedCarousels();
	setupCategoryFilters();
	setupMobileCategoryPanels();
	setupMobileNavDrawer();
	setupSearchAutocomplete();
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', setupPublicInteractions);
} else {
	setupPublicInteractions();
}
