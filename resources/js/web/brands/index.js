import collapse from '@alpinejs/collapse';
import Alpine from 'alpinejs';
import { scrollHeader } from '../../components/web/layout/scroll-header.js';
import { siteMenu } from '../../components/web/layout/site-menu.js';
import { brandDisclosure } from './partials/brand.js';

Alpine.plugin(collapse);
Alpine.data('siteMenu', siteMenu);
Alpine.data('scrollHeader', scrollHeader);
Alpine.data('brandDisclosure', brandDisclosure);
Alpine.start();
