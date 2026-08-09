import "../css/template.css";
// import "../scss/main.scss";
import "../css/app.css";
import "./bootstrap";

import { createInertiaApp, Link } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import VueGoogleMaps from '@fawmi/vue-google-maps'

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .component("inertia-link", Link)
            .use(plugin)
            .use(VueGoogleMaps, {
                load: {
                    key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
                }
            })
            .mixin({
                methods: {
                    authUserHasRole(verifiableRole) {
                        return this.$page.props.user.roles.some(
                            (role) => role.name === verifiableRole
                        );
                    },
                    imagePath(path) {
                        return window.settings.images + "/" + path;
                    },
                    imageFromAwsPublic(path) {
                        return `${import.meta.env.VITE_AWS_PUBLIC_URL}/${path}`;
                    },
                },
            })
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
