=== Expenses ===
Contributors: ENDif Media
Donate link: https://endif.media
Tags: expense, data collection, users, business, finances
Requires at least: 4.3
Tested up to: 4.9.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A useful expense record keeping plugin for WordPress.

== Description ==

A useful expense record keeping software. Users are able to create, edit, and post Expenses.

There are five expense types to choose from - Mileage, Hotel, Plane Tickets, Receipts, Food/Ent, and Parking.

Users (other than admins) can only create an expense for themselves. They won't be able to see, create, or edit anyone
other user's expense records.

Works for author, contributer, editor, subscriber, and admin profile types with admin being the main account. There is
a known bug with WooCommerce. Subscriber profile types: Woo uses subscriber profiles as "customers" and alters the
profile view removing access to the standard WordPress dashboard. I would recommend using one of the other non-admin profile
types for employees that are just using the site for expense tracking.

== Installation ==

1. Upload `expenses` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

= With WP-CLI =
`wp plugin install expenses --activate`

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2.
3.
4.

== Changelog ==

= 1.2.0 =
* [fix] reports not displaying receipt expense type - typo

= 1.0.1 =
* Initial release