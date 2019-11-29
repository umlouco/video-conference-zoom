Previously, shortcode had to be written in complex way that some people would not feel reliable for this specific plugin. With new update its pretty easy to remember.

### Basic Usage

Use: `[zoom_api_link meeting_id="" link_only="no"]`

Where,

* `meeting_id` = Your meeting ID.
* `link_only` = Show only link or not. Change to "yes" instead of "no" to show link only

Your frontend page should look like:

<img src="https://deepenbajracharya.com.np/wp-content/uploads/2019/11/Meetings-%E2%80%93-Plugin-Tester-1024x520.png">

### How to get Meeting ID

1. Goto your wp-admin
2. In the side menu look of for "Zoom Meeting"
3. Click or hover and then open up "Live meetings" page.
4. Select user from the dropdown on top right.
5. Grab the ID from "meeting ID" column

### Shortcode Template Override

With new version, its possible to override the display of output from default plugin layout. You can do so by following method.

1. Goto **`wp-admin/plugins/video-conferencing-with-zoom-api/templates/shortcode`** folder
2. Copy this "Shortcode" folder to **`yourtheme/video-conferencing-zoom/shortcode/zoom-shortcode.php`**
3. Your done ! Change the styling and divs according to your needs.
