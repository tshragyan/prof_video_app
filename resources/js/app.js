import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { initShopifyAppBridge } from './shopify'

createInertiaApp({
    resolve: name => import(`./Pages/${name}.vue`),
    setup({ el, App, props }) {

        const appBridge = initShopifyAppBridge(props.initialPage.props);

        const vueApp = createApp({
            render: () => h(App, props),
        });

        vueApp.config.globalProperties.$appBridge = appBridge;
        vueApp.provide('appBridge', appBridge);

        vueApp.config.compilerOptions.isCustomElement = tag =>
            tag.startsWith('polaris-');

        vueApp.mount(el);
    },
});
