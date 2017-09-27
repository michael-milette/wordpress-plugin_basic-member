<?php
/*
    "Basic Member" Copyright (C) 2017 TNG Consulting Inc.   (www.tngconsulting.ca)

    Basic Member plugin for WordPress is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Basic Member plugin for WordPress is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Basic Member. If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

include_once('BasicMember_LifeCycle.php');

class BasicMember_Plugin extends BasicMember_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31

        // Create an array containing all categories.
        $args = array("hide_empty" => 0,
                    "type"      => "post",      
                    "orderby"   => "name",
                    "order"     => "ASC" );
        $cats = get_categories($args);
        $categories[] =  '';
        foreach($cats as $category) {
            $categories[] = $category->cat_name;
        }
        
        // Create an array containing URLs of all pages.
        $pags = get_pages(); 
        $pages[] =  '';
        foreach ( $pags as $page ) {
            $pages[] = get_page_link( $page->ID );
        }
        
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'Info' => __('Select a category for each of the two levels of subscription below.</p><p>Tip: To disable a subscription level, leave the category blank.', 'basic-member'),
            'GeneralSettings' => '<h3>' . __('General Settings', 'basic-member') . '</h3>',
            'AccessDeniedMessage' => array(__( 'Access denied message', 'basic-member' ) ),
            'SubscribePageURL' => array_merge( array(__( 'Subscribe page URL', 'basic-member' ) ), $pages ),
            'DisplayLoginOnDeny' => array(__( 'Display login page for restricted content', 'basic-member' ), 'Yes', 'No' ),
            'SubscriberHeading' => '<h3>' . __('Restricted Access for Subscriber Role', 'basic-member') . '</h3>',
            'Info2' => __('Note: Content in the Restricted Access category above will also be displayed to users with a Premium Access role.', 'basic-member'),
            'SubscriberCategory' => array_merge( array(__( 'Category', 'basic-member' ) ), $categories ),
            'SubscriberPlusHeading' => '<h3>' . __( 'Premium Access for Subscriber+ Role', 'basic-member' ) . '</h3>',
            'SubscriberPlusCategory' => array_merge( array(__( 'Category', 'basic-member') ), $categories ),
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Basic Member';
    }

    protected function getMainPluginFileName() {
        return 'basic-member.php';
    }

    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    /*
     * Description: Disables WordPress dashboard, hides the admin bar for subscribers, adds category taxonomy to pages.
     * @return void
     */
    public function disable_admin_ui() {
        // Add categories to pages - WordPress 3.0+ only.
        if ( function_exists( 'register_taxonomy_for_object_type' ) ) {
            register_taxonomy_for_object_type( 'category', 'page' );
        }

        // If user is a subscriber.
        if ( current_user_can( 'subscriber' ) || current_user_can( 'subscriber+' ) ) {
            // Disable the admin bar for subscribers - WordPress 3.1+ only.
            if (function_exists('show_admin_bar')) {
                show_admin_bar( false );
            }
            if ( is_admin() ) { // If on the Dashboard page.
                // Redirect to home if trying to access the dashboard.
                if (function_exists('home_url')) {
                    $homeURL = home_url();
                } else {
                    $homeURL = site_url('/');
                }
                wp_redirect( $homeURL );
            }
        }
    }

    /*
     * Restricts access to content with a membr or prim-membr categories.
     * @return void
     */
    public function restrict_content() {
        $optionsManager = new BasicMember_Plugin();
        // User with no role assigned.
        $subscriber = $this->getOption( 'SubscriberCategory', 'subscriber' );
        // User with a role of subscriberplus.
        $subscriberplus = $this->getOption( 'SubscriberPlusCategory', 'subscriberplus' );

        if(has_category( [ $subscriber, $subscriberplus ] ) ) {
            if( !is_user_logged_in() ) {
                if ($this->getOption( 'DisplayLoginOnDeny', 'Yes' ) == 'Yes') {
                    // Not logged in : Will be redirected to login page when trying to access pages and posts in subscriber or contributor categories.
                    wp_redirect( wp_login_url( get_permalink() ) );
                } else {
                    $message = $this->getOption( 'AccessDeniedMessage', __( 'Access to this page is restricted.','error' ));
                    die($message);
                }
            } else {  // User is logged in.
                // If page is in subscriber category and user has no role, redirect to premium page.
                global $current_user;
                $subscribePageURL = $this->getOption( 'SubscribePageURL', site_url('/prim') );
                if ( has_category( [ $subscriber ] ) && empty($current_user->roles) ) {
                    wp_redirect( $subscribePageURL );
                }
                // If page is in subscriberplus category and user has no role or subscriber role, redirect to premium page.
                if ( has_category( [ $subscriberplus ] ) && ( empty($current_user->roles) || current_user_can( 'subscriber' ) ) ) {
                    wp_redirect( $subscribePageURL );
                }
                // Otherwise display page.
            }
        }
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
        // Disable Admin UI.
        add_action('init', array( &$this, 'disable_admin_ui') );
        // Restrict content.
        add_action('template_redirect', array( &$this, 'restrict_content') );

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
    }
}
