<?php

namespace GophrSameDay\Admin;

class MenuPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
    }

    public function addMenuPage(): void
    {
        add_options_page(
            'Gophr Same-Day Delivery Settings',
            'Gophr Plugin',
            'manage_options',
            'gophr-same-day',
            [$this, 'renderPage']
        );
    }

    public function renderPage(): void
    {
        echo '<div class="wrap"><h1>Gophr Same-Day Delivery</h1><p>Settings go here.</p></div>';
    }
}