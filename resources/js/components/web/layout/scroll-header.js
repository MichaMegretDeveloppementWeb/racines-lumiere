const COMPACT_SCROLL_THRESHOLD = 32;

/**
 * @typedef {object} ScrollHeaderData
 * @property {boolean} isScrolled
 * @property {number | null} frameId
 * @property {(() => void) | null} stopListening
 * @property {() => void} init
 * @property {() => void} destroy
 */

/**
 * Blend into the hero at the top, then give the navigation its own compact surface.
 *
 * @returns {ScrollHeaderData & ThisType<ScrollHeaderData>}
 */
export function scrollHeader() {
    return {
        isScrolled: false,
        frameId: null,
        stopListening: null,

        init() {
            this.destroy();
            this.isScrolled = window.scrollY > COMPACT_SCROLL_THRESHOLD;
            const onScroll = () => {
                if (this.frameId !== null) {
                    return;
                }

                this.frameId = window.requestAnimationFrame(() => {
                    this.isScrolled = window.scrollY > COMPACT_SCROLL_THRESHOLD;
                    this.frameId = null;
                });
            };

            window.addEventListener('scroll', onScroll, { passive: true });
            this.stopListening = () => window.removeEventListener('scroll', onScroll);
        },

        destroy() {
            this.stopListening?.();
            this.stopListening = null;
            if (this.frameId !== null) {
                window.cancelAnimationFrame(this.frameId);
                this.frameId = null;
            }
        },
    };
}
