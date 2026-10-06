/**
 * @typedef {object} BrandDisclosureData
 * @property {boolean} isOpen
 * @property {() => void} toggle
 * @property {(returnFocus?: boolean) => void} close
 */

/**
 * Brand presentations stay in the HTML; Alpine only folds their details.
 *
 * @returns {BrandDisclosureData & ThisType<BrandDisclosureData & { $refs: { toggle: HTMLButtonElement } }>}
 */
export function brandDisclosure() {
    return {
        isOpen: false,

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.isOpen = true;
            }
        },

        close(returnFocus = true) {
            if (!this.isOpen) {
                return;
            }

            this.isOpen = false;

            // An outside click keeps focus on its own destination.
            if (returnFocus) {
                this.$refs.toggle.focus({ preventScroll: true });
            }
        },
    };
}
