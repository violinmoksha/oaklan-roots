## Installation for localhost dev box

1. Clone the git repo - `git clone https://github.com/roots/bedrock.git`
2. Run `composer install`
3. Copy `.env.example` to `.env` and update environment variables:
  * `DB_NAME` - Database name
  * `DB_USER` - Database user
  * `DB_PASSWORD` - Database password
  * `DB_HOST` - Database host
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (http://example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (http://example.com/wp)
4. Add theme(s) in `web/app/themes` as you would for a normal WordPress site.
5. ensure ruby bundler is installed via `sudo gem install bundler`
6. run `bundle install`
7. alter config/deploy.rb as needed
8. alter config/deploy/staging.rb as needed
9. alter config/deploy/production.rb as needed
10. Set your site vhost document root to `/path/to/site/web/` (`/path/to/site/current/web/` if using deploys)
11. Access WP admin at `http://example.dev/wp/wp-admin`

### Setup for deploying to staging in a VM

...more coming soon...

### Setup for deploying to production

...stay tuned...
