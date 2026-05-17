<?php
/**
 * Register and run plugin hooks.
 *
 * @package Plugin_Name
 */

/**
 * Loads action and filter hooks.
 */
class Plugin_Name_Loader {

	/**
	 * Actions list.
	 *
	 * @var array<int, array<string, mixed>>
	 */
	protected $actions = array();

	/**
	 * Filters list.
	 *
	 * @var array<int, array<string, mixed>>
	 */
	protected $filters = array();

	/**
	 * Add an action hook.
	 *
	 * @param string $hook Hook name.
	 * @param object $component Class instance.
	 * @param string $callback Callback method.
	 * @param int    $priority Priority.
	 * @param int    $accepted_args Accepted args.
	 *
	 * @return void
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);
	}

	/**
	 * Add a filter hook.
	 *
	 * @param string $hook Hook name.
	 * @param object $component Class instance.
	 * @param string $callback Callback method.
	 * @param int    $priority Priority.
	 * @param int    $accepted_args Accepted args.
	 *
	 * @return void
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);
	}

	/**
	 * Register all hooks with WordPress.
	 *
	 * @return void
	 */
	public function run() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}
}
