import Alpine from 'alpinejs';
import { scrollHeader } from '../../components/web/layout/scroll-header.js';
import { siteMenu } from '../../components/web/layout/site-menu.js';
import { brandDialog } from './brand-dialog.js';

Alpine.data('siteMenu', siteMenu);
Alpine.data('scrollHeader', scrollHeader);
Alpine.data('brandDialog', brandDialog);
Alpine.start();
