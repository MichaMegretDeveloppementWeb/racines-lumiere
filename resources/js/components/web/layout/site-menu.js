const FOCUSABLE = 'a[href], button:not([disabled])';

/**
 * @typedef {object} SiteMenuData
 * @property {boolean} isOpen
 * @property {() => void} toggle
 * @property {() => void} open
 * @property {() => void} close
 * @property {(event: KeyboardEvent) => void} keepFocusInside
 * @property {() => void} destroy
 */

/**
 * @typedef {object} AlpineMagics
 * @property {{ toggle: HTMLButtonElement, panel: HTMLElement }} $refs
 * @property {(callback: () => void) => void} $nextTick
 */

/**
 * The elements of a container the keyboard can reach, in document order.
 *
 * @param {HTMLElement} container
 * @returns {HTMLElement[]}
 */
function focusableWithin(container) {
    return Array.from(container.querySelectorAll(FOCUSABLE));
}

/**
 * The page scroll is frozen while the menu covers it.
 *
 * @param {boolean} isFrozen
 */
function freezePageScroll(isFrozen) {
    document.documentElement.style.overflow = isFrozen ? 'hidden' : '';
}

/**
 * The main menu of small screens: it opens over the page, keeps the keyboard inside, and gives the focus back.
 *
 * @returns {SiteMenuData & ThisType<SiteMenuData & AlpineMagics>}
 */
export function siteMenu() {
    return {
        isOpen: false,

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        open() {
            this.isOpen = true;
            freezePageScroll(true);
            this.$nextTick(() => focusableWithin(this.$refs.panel)[0]?.focus());
        },

        close() {
            if (!this.isOpen) {
                return;
            }

            this.isOpen = false;
            freezePageScroll(false);
            this.$refs.toggle.focus();
        },

        keepFocusInside(event) {
            if (!this.isOpen) {
                return;
            }

            const reachable = [this.$refs.toggle, ...focusableWithin(this.$refs.panel)];
            const first = reachable[0];
            const last = reachable[reachable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },

        destroy() {
            freezePageScroll(false);
        },
    };
}
