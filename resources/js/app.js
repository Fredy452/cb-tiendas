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

const setupFeaturedCarousels = () => {
	const setupCarousel = ({
		carouselSelector,
		viewportSelector,
		sectionSelector,
		prevSelector,
		nextSelector,
	}) => {
		document.querySelectorAll(carouselSelector).forEach((carousel) => {
		if (carousel.dataset.ready === 'true') {
			return;
		}

		const viewport = carousel.querySelector(viewportSelector);
		const section = carousel.closest(sectionSelector);
		const prevButton = prevSelector ? section?.querySelector(prevSelector) : null;
		const nextButton = nextSelector ? section?.querySelector(nextSelector) : null;

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

		let autoplayTimer = null;
		const autoplayDelay = 4000;

		const stopAutoplay = () => {
			if (autoplayTimer) {
				window.clearInterval(autoplayTimer);
				autoplayTimer = null;
			}
		};

		const startAutoplay = () => {
			if (autoplayTimer) {
				return;
			}

			autoplayTimer = window.setInterval(() => {
				embla.scrollNext();
			}, autoplayDelay);
		};

		startAutoplay();

		viewport.addEventListener('mouseenter', stopAutoplay);
		viewport.addEventListener('mouseleave', startAutoplay);
		prevButton?.addEventListener('mouseenter', stopAutoplay);
		prevButton?.addEventListener('mouseleave', startAutoplay);
		nextButton?.addEventListener('mouseenter', stopAutoplay);
		nextButton?.addEventListener('mouseleave', startAutoplay);
		embla.on('pointerDown', stopAutoplay);
		embla.on('pointerUp', startAutoplay);
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
		sectionSelector: '[data-featured-section]',
		prevSelector: '[data-featured-prev]',
		nextSelector: '[data-featured-next]',
	});

	setupCarousel({
		carouselSelector: '[data-home-carousel]',
		viewportSelector: '[data-home-viewport]',
		sectionSelector: '[data-home-carousel-section]',
		prevSelector: null,
		nextSelector: null,
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

		let autoplayTimer = null;
		const autoplayDelay = 4000;

		const stopAutoplay = () => {
			if (autoplayTimer) {
				window.clearInterval(autoplayTimer);
				autoplayTimer = null;
			}
		};

		const startAutoplay = () => {
			if (autoplayTimer) {
				return;
			}

			autoplayTimer = window.setInterval(() => {
				embla.scrollNext();
			}, autoplayDelay);
		};

		startAutoplay();

		viewport.addEventListener('mouseenter', stopAutoplay);
		viewport.addEventListener('mouseleave', startAutoplay);
		embla.on('pointerDown', stopAutoplay);
		embla.on('pointerUp', startAutoplay);
		embla.on('destroy', stopAutoplay);
	});
};

const setupMobileCategoryPanels = () => {
	document.querySelectorAll('[data-mobile-categories-toggle]').forEach((toggle) => {
		if (toggle.dataset.ready === 'true') {
			return;
		}

		const panelId = toggle.getAttribute('aria-controls');
		const panel = panelId ? document.getElementById(panelId) : null;
		const icon = toggle.querySelector('[data-mobile-categories-icon]');

		if (! panel) {
			return;
		}

		const setExpanded = (expanded) => {
			toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
			panel.classList.toggle('hidden', ! expanded);

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

		const closeDrawer = () => {
			toggle.setAttribute('aria-expanded', 'false');
			layer.classList.add('pointer-events-none', 'opacity-0');
			drawer.classList.add('translate-x-full');
			document.body.classList.remove('overflow-hidden');
		};

		const openDrawer = () => {
			toggle.setAttribute('aria-expanded', 'true');
			layer.classList.remove('pointer-events-none', 'opacity-0');
			drawer.classList.remove('translate-x-full');
			document.body.classList.add('overflow-hidden');
		};

		toggle.dataset.ready = 'true';
		closeDrawer();

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

		const showPanel = () => panel.classList.remove('hidden');
		const hidePanel = () => panel.classList.add('hidden');

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

			results.forEach((store) => {
				const li = document.createElement('li');
				const a = document.createElement('a');

				a.href = store.url;
				a.className = 'cb-search-result-item';

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
	setupCategoryComboboxes();
	setupImagePreviews();
	setupLocationPickers();
	setupFeaturedCarousels();
	setupRelatedCarousels();
	setupMobileCategoryPanels();
	setupMobileNavDrawer();
	setupSearchAutocomplete();
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', setupPublicInteractions);
} else {
	setupPublicInteractions();
}
