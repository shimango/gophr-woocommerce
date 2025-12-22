<?php

namespace GophrSameDay\Admin;

class MenuPage
{
    public static function getInstance(): MenuPage
    {
        return new self();
    }

    public function addMenuPage(): void
    {
        add_menu_page(
            'Gophr Same-Day Delivery Settings',
            'Gophr Plugin',
            'manage_options',
            'gophr_same_day',
            [$this, 'renderPage'],
            'data:image/svg+xml;base64,' . base64_encode( file_get_contents( GOPHR_SAME_DAY_PATH . 'assets/images/gophr.svg' ) ),
        );

        add_submenu_page(
            'gophr_same_day',
            'Gophr Same-Day Delivery Settings',
            'manage_options',
            'gophr_same_day',
            'gophr_same_day1',
            [$this, 'renderSubPage'],

        );
    }

    public function renderPage(): void
    {
        echo '<div class="wrap"><h1>Gophr Same-Day Delivery</h1><p>Settings go here.</p></div>';
    }

    public function renderSubPage(): void
    {
        echo '<div class="wrap"><h1>Gophr Same-Day Delivery</h1><p>SubpgE</p></div>';
    }
}