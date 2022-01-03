import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

export { useTemplate } from './template-manager.js';

export function createAtomicApp (options) {
  let atomic = window.Atomic;

  // Inertia.js Setup.
  let inertia = options.inertia(atomic);
  
  if (inertia?.progress) {
    InertiaProgress.init(inertia.progress);
  }

  // Atomic Setup.
  const components = options.components(atomic);

  /**
   * Create the Inertia.js application.
   */
  createInertiaApp({

    /**
     * Resolve the Inertia.js templates.
     */
    resolve ({ component, template }) {
      const resolver = components.find(c => c.name === component);

      if (! resolver) {
        throw new Error(`The Atomic app could not resolve unregistered component ${component}.`);
      }

      if (typeof resolver === 'object' && typeof resolver.resolve === 'function') {
        try {
          return resolver.resolve(template);
        } catch (e) {
          if (e.code !== 'MODULE_NOT_FOUND') {
            throw e;
          }
        }

        try {
          return resolver.fallbackResolver(template);
        } catch (e) {
          if (e.code !== 'MODULE_NOT_FOUND') {
            throw e;
          }
        }
      }

      throw new Error(`The Atomic app could not resolve template ${component}/${template}.`);
    },

    /**
     * Create the Vue application.
     */
    setup ({ el, App, props, plugin }) {
      const app = createApp({
        setup () {
          components.forEach(component => typeof component?.setup === 'function' && component.setup());

          return () => h(App, props);
        },
      });

      app.use(plugin);

      if (options?.store !== undefined) {
        app.use(options.store);
      }

      if (typeof options?.plugins === 'function') {
        options.plugins(app);
      }

      app.mount(el);
    },
    
  });
};
