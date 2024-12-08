import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createSSRApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

const appName = import.meta.env.VITE_APP_NAME || "NCMS";

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob("./Pages/**/*.vue")
    ),
  setup({ el, App, props, plugin }) {
    const createdApp = createSSRApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);

    // delete el.dataset.page;
    el.dataset.info = "NCMS";

    return createdApp;
  },
  progress: {
    color: "#4B5563",
  },
});
