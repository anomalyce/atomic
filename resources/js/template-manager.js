import { h, ref, isRef, reactive, isReactive } from 'vue';

class TemplateManager
{
  /**
   * Instantiate a new template manager object.
   * 
   * @param  object  options
   * @param  string  slot
   * @return void
   * 
   * @throws \Error
   */
  constructor (options, slot) {
    this.states = {};
    this.options = options;
    this.key = this.options?.key || Math.random().toString(36).substr(0, 10);

    if (! this.options.slots) {
      throw new Error('The template manager requires the slots to be passed through the options.');
    }

    if (this.options.state && ! isRef(this.options.state)) {
      throw new Error('The template manager requires the state property to be a ref object.');
    }

    if (slot && typeof slot === 'string') {
      this._declarePrerenderer(slot);
    }

    this.state = this.options.state || ref(slot);
  }

  /**
   * Declare a template state.
   * 
   * @param  string  name
   * @param  \Closure|object  callback
   * @return void
   */
  declare (name, callback) {
    this.states[name] = typeof callback === 'function' ? callback() : callback;
  }

  /**
   * Get the template state.
   * 
   * @return string
   */
  getState () {
    return this.state.value;
  }

  /**
   * Get the template state object.
   * 
   * @return object
   */
  getStateObject () {
    return isRef(this.state) ? this.state : ref(this.state);
  }

  /**
   * Set the template state.
   * 
   * @param  string  state
   * @return void
   */
  setState (state) {
    this.state.value = state;
  }

  /**
   * Render the appropriate template.
   * 
   * @param  \Closure|object  parameters
   * @return \Closure
   */
  render (parameters) {
    this.declare('default', parameters);

    return () => {
      const slot = this.state.value || 'default';

      if (Object.keys(this.states).indexOf(slot) === -1) {
        throw new Error(`The template manager could not render the ${slot} slot.`);
      }

      return this._generate(this.states[slot]);
    };
  }

  /**
   * Generate the given slot and pass the parameters.
   * 
   * @param  string  slot
   * @param  object  parameters
   * @return \Closure
   */
  _generate (parameters) {
    const slot = this.state.value || 'default';
    const tag = this.options.tag || 'div';

    const content = this.options.slots[slot]({
      slots: this.options.slots,
      ...(! parameters || isReactive(parameters)) ? parameters : reactive(parameters),
    });

    const options = {
      'key':                  this.key,
      'data-atomic-key':      this.key,
      'data-atomic-template': slot,
      'class':                `atomic-template atomic-template-${slot}`,
      ...this.options?.attrs,
    };

    return h(tag, options, content);
  }

  /**
   * Declare the prerenderer state.
   * 
   * @param  string  name
   * @return void
   */
  _declarePrerenderer (name) {
    this.declare(name, () => ({
      slots: this.options.slots,
      render: (timeout) => {
        setTimeout(() => this.setState('default'), timeout || 0);
      },
    }));
  }
}

export function useTemplate () {
  return new TemplateManager(...arguments);
};
