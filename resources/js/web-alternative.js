import collapse from '@alpinejs/collapse';
import Alpine from 'alpinejs';
import { siteMenu } from './components/web/layout/site-menu.js';
import { scrollHeader } from './web/home/alternative/header.js';
import { reviewCarousel } from './web/home/alternative/reviews.js';
// Temporary local comparison tool: remove its import, registration and assets before publication.
import { designPreview } from './web/home/alternative/design-preview.js';

Alpine.plugin(collapse);
Alpine.data('siteMenu', siteMenu);
Alpine.data('scrollHeader', scrollHeader);
Alpine.data('reviewCarousel', reviewCarousel);
Alpine.data('designPreview', designPreview);

Alpine.start();
