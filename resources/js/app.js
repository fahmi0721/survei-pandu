import './bootstrap';
import '@tabler/core/dist/js/tabler.min.js';
import './modules/coba';
import { initAuth } from './modules/auth'; $(function () { initAuth();});
import { initAdmin } from './modules/admin'; $(function () { initAdmin();});


