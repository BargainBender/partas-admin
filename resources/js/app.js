import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import ToastPlugin from 'vue-toast-notification';

import { OhVueIcon, addIcons } from "oh-vue-icons";
import { 
    FaChevronRight,
    BiCalendarWeekFill,
    RiDashboard3Fill,
    FaBus,
    RiPinDistanceFill,
    FaMapMarked,
    BiGearWideConnected,
    IoExit
} from "oh-vue-icons/icons";

addIcons(
    FaChevronRight,
    RiDashboard3Fill,
    FaBus,
    RiPinDistanceFill,
    BiCalendarWeekFill,
    FaMapMarked,
    BiGearWideConnected,
    IoExit
)

import 'vue-toast-notification/dist/theme-bootstrap.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ToastPlugin)
      .component("v-icon", OhVueIcon)
      .use(ZiggyVue, Ziggy)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
