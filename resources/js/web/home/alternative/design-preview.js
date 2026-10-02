/**
 * @typedef {{ id: string, src: string, srcsets: Record<string, string> }} PreviewImage
 * @typedef {{ id: string, family: string }} PreviewFont
 * @typedef {{ id: string, filter: string }} PreviewTone
 * @typedef {{ id: string, height: string }} PreviewHeight
 * @typedef {{ imageId: string, fontId: string, toneId: string, heightId: string }} PreviewSelection
 * @typedef {{ images: PreviewImage[], fonts: PreviewFont[], tones: PreviewTone[], heights: PreviewHeight[], defaults: PreviewSelection }} PreviewConfig
 * @typedef {object} DesignPreviewData
 * @property {boolean} isOpen
 * @property {boolean} isLoading
 * @property {boolean} hasImageError
 * @property {string} imageId
 * @property {string} fontId
 * @property {string} toneId
 * @property {string} heightId
 * @property {string} appliedImageId
 * @property {number} imageRequest
 * @property {HTMLImageElement | null} heroImage
 * @property {HTMLSourceElement[]} heroSources
 * @property {Record<string, string> | null} originalStyle
 * @property {(() => void) | null} restoreImage
 * @property {() => void} init
 * @property {() => void} destroy
 * @property {() => void} toggle
 * @property {() => void} close
 * @property {() => Promise<void>} selectImage
 * @property {(image: PreviewImage) => void} applyImage
 * @property {() => void} selectFont
 * @property {() => void} selectTone
 * @property {() => void} selectHeight
 * @property {() => Promise<void>} reset
 */

/**
 * @typedef {{ $refs: { toggle: HTMLElement } }} PreviewMagics
 */

/**
 * Temporary design comparison. Remove this module and its registration before publication.
 *
 * @param {PreviewConfig} config
 * @returns {DesignPreviewData & ThisType<DesignPreviewData & PreviewMagics>}
 */
export function designPreview(config) {
    return {
        ...config.defaults,
        isOpen: false,
        isLoading: false,
        hasImageError: false,
        appliedImageId: config.defaults.imageId,
        imageRequest: 0,
        heroImage: null,
        heroSources: [],
        originalStyle: null,
        restoreImage: null,

        init() {
            this.destroy();
            Object.assign(this, config.defaults);
            this.appliedImageId = config.defaults.imageId;
            this.isLoading = false;
            this.hasImageError = false;
            this.heroImage = document.querySelector('.alt-hero-image img');
            this.heroSources = [...(document.querySelector('.alt-hero-image picture')?.querySelectorAll('source') ?? [])];
            this.originalStyle = Object.fromEntries(
                ['--font-sans', '--alt-hero-image-filter', '--alt-hero-min-height']
                    .map((name) => [name, document.body.style.getPropertyValue(name)]),
            );
            const image = this.heroImage;
            if (!image) {
                return;
            }

            const src = image.src;
            const srcset = image.srcset;
            const sources = this.heroSources.map((source) => ({ source, srcset: source.srcset }));
            this.restoreImage = () => {
                sources.forEach((saved) => { saved.source.srcset = saved.srcset; });
                image.srcset = srcset;
                image.src = src;
            };
        },

        toggle() {
            if (this.isOpen) {
                this.close();
                return;
            }

            this.isOpen = true;
        },

        close() {
            if (!this.isOpen) {
                return;
            }

            this.isOpen = false;
            this.$refs.toggle.focus({ preventScroll: true });
        },

        async selectImage() {
            const image = config.images.find((option) => option.id === this.imageId);
            if (!image || !this.heroImage) {
                return;
            }

            const request = ++this.imageRequest;
            this.isLoading = true;
            this.hasImageError = false;
            this.applyImage(image);
            try {
                await this.heroImage.decode();
                if (request === this.imageRequest) {
                    this.appliedImageId = image.id;
                }
            } catch {
                if (request === this.imageRequest) {
                    const previous = config.images.find((option) => option.id === this.appliedImageId);
                    if (previous) {
                        this.applyImage(previous);
                        this.imageId = previous.id;
                    }
                    this.hasImageError = true;
                }
            } finally {
                if (request === this.imageRequest) {
                    this.isLoading = false;
                }
            }
        },

        applyImage(image) {
            this.heroSources.forEach((source) => {
                const format = source.type === 'image/avif' ? 'avif' : 'webp';
                source.srcset = image.srcsets[format];
            });
            if (this.heroImage) {
                this.heroImage.srcset = image.srcsets.jpg;
                this.heroImage.src = image.src;
            }
        },

        selectFont() {
            const font = config.fonts.find((option) => option.id === this.fontId);
            if (font) {
                document.body.style.setProperty('--font-sans', font.family);
            }
        },

        selectTone() {
            const tone = config.tones.find((option) => option.id === this.toneId);
            if (tone) {
                document.body.style.setProperty('--alt-hero-image-filter', tone.filter);
            }
        },

        selectHeight() {
            const height = config.heights.find((option) => option.id === this.heightId);
            if (height) {
                document.body.style.setProperty('--alt-hero-min-height', height.height);
            }
        },

        async reset() {
            Object.assign(this, config.defaults);
            this.selectFont();
            this.selectTone();
            this.selectHeight();
            await this.selectImage();
        },

        destroy() {
            this.imageRequest++;
            this.restoreImage?.();
            this.restoreImage = null;
            if (this.originalStyle) {
                Object.entries(this.originalStyle).forEach(([name, value]) => {
                    if (value) {
                        document.body.style.setProperty(name, value);
                    } else {
                        document.body.style.removeProperty(name);
                    }
                });
            }
            this.originalStyle = null;
        },
    };
}
