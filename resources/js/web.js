import collapse from '@alpinejs/collapse';
import Alpine from 'alpinejs';
import { siteMenu } from './components/web/layout/site-menu.js';

Alpine.plugin(collapse);
Alpine.data('siteMenu', siteMenu);

Alpine.start();
