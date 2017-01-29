<?php

namespace JumpGate\ViewResolution\Traits;

trait AutoResolvesViews
{
    /**
     * Find the view for the called method.
     *
     * @param null|string $view
     * @param null|string $layout
     *
     * @return $this
     */
    public function view($view = null, $layout = null)
    {
        $layoutOptions = $this->getLayoutOptions();

        if (! is_null($layout)) {
            $layoutOptions = [
                'default' => $layout,
                'ajax'    => $layout,
            ];
        }

        // Set up the default view resolution
        viewBuilder()->setUp($layoutOptions, $view);
        $this->setupLayout();
    }

    /**
     * Master template method
     * Sets the template based on location and passes variables to the view.
     *
     * @return void
     */
    public function setupLayout()
    {
        $this->layout = viewBuilder()->getLayout();
    }

    protected function getLayoutOptions()
    {
        if (isset($this->layoutOptions)) {
            return $this->layoutOptions;
        }

        return [
            'default' => null,
            'ajax'    => null,
        ];
    }
}
