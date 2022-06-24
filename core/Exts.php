<?php

namespace ITP;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Exts
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'api_endpoints']);
    }
    public function api_endpoints()
    {
        register_rest_route('itprix/', '/all_users', [
            'methodes' => 'GET',
            'callback' => [$this, 'all_users'],
        ]);
        register_rest_route('itprix/', '/all_customers', [
            'methodes' => 'GET',
            'callback' => [$this, 'all_customers'],
        ]);

        register_rest_route('itprix/', '/all_storeOwner', [
            'methodes' => 'GET',
            'callback' => [$this, 'all_storeOwner'],
        ]);
        register_rest_route('itprix/', '/storeOwner', [
            'methodes' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'storeOwner'],
            'args' => [
                'id' => [
                    'required' => false,
                    'type' => 'integer',
                ],
                'res' => [
                    'required' => false,
                    'type' => 'string',
                ]
            ]
        ]);
        register_rest_route('itprix/', '/customer', [
            'methodes' => \WP_REST_Server::READABLE,
            'callback' => [$this, 'customer'],
            'args' => [
                'id' => [
                    'required' => false,
                    'type' => 'integer',
                ]
            ],
        ]);
    }
    public function all_users()
    {

        $all_users = get_users(
            [
                'role__in' => [
                    'shop_manager', 'customer'
                ],
            ]
        );
        return $all_users;
    }
    public function customer($prems)
    {
        $id = $prems->get_param('id');
        $res = false;
        if (!empty($id)) {
            $user = get_user_meta($id);
            if (!empty($user)) {
                $res = $this->customerData($id);
            }
        } else {
            $all_customers = get_users(
                [
                    'role__in' => [
                        'customer'
                    ],
                ]
            );
            $res = false;
        }
        $all_customers = get_users(
            [
                'role__in' => [
                    'customer'
                ],
            ]
        );
        return rest_ensure_response($res);
    }

    public function all_storeOwnoer()
    {

        $all_storeOwnoer = get_users(
            [
                'role__in' => [
                    'shop_manager', 'shop_owner'
                ],
                'meta_key' => 'has_shop',
                'meta_compare' => '==',
                'meta_value' => 'yes'
            ]
        );
    }
    public function storeOwner($prem)
    {


        $id = $prem->get_param('id');
        $methode = $prem->get_param('res');
        $res = false;

        if (!empty($id)) {
            $user = is_author($id);
            var_dump($user);
            if ($user !== false) {
                //user id exist
                $customer = new \WP_User($id);
                if (!empty($methode)) {
                    if ($methode == 'set') {
                        $customer->add_role('shop_manager');
                        update_user_meta($id, 'has_shop', 'yes');
                    } elseif ($methode == 'unset') {
                        $customer->remove_role('shop_manager');
                        update_user_meta($id, 'has_shop', 'no');
                    }
                } else {
                    $res = $user;
                }
            }
        }
        return rest_ensure_response($res);
    }
    public function get_storeOwnoer($id)
    {

        $info = get_userdata($id);
        if (in_array('shop_manager', $info->roles)) {
            return $info;
        } else {
            return 'not have store';
        }
    }

    private function customerData($id)
    {


        $data = new \WP_User($id);
        $ud = get_userdata($id);
        $fields = ['first_name', 'last_name', 'user_phone', 'has_shop', 'store_name'];
        $nData = [];

        $has_shop = get_user_meta($id, 'has_shop', true);
        $nData['fname'] = get_user_meta($id, 'first_name', true);
        $nData['lname'] = get_user_meta($id, 'last_name', true);
        $nData['user_email'] = $data->user_email;
        $nData['pic'] = esc_url(get_avatar_data($id)['url']);
        $nData['has_shop'] = $has_shop;
        if ($has_shop == 'yes') {
            $nData['shop_name'] = get_user_meta($id, 'store_name', true);
        }
        return $nData;
    }
}

new Exts();
