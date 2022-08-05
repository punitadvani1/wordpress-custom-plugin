# wordpress-custom-plugin

Create a plugin that does the following tasks:
1. Register a new post type "movie"
2. Register a new user role "viewer"
3. on frontend, title of posttype "movie" will be appended with string "-Upcoming this year" which should only show up on their single template only, not on archive page
4. Create a wp-cron to send daily email to every user that has user role "viewer" with subject "News of the jungle" and body "Something happening somewhere"
