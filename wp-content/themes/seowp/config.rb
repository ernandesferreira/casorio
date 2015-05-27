# Require any additional compass plugins here.

# We use Compass for scss: http://compass-style.org
#
# This is Ruby project configuration file used to setup
# SCSS files compiling from /sass/ folder to style.css
#
# If you want to edit our /sass/ files (which is not recommended
# as you will loose all the changes on the next theme update)
# then you need to recompile it using Compass.
#
# In Terminal (on mac) navigate to theme folder and
# run "compass watch" all your edits to .scss files
# will be automatically compiled into style.css

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "/"
sass_dir = "sass"
images_dir = "images"
javascripts_dir = "javascripts"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :compact

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass
