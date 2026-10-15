const locale = document.documentElement.lang === 'bn' ? 'bn-BD' : 'en-US';
const formatNumber = (value) => new Intl.NumberFormat(locale).format(value);

/**
 * Mobile navigation drawer: open/close buttons, overlay click and Escape key.
 */
function initDrawer() {
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');

    if (!drawer || !overlay) {
        return;
    }

    let lastTrigger = null;

    const setOpen = (open) => {
        drawer.classList.toggle('translate-x-full', !open);
        drawer.setAttribute('aria-hidden', String(!open));
        drawer.inert = !open;
        overlay.classList.toggle('hidden', !open);
        document.body.classList.toggle('overflow-hidden', open);
        document.querySelectorAll('[data-drawer-open]').forEach((button) => button.setAttribute('aria-expanded', String(open)));

        if (open) {
            drawer.querySelector('[data-drawer-close]')?.focus();
        } else {
            lastTrigger?.focus();
        }
    };

    drawer.inert = true;

    document.querySelectorAll('[data-drawer-open]').forEach((button) =>
        button.addEventListener('click', () => {
            lastTrigger = button;
            setOpen(true);
        }),
    );
    document.querySelectorAll('[data-drawer-close]').forEach((button) => button.addEventListener('click', () => setOpen(false)));
    overlay.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !drawer.inert) {
            setOpen(false);
        }
    });
}

/**
 * Animated counters for the impact stats band.
 */
function initCounters() {
    const counters = document.querySelectorAll('[data-count]');

    if (!counters.length) {
        return;
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const animate = (element) => {
        const target = Number(element.dataset.count);

        if (reduceMotion) {
            element.textContent = formatNumber(target);
            return;
        }

        const started = performance.now();
        const duration = 1200;

        const step = (now) => {
            const progress = Math.min((now - started) / duration, 1);
            element.textContent = formatNumber(Math.floor(target * progress));

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };

        requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                observer.unobserve(entry.target);
                animate(entry.target);
            }
        });
    });

    counters.forEach((counter) => {
        counter.textContent = formatNumber(0);
        observer.observe(counter);
    });
}

/**
 * Quick Give box: frequency tabs, preset amounts, custom amount and impact text.
 */
function initQuickGive() {
    document.querySelectorAll('[data-quick-give]').forEach((root) => {
        const impact = root.querySelector('[data-impact-text]');
        const customInput = root.querySelector('[data-custom-amount]');
        const donateLink = root.querySelector('[data-donate-link]');
        const minimum = Number(root.dataset.minimum || 0);
        const messages = JSON.parse(root.dataset.messages);

        let frequency = 'monthly';
        let amount = 0;

        const tiersFor = (mode) =>
            [...root.querySelectorAll(`[data-amount-button][data-frequency="${mode}"]`)].map((button) => ({
                amount: Number(button.dataset.amount),
                impact: button.dataset.impact,
            }));

        const updateImpact = () => {
            const tiers = tiersFor(frequency);

            if (!amount) {
                impact.textContent = messages.empty;
            } else if (amount < minimum) {
                impact.textContent = messages.minimum.replace(':amount', formatNumber(minimum));
            } else {
                const tier = tiers.filter((candidate) => amount >= candidate.amount).pop() ?? tiers[0];
                impact.textContent = messages[frequency].replace(':amount', formatNumber(amount)).replace(':impact', tier.impact);
            }

            const url = new URL(donateLink.dataset.baseUrl, window.location.origin);
            url.searchParams.set('frequency', frequency);

            if (amount >= minimum) {
                url.searchParams.set('amount', String(amount));
            }

            donateLink.href = url.toString();
        };

        const selectButton = (selected) => {
            root.querySelectorAll('[data-amount-button], [data-custom-button]').forEach((button) =>
                button.setAttribute('aria-pressed', String(button === selected)),
            );
        };

        const setFrequency = (mode) => {
            frequency = mode;

            root.querySelectorAll('[data-frequency-tab]').forEach((tab) => tab.setAttribute('aria-selected', String(tab.dataset.frequencyTab === mode)));
            root.querySelectorAll('[data-amount-button]').forEach((button) => button.classList.toggle('hidden', button.dataset.frequency !== mode));

            const buttons = root.querySelectorAll(`[data-amount-button][data-frequency="${mode}"]`);
            const preset = buttons[mode === 'monthly' ? 1 : 0] ?? buttons[0];

            customInput.classList.add('hidden');
            selectButton(preset);
            amount = Number(preset.dataset.amount);
            updateImpact();
        };

        root.querySelectorAll('[data-frequency-tab]').forEach((tab) => tab.addEventListener('click', () => setFrequency(tab.dataset.frequencyTab)));

        root.querySelectorAll('[data-amount-button]').forEach((button) =>
            button.addEventListener('click', () => {
                customInput.classList.add('hidden');
                selectButton(button);
                amount = Number(button.dataset.amount);
                updateImpact();
            }),
        );

        root.querySelector('[data-custom-button]').addEventListener('click', (event) => {
            selectButton(event.currentTarget);
            customInput.classList.remove('hidden');
            customInput.focus();
            amount = Number(customInput.value) || 0;
            updateImpact();
        });

        customInput.addEventListener('input', () => {
            amount = Math.max(0, Math.floor(Number(customInput.value) || 0));
            updateImpact();
        });

        setFrequency('monthly');
    });
}

initDrawer();
initCounters();
initQuickGive();
