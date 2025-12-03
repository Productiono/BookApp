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

    /**
     * Relative path to the login screen. Users will be redirected back to the
     * embedded dashboard after logging in.
     *
     * @var string
     */
    private $login_path = '/auth/login?redirect=/';

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
        $login_url = esc_url($this->get_login_url());
        ?>
        <div class="calcom-admin-wrap">
            <div class="calcom-admin-frame-wrapper">
                <iframe
                    src="<?php echo $login_url; ?>"
                    class="calcom-admin-iframe"
                    title="Cal.com bookings"
                    frameborder="0"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
        <?php
    }

    /**
     * Build the login URL to ensure we always load the login form first.
     * Once authenticated, the remote app handles redirecting the user to the
     * dashboard within the same iframe.
     *
     * @return string
     */
    private function get_login_url(): string
    {
        $base = trailingslashit($this->app_url);
        $login_path = ltrim($this->login_path, '/');

        return $base . $login_path;
    }
}
