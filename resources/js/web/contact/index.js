import { Alpine, Livewire } from '@livewire';
import { scrollHeader } from '../../components/web/layout/scroll-header.js';
import { siteMenu } from '../../components/web/layout/site-menu.js';

Alpine.data('siteMenu', siteMenu);
Alpine.data('scrollHeader', scrollHeader);

Livewire.start();
