import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

export function createAtomicApp (options) {
  let atomic = window.Atomic;

  // Tailwindcss Setup.
  // options.tailwind



  // Inertia.js Setup.
  let inertia = options.inertia(atomic);

  if (! inertia?.resolveUsing) {
    throw new Error('The Atomic app requires an Inertia.js resolver.');
  }

  atomic.resolve = inertia.resolveUsing;

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
      const app = createApp(() => {
        return h(App, props);
      });

      app.use(plugin);
      app.mount(el);
    },
    
  });
};
