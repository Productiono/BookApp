<?php

namespace CalCom;

defined('ABSPATH') || exit;

class Admin
{
    /**
     * External app URL to embed inside the dashboard
     *
     * @var string
     */
    private $app_url = 'http://162.55.168.66:3000';

    public function hooks(): void
    {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Register the Bookings admin menu entry
     *
     * @return void
     */
    public function register_menu(): void
    {
        add_menu_page(
            __('Bookings', 'calcom'),
            __('Bookings', 'calcom'),
            'read',
            'calcom-bookings',
            [$this, 'render_admin_page'],
            'dashicons-calendar-alt',
            26
        );
    }

    /**
     * Load assets specific to the admin embed page
     *
     * @param string $hook
     * @return void
     */
    public function enqueue_assets(string $hook): void
    {
        if ('toplevel_page_calcom-bookings' !== $hook) {
            return;
        }

        wp_enqueue_style('calcom-admin-embed', CALCOM_ASSETS_URL . 'css/admin.css', [], null);
    }

    /**
     * Render the Bookings page with an embedded login form
     *
     * @return void
     */
    public function render_admin_page(): void
    {
        ?>
        <div class="calcom-admin-wrap">
            <div class="calcom-admin-frame-wrapper">
                <iframe
                    src="<?php echo esc_url($this->app_url); ?>"
                    class="calcom-admin-iframe"
                    title="Cal.com bookings"
                    frameborder="0"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
        <?php
    }
}
