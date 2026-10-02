const SWIPE_THRESHOLD = 0.2;
const MAX_SWIPE_DISTANCE = 80;
const SCROLL_SETTLE_DELAY = 150;
const ANIMATION_DURATION = 320;

/**
 * @typedef {object} DragOrigin
 * @property {number} pointerId
 * @property {number} clientX
 * @property {number} scrollLeft
 * @property {number} index
 */

/**
 * @typedef {object} ReviewCarouselData
 * @property {string[]} authors
 * @property {number} count
 * @property {number} loopStart
 * @property {number} activeIndex
 * @property {number} targetSlot
 * @property {number[]} positions
 * @property {number} cardWidth
 * @property {boolean} isDragging
 * @property {boolean} isAnimating
 * @property {number | null} animationFrame
 * @property {number} initializationId
 * @property {DragOrigin | null} dragOrigin
 * @property {number | null} settleTimer
 * @property {(() => void) | null} stopObserving
 * @property {string} activeAuthor
 * @property {() => void} init
 * @property {() => void} measure
 * @property {(index: number) => void} goTo
 * @property {(position: number) => void} animateTo
 * @property {() => void} stopAnimation
 * @property {() => void} previous
 * @property {() => void} next
 * @property {() => void} onScroll
 * @property {() => void} settle
 * @property {(event: PointerEvent) => void} beginDrag
 * @property {(event: PointerEvent) => void} moveDrag
 * @property {(event: PointerEvent) => void} endDrag
 * @property {() => void} destroy
 */

/**
 * @typedef {object} CarouselMagics
 * @property {{ track: HTMLUListElement }} $refs
 * @property {(callback: () => void) => void} $nextTick
 */

/**
 * @param {number} index
 * @param {number} count
 * @returns {number}
 */
function wrappedIndex(index, count) {
    return ((index % count) + count) % count;
}

/**
 * @param {number[]} positions
 * @param {number} offset
 * @returns {number}
 */
function nearestSlot(positions, offset) {
    const distances = positions.map((position) => Math.abs(position - offset));

    return distances.indexOf(Math.min(...distances));
}

/**
 * @param {number} progress
 * @returns {number}
 */
function easedProgress(progress) {
    return 1 - (1 - progress) ** 3;
}

/**
 * Seamless looping copies stay inert; original testimonials remain accessible once.
 *
 * @param {{ authors: string[] }} configuration
 * @returns {ReviewCarouselData & ThisType<ReviewCarouselData & CarouselMagics>}
 */
export function reviewCarousel({ authors }) {
    return {
        authors,
        activeIndex: 0,
        targetSlot: 0,
        positions: [0],
        cardWidth: 0,
        isDragging: false,
        isAnimating: false,
        animationFrame: null,
        initializationId: 0,
        dragOrigin: null,
        settleTimer: null,
        stopObserving: null,

        get count() {
            return this.authors.length;
        },

        get loopStart() {
            return this.count > 1 ? this.count : 0;
        },

        get activeAuthor() {
            return this.authors[this.activeIndex] ?? '';
        },

        init() {
            this.destroy();
            const initializationId = this.initializationId;
            this.$nextTick(() => {
                if (this.initializationId !== initializationId) {
                    return;
                }

                this.measure();
                const observer = new ResizeObserver(() => this.measure());
                observer.observe(this.$refs.track);
                this.stopObserving = () => observer.disconnect();
            });
        },

        measure() {
            this.stopAnimation();
            const track = this.$refs.track;
            const cards = Array.from(track.children).filter((card) => card instanceof HTMLElement);
            const first = cards[0]?.getBoundingClientRect();
            const offsets = cards.map((card) => card.getBoundingClientRect().left - (first?.left ?? 0));
            const width = first?.width ?? 0;

            this.positions = offsets.length > 0 ? offsets : [0];
            this.cardWidth = width;
            this.targetSlot = this.loopStart + this.activeIndex;
            track.scrollTo({ left: this.positions[this.targetSlot], behavior: 'instant' });
        },

        goTo(index) {
            if (this.count < 2) {
                return;
            }

            const track = this.$refs.track;
            let slot = this.targetSlot + index - this.activeIndex;
            if (slot < 0 || slot >= this.positions.length - 1) {
                track.scrollTo({ left: this.positions[this.loopStart + this.activeIndex], behavior: 'instant' });
                slot = this.loopStart + index;
            }

            this.targetSlot = slot;
            this.activeIndex = wrappedIndex(index, this.count);
            this.animateTo(this.positions[slot]);
        },

        animateTo(position) {
            this.stopAnimation();
            const track = this.$refs.track;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                track.scrollTo({ left: position, behavior: 'instant' });
                this.settle();

                return;
            }

            const origin = track.scrollLeft;
            /** @type {number | null} */
            let startedAt = null;
            this.isAnimating = true;
            /** @param {number} timestamp */
            const advance = (timestamp) => {
                startedAt ??= timestamp;
                const progress = Math.min((timestamp - startedAt) / ANIMATION_DURATION, 1);
                track.scrollTo({ left: origin + (position - origin) * easedProgress(progress), behavior: 'instant' });
                if (progress < 1) {
                    this.animationFrame = window.requestAnimationFrame(advance);
                } else {
                    this.animationFrame = null;
                    this.isAnimating = false;
                    this.settle();
                }
            };

            this.animationFrame = window.requestAnimationFrame(advance);
        },

        stopAnimation() {
            if (this.animationFrame !== null) {
                window.cancelAnimationFrame(this.animationFrame);
                this.animationFrame = null;
            }

            this.isAnimating = false;
        },

        previous() {
            this.goTo(this.activeIndex - 1);
        },

        next() {
            this.goTo(this.activeIndex + 1);
        },

        onScroll() {
            if (this.isDragging || this.isAnimating) {
                return;
            }

            if (this.settleTimer !== null) {
                window.clearTimeout(this.settleTimer);
            }

            this.settleTimer = window.setTimeout(() => this.settle(), SCROLL_SETTLE_DELAY);
        },

        settle() {
            if (this.isDragging || this.isAnimating) {
                return;
            }

            if (this.settleTimer !== null) {
                window.clearTimeout(this.settleTimer);
                this.settleTimer = null;
            }

            const track = this.$refs.track;
            const slot = nearestSlot(this.positions, track.scrollLeft);
            this.activeIndex = wrappedIndex(slot - this.loopStart, this.count);
            this.targetSlot = this.loopStart + this.activeIndex;
            const position = this.positions[this.targetSlot];
            if (Math.abs(track.scrollLeft - position) > 1) {
                track.scrollTo({ left: position, behavior: 'instant' });
            }
        },

        beginDrag(event) {
            if (!event.isPrimary || event.button !== 0 || this.dragOrigin !== null || this.count < 2) {
                return;
            }

            this.stopAnimation();
            this.settle();
            this.dragOrigin = {
                pointerId: event.pointerId,
                clientX: event.clientX,
                scrollLeft: this.positions[this.targetSlot],
                index: this.activeIndex,
            };
            this.isDragging = true;
            this.$refs.track.scrollTo({ left: this.dragOrigin.scrollLeft, behavior: 'instant' });
            this.$refs.track.setPointerCapture(event.pointerId);
        },

        moveDrag(event) {
            if (this.dragOrigin === null || event.pointerId !== this.dragOrigin.pointerId) {
                return;
            }

            this.$refs.track.scrollLeft = this.dragOrigin.scrollLeft - (event.clientX - this.dragOrigin.clientX);
        },

        endDrag(event) {
            const origin = this.dragOrigin;
            if (origin === null || event.pointerId !== origin.pointerId) {
                return;
            }

            const distance = event.type === 'pointercancel' ? 0 : origin.clientX - event.clientX;
            const threshold = Math.min(this.cardWidth * SWIPE_THRESHOLD, MAX_SWIPE_DISTANCE);
            const direction = Math.abs(distance) >= threshold ? Math.sign(distance) : 0;

            this.dragOrigin = null;
            this.isDragging = false;
            if (this.$refs.track.hasPointerCapture(event.pointerId)) {
                this.$refs.track.releasePointerCapture(event.pointerId);
            }

            this.goTo(origin.index + direction);
        },

        destroy() {
            this.initializationId++;
            this.stopAnimation();
            this.stopObserving?.();
            this.stopObserving = null;
            if (this.settleTimer !== null) {
                window.clearTimeout(this.settleTimer);
                this.settleTimer = null;
            }
        },
    };
}
