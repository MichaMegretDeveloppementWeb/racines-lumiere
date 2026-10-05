import collapse from '@alpinejs/collapse';
import Alpine from 'alpinejs';
import { scrollHeader } from './components/web/layout/scroll-header.js';
import { siteMenu } from './components/web/layout/site-menu.js';
import { reviewCarousel } from './web/home/review-carousel.js';

Alpine.plugin(collapse);
Alpine.data('siteMenu', siteMenu);
Alpine.data('scrollHeader', scrollHeader);
Alpine.data('reviewCarousel', reviewCarousel);

Alpine.start();
