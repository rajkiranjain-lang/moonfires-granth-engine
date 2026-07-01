<?php
/**
 * Hook Loader
 * Manages all WordPress hooks
 */

namespace Moonfires\Granth\Core;

class Loader {
    private $actions = [];
    private $filters = [];

    /**
     * Add action
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions[$hook][] = [
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }

    /**
     * Add filter
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters[$hook][] = [
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];
    }

    /**
     * Run all registered hooks
     */
    public function run() {
        // Run filters
        foreach ($this->filters as $hook => $callbacks) {
            foreach ($callbacks as $callback) {
                add_filter(
                    $hook,
                    [$callback['component'], $callback['callback']],
                    $callback['priority'],
                    $callback['accepted_args']
                );
            }
        }

        // Run actions
        foreach ($this->actions as $hook => $callbacks) {
            foreach ($callbacks as $callback) {
                add_action(
                    $hook,
                    [$callback['component'], $callback['callback']],
                    $callback['priority'],
                    $callback['accepted_args']
                );
            }
        }
    }
}
