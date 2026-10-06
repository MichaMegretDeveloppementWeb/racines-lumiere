/**
 * @typedef {object} BrandDialogData
 * @property {boolean} isOpen
 * @property {() => void} open
 * @property {() => void} close
 * @property {() => void} restoreFocus
 * @property {(event: MouseEvent) => void} closeOnBackdrop
 * @property {(event: KeyboardEvent) => void} keepFocusInside
 * @property {() => void} destroy
 */

/**
 * Native modal dialogs keep the gallery still and provide keyboard focus containment.
 *
 * @returns {BrandDialogData & ThisType<BrandDialogData & { $refs: { toggle: HTMLButtonElement, dialog: HTMLDialogElement, body: HTMLElement } }>}
 */
export function brandDialog() {
    return {
        isOpen: false,

        open() {
            this.$refs.dialog.showModal();
            this.$refs.body.scrollTop = 0;
            this.isOpen = true;
        },

        close() {
            this.$refs.dialog.close();
        },

        restoreFocus() {
            this.isOpen = false;
            this.$refs.toggle.focus({ preventScroll: true });
        },

        closeOnBackdrop(event) {
            if (event.target !== this.$refs.dialog) {
                return;
            }

            const bounds = this.$refs.dialog.getBoundingClientRect();
            const isOutside = event.clientX < bounds.left || event.clientX > bounds.right
                || event.clientY < bounds.top || event.clientY > bounds.bottom;

            if (isOutside) {
                this.close();
            }
        },

        keepFocusInside(event) {
            const reachable = this.$refs.dialog.querySelectorAll('button:not([disabled]), a[href]');
            const first = reachable[0];
            const last = reachable[reachable.length - 1];
            const destination = event.shiftKey && document.activeElement === first ? last
                : !event.shiftKey && document.activeElement === last ? first : null;

            if (destination instanceof HTMLElement) {
                event.preventDefault();
                destination.focus();
            }
        },

        destroy() {
            this.$refs.dialog.close();
        },
    };
}
