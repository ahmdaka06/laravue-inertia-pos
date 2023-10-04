import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { InertiaProgress } from '@inertiajs/progress'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
        //set mixins
      .mixin({
        methods: {

          //method "hasAnyPermission"
          hasAnyPermission: function (permissions) {

            //get permissions from props
            let allPermissions = this.$page.props.auth.permissions;

            let hasPermission = false;
            permissions.forEach(function(item){
              if(allPermissions[item]) hasPermission = true;
            });

            return hasPermission;
          },

          //format price
          formatPrice(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
          },

        },
      })
      .use(plugin)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' });
