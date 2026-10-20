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
        const ring = element.closest('[data-stat]')?.querySelector('[data-count-ring]');
        const setRing = (progress) => ring?.style.setProperty('stroke-dashoffset', String(1 - progress));

        if (reduceMotion) {
            element.textContent = formatNumber(target);
            setRing(1);
            return;
        }

        const started = performance.now();
        const duration = 1600;

        const step = (now) => {
            const linear = Math.min((now - started) / duration, 1);
            const progress = 1 - Math.pow(1 - linear, 3);
            element.textContent = formatNumber(Math.floor(target * progress));
            setRing(progress);

            if (linear < 1) {
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
        counter.closest('[data-stat]')?.querySelector('[data-count-ring]')?.style.setProperty('stroke-dashoffset', '1');
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

            impact.classList.remove('animate-fade-in');
            void impact.offsetWidth;
            impact.classList.add('animate-fade-in');

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

/**
 * Scroll reveal: fade elements up as they enter the viewport. Children of [data-reveal-stagger] get increasing delays.
 */
function initReveal() {
    const items = [...document.querySelectorAll('[data-reveal], [data-reveal-stagger] > *')];

    if (!items.length) {
        return;
    }

    document.querySelectorAll('[data-reveal-stagger]').forEach((group) => {
        [...group.children].forEach((child, index) => child.style.setProperty('--reveal-delay', `${Math.min(index, 8) * 90}ms`));
    });

    const finish = (element) => {
        element.classList.add('reveal-done');
        element.style.removeProperty('--reveal-delay');
    };

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
        items.forEach(finish);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const element = entry.target;
                observer.unobserve(element);
                element.classList.add('is-visible');

                const onEnd = (event) => {
                    if (event.target === element && event.propertyName === 'opacity') {
                        element.removeEventListener('transitionend', onEnd);
                        finish(element);
                    }
                };

                element.addEventListener('transitionend', onEnd);
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.12 },
    );

    items.forEach((item) => observer.observe(item));
}

/**
 * Deepen the sticky header's shadow and show the back-to-top button once the page is scrolled.
 */
function initScrollState() {
    const header = document.querySelector('.site-header');
    const backToTop = document.querySelector('[data-back-to-top]');

    const update = () => {
        const scrolled = window.scrollY > 12;
        header?.toggleAttribute('data-scrolled', scrolled);

        if (backToTop) {
            const show = window.scrollY > 600;
            backToTop.classList.toggle('opacity-0', !show);
            backToTop.classList.toggle('translate-y-4', !show);
            backToTop.classList.toggle('pointer-events-none', !show);
            backToTop.tabIndex = show ? 0 : -1;
        }
    };

    backToTop?.addEventListener('click', () => window.scrollTo({ top: 0 }));
    window.addEventListener('scroll', update, { passive: true });
    update();
}

/**
 * Watch elements and add a class once they scroll into view (used by heading bars and drawn SVG lines).
 */
function addClassWhenVisible(selector, className) {
    const elements = document.querySelectorAll(selector);

    if (!elements.length) {
        return;
    }

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add(className));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    observer.unobserve(entry.target);
                    entry.target.classList.add(className);
                }
            });
        },
        { threshold: 0.4 },
    );

    elements.forEach((element) => observer.observe(element));
}

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Parallax: [data-parallax="0.2"] elements drift at a fraction of the scroll speed.
 * Uses the `translate` property so it combines with the CSS float animation on `transform`.
 */
function initParallax() {
    const layers = [...document.querySelectorAll('[data-parallax]')];

    if (!layers.length || prefersReducedMotion()) {
        return;
    }

    let ticking = false;

    const update = () => {
        const scrollY = window.scrollY;
        layers.forEach((layer) => {
            layer.style.translate = `0 ${(scrollY * Number(layer.dataset.parallax)).toFixed(1)}px`;
        });
        ticking = false;
    };

    window.addEventListener(
        'scroll',
        () => {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        },
        { passive: true },
    );
    update();
}

/**
 * Reading progress bar under the header.
 */
function initScrollProgress() {
    const bar = document.querySelector('[data-scroll-progress]');

    if (!bar) {
        return;
    }

    let ticking = false;

    const update = () => {
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.transform = `scaleX(${scrollable > 0 ? Math.min(window.scrollY / scrollable, 1) : 0})`;
        ticking = false;
    };

    window.addEventListener(
        'scroll',
        () => {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        },
        { passive: true },
    );
    window.addEventListener('resize', update);
    update();
}

/**
 * Typewriter: cycles through the phrases in [data-typewriter]'s JSON data-words attribute.
 */
function initTypewriter() {
    document.querySelectorAll('[data-typewriter]').forEach((element) => {
        const words = JSON.parse(element.dataset.words || '[]');
        const target = element.querySelector('[data-typewriter-text]');

        if (!words.length || !target) {
            return;
        }

        if (prefersReducedMotion()) {
            let index = 0;
            setInterval(() => {
                index = (index + 1) % words.length;
                target.textContent = words[index];
            }, 3000);
            return;
        }

        let wordIndex = 0;
        let length = words[0].length;
        let deleting = true;
        const segmenter = 'Segmenter' in Intl ? new Intl.Segmenter(locale, { granularity: 'grapheme' }) : null;
        const characters = (word) => (segmenter ? [...segmenter.segment(word)].map((part) => part.segment) : [...word]);

        const tick = () => {
            const word = characters(words[wordIndex]);

            if (deleting) {
                length--;
                target.textContent = word.slice(0, length).join('');

                if (length <= 0) {
                    deleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    setTimeout(tick, 300);
                    return;
                }

                setTimeout(tick, 35);
                return;
            }

            length++;
            target.textContent = word.slice(0, length).join('');

            if (length >= word.length) {
                deleting = true;
                setTimeout(tick, 2200);
                return;
            }

            setTimeout(tick, 70);
        };

        length = characters(words[0]).length;
        setTimeout(tick, 2600);
    });
}

/**
 * Ripple effect on button clicks.
 */
function initRipples() {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('.btn-donate, .btn-outline');

        if (!button || prefersReducedMotion()) {
            return;
        }

        const rect = button.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.left = `${event.clientX - rect.left}px`;
        ripple.style.top = `${event.clientY - rect.top}px`;
        button.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
    });
}

/**
 * Floating hearts on donate links; navigation waits briefly so the hearts can be seen.
 */
function initFloatingHearts() {
    const heartSvg =
        '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="100%" height="100%"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>';

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a.btn-donate, [data-donate-link]');

        if (!link || prefersReducedMotion() || event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) {
            return;
        }

        const x = event.clientX || link.getBoundingClientRect().left + link.offsetWidth / 2;
        const y = event.clientY || link.getBoundingClientRect().top;

        for (let index = 0; index < 6; index++) {
            const heart = document.createElement('span');
            const size = 12 + Math.random() * 12;
            heart.className = 'floating-heart';
            heart.innerHTML = heartSvg;
            heart.style.cssText = `left:${x}px;top:${y}px;width:${size}px;height:${size}px;animation-delay:${index * 60}ms;--drift:${(Math.random() - 0.5) * 90}px;--spin:${(Math.random() - 0.5) * 60}deg`;
            document.body.appendChild(heart);
            heart.addEventListener('animationend', () => heart.remove());
        }

        if (link.href && link.target !== '_blank') {
            event.preventDefault();
            setTimeout(() => {
                window.location.href = link.href;
            }, 450);
        }
    });
}

initDrawer();
initCounters();
initQuickGive();
initReveal();
initScrollState();
initParallax();
initScrollProgress();
initTypewriter();
initRipples();
initFloatingHearts();
addClassWhenVisible('.heading-bar[data-draw]', 'is-drawn');
addClassWhenVisible('.draw-line', 'is-drawn');
