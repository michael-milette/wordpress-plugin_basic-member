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
            'GeneralSettings' => '<h3>' . __('General Settings', 'basic-member') . '</h3>',
            'DisplayLoginOnDeny' => array(__( 'Display login page for restricted content', 'basic-member' ), 'Yes', 'No' ),
            'AccessDeniedMessage' => array(__( 'Access denied message. Will be displayed when trying to access restricted content and <strong>Display login page for restricted content</strong> is set to <strong>No</strong>.', 'basic-member' ) ),
            'SubscribePageURL' => array_merge( array(__( 'Subscribe page URL. This will be displayed when a logged-in user attempts to access premium content.', 'basic-member' ) ), $pages ),
            'SubscriberHeading' => '<h3>' . __('Restricted Access for Subscriber Role', 'basic-member') . '</h3>',
            'SubscriberCategory' => array_merge( array(__( 'Category to associate with the Subscriber role (blank to disable):', 'basic-member' ) ), $categories ),
            'SubscriberPlusHeading' => '<h3>' . __( 'Premium Access for Subscriber+ Role', 'basic-member' ) . '</h3>',
            'SubscriberPlusCategory' => array_merge( array(__( 'Category to associate with the Subscriber+ role (blank to disable):', 'basic-member') ), $categories ),
            'Info' => __('Note: Content in the Restricted Access category above will also be displayed to users with a Premium Access role.', 'basic-member'),
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
     *
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
     *
     * @return void
     */
    public function restrict_content() {
        $optionsManager = new BasicMember_Plugin();
        // User with no role assigned.
        $subscriber = $this->getOption( 'SubscriberCategory', 'subscriber' );
        // User with a role of subscriberplus.
        $subscriberplus = $this->getOption( 'SubscriberPlusCategory', 'subscriber-2' );

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
    
    /*
     * Build list of subscriber and subscriberplus catgory ids.
     *
     * @param neg boolean (optional) true makes ID's negative, false makes them positive.
     * @return string containing commas delimited list of category IDs.
     */
    private function restrictedCategoryList($neg = false) {
        global $current_user;
        $subscriberIDs = '';
        
        // Add categories if authenticated user has a role that which is not subscriber+.
        if (!is_user_logged_in() || empty($current_user->roles) || current_user_can( 'subscriber+' ) ) {
            $subscriberIDs = $this->getOption( 'SubscriberCategory', 'subscriber' );
            if ( !empty( $subscriberIDs ) ) {
                $subscriberIDs = ( $neg ? '-' : '' ).strval( get_cat_ID( $subscriberIDs ) );
            }
        }
        
        // Add categories if authenticated user has a role that which is not subscriber.
        if (!is_user_logged_in() || empty($current_user->roles) || current_user_can( 'subscriber' ) ) {
            $subscriberPlusID = $this->getOption( 'SubscriberPlusCategory', 'subscriber-2' );
            if ( !empty( $subscriberPlusID ) ) {
                if ( is_numeric( $subscriberIDs ) ) {
                    $subscriberIDs .= ',';
                }
                $subscriberIDs .= ( $neg ? '-' : '' ).strval( get_cat_ID( $subscriberPlusID ) );
            }
        }
        return $subscriberIDs;
    }

    /* 
     * Exclude Category Posts from Home Page
     *
     * @param object query.
     * @return object query.
     */
    public function exclude_post_category( $query ) {
        if ( $query->is_home() ) { // && $query->is_main_query() ) {
            $subscriberIDs = $this->restrictedCategoryList(true);

            // Filter out membership category numbers.
            if (!empty($subscriberIDs)) {
                $query->set( 'cat', $subscriberIDs );
            }
        }
        return $query;
    }
    
    /* 
     * Hide categories from WordPress category widget
     *
     * @param array
     * @return array
     */
    public function exclude_widget_categories($args){
        $args["exclude"] = $this->restrictedCategoryList();
        return $args;
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37

        // Disable Admin UI.
        add_action('init', array( &$this, 'disable_admin_ui') );
        // Restrict content.
        add_action('template_redirect', array( &$this, 'restrict_content') );
        if ($this->getOption( 'DisplayLoginOnDeny', 'Yes' ) == 'No') {
            // Hide restricted categories in blogroll.
            add_action( 'pre_get_posts', array( &$this, 'exclude_post_category' ) );
            // Hide restricted categories in category list.
            add_filter( 'widget_categories_args', array( &$this, 'exclude_widget_categories' ) );
        }

        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39
    }
}
