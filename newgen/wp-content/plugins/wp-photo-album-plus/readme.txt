=== WP Photo Album Plus ===
Contributors: opajaap
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=OpaJaap@OpaJaap.nl&item_name=WP-Photo-Album-Plus&item_number=Support-Open-Source&currency_code=USD&lc=US
Tags: photo, album, photoalbum, gallery, slideshow, sidebar widget, photowidget, photoblog, widget, qtranslate
Version: 3.0.5
Stable tag: trunk
Author: J.N. Breetvelt
Author URI: http://www.opajaap.nl/
Requires at least: 3.0
Tested up to: 3.2

This plugin is designed to easily manage and display your photo albums and slideshows within your WordPress site. 
Additionally there are four widgets: Photo of the day, a Search Photos widget, a Top Ten Rated photo widget and a Mini slideshow widget.

== Description ==

This plugin is designed to easily manage and display your photo albums and slideshows within your WordPress site. 

* You can create various albums that contain photos as well as sub albums at the same time.
* There is no limitation to the number of albums and photos.
* There is no limitation to the nesting depth of sub-albums.
* You have full control over the display sizes of the photos.
* You can specify the way the albums are ordered.
* You can specify the way the photos are ordered within the albums, both on a system-wide as well as an per album basis.
* The visitor of your site can run a slideshow from the photos in an album by a single mouseclick.
* The visitor can see an overview of thumbnail images of the photos in album.
* The visitor can browse through the photos in each album you decide to publish.
* You can add a Photo of the day Sidebar Widget that displays a photo which can be changed every hour, day or week.
* You can add a Search Sidebar Widget which enables the visitors to search albums and photos for certain words in names and descriptions.
* You can enable a rating system and a supporting Top Ten Photos Sidebar Widget that can hold a configurable number of high rated photos.
* Apart from the full-size slideshows you can add a Sidebar Widget that displays a mini slideshow.
* There is a General Purpose widget that is a text widget wherein you can use wppa+ script commands.
* Almost all appearance settings can be done in the settings admin page. No php, html or css knowledge is required to customize the appearence of the photo display.
* International language support for static text: Currently included foreign languages files: Dutch, Japanese, French(outdated), Spanish, German.
* Inrernational language support for dynamic text: Album and photo names and descriptions fully support the qTranslate multilanguage rules and have separate edit fields for all qTranslate activated languages.

Plugin Admin Features:

You can find the plugin admin section under Menu Photo Albums on the admin screen.

* Photo Albums: Create and manage Albums.
* Upload photos: To upload photos to an album you created.
* Import photos: To bulk import photos to an album that are previously been ftp'd.
* Settings: To control the various settings to customize your needs.
* Sidebar Widget: To specify the behaviour for an optional sidebar widget.
* Help & Info: Much information about how to...

== Installation ==

= Upgrade notice =
This version is: Major rev# 3, Minor rev# 0, Fix rev# 2, Hotfix rev# 000.
If you are upgrading from a previous Major or Minor version, note that:
* If you modified wppa_theme.php and/or wppa_style.css, you will have to use the newly supplied versions. The previous versions are NOT compatible.
* If you set the userlevel to anything else than 'administrator' you may have to set it again. Note that changing the userlevel can be done by the administrator only!
* You may have to activate the sidebar widget again.

= Standard installation when not from the wp plugins page =
* Unzip and upload the wppa plugin folder to wp-content/plugins/
* Make sure that the folder wp-content/uploads/ exists and is writable by the server (CHMOD 755)
* Activate the plugin in WP Admin -> Plugins.
* If, after installation, you are unable to upload photos, check the existance and rights (CHMOD 755)
of the folders wp-content/uploads/wppa/ and wp-content/uploads/wppa/thumbs/. 
In rare cases you will need to create them manually.
* If you upgraded from WP Photo Album (without plus) and you had copied wppa_theme.php and/or wppa_style.css 
to your theme directory, you must remove them or replace them with the newly supplied versions.

== Frequently Asked Questions ==

= How does the search widget work? =

* A space between words means AND, a comma between words means OR.
Example: search for 'one two, three four, five' gives a result when either 'one' AND 'two' appears in the same (combination of) name and description. 
If it matches the name and description of an album, you get the album, and photo vice versa.
OR this might apply for ('three' AND 'four') OR 'five'. Albums and photos are returned on one page, regardless of pagination settings, if any. 
That's the way it is designed.

= How can i translate the plugin into my language? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=1349

= How do i install a hotfix? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=823

= What to do if i get errors during upload or import photos? =

* It is always the best to downsize your photos to the Full Size before uploading. It is the fastest and safest way to add photos tou your photo albums.
Photos that are way too large take unnessesary long time to download, so your visitors will expierience a slow website. 
Therefor the photos should not be larger (in terms of pixelsizes) than the largest size you are going to display them on the screen.
WP-photo-album-plus is capable to downsize the photos for you, but very often this fails because of configuration problems. 
Here is explained why:
Modern cameras produce photos of 7 megapixels or even more. To downsize the photos to either an automaticly downsized photo or
even a thumbnail image, the server has to create internally a fullsize fullcolor image of the photo you are uploading/importing.
This will require one byte of memory for each color (Red, Green, Blue) and for every pixel. 
So, apart form the memory required for the server's program and the resized image, you will need 21 MB (or even more) of memory just for the intermediate image.
As most hosting providers do not allow you more than 32 MB, you will get 'Out of memory' errormessages when you try to upload large pictures.
You can configure WP to use 64 MB (That would be enough in most cases) by specifying `define(‘WP_MEMORY_LIMIT’, ‘64M’);` in wp-config.php, 
but, as explained earlier, this does not help when your hosting provider does not allows the use of that much memory.
If you have control over the server yourself: configure it to allow the use of enough memory.
Oh, just Google on 'picture resizer' and you will find a bunch of free programs that will easily perform the resizing task for you.


== Changelog ==

= 3.0.5 =

= Bug fixes =

* IMPORTANT Fix: All problems that are related to pre-rendering are fixed. 
The problems with themes like Thesis and plugins like the face-book-meta-tags-plugin that 
perform a pre-rendering of a post or excerpt are solved now. 
The restrictions on using the rating system (that did not work anyway) are no longer applicable.
* Under some circumstances when using qTranslate, the proper language file was not loaded. Fixed.

= Hot fixes after initial release =

* 001: Fixed erroneous link in albumcover

= 3.0.4 =

= New features = 

* You can back-up and restore the settings and reset them to default values.
* Added table X in the settings panel, being a read only table displaying the php configuration.

= Enhancements =

* Improved error reporting and documentation of limitations in admin pages.

= Bug fixes =

* Fixed an no harmfull warning in photo of the day widget admin page.
* Removed a superfluous p-opening tag.

= Known problems =

* The Thesis theme has a problem with the <input > field that is required for the rating system. (nonce field).
The rating system should be disabled in that case (using Thesis).

= Hotfixes after initial release =

* 001: Added class wppa-slideshow-browse-link to enable hiding it with display: none. This was a special cutomer request and not an error.
* 002: Photo specific link will now also be copied during a copy photo action.
* 003: Removed an empty <p></p> right before a wppa invocation. 
* 004: Fix for facebook plugin (?)

= 3.0.3 =

= New features = 

* Increase configurability of links from album cover photo.
* A re-initializing action (Table VIII, item 3) has been added. This will be helpfull in multiblog (network) sites.

= Bug fixes =

* Includes all hot-fixes since 3.0.2.000.
* Minor cosmetic changes in the new settings page.

= Hot fixes after initial release =

= Known problems =

* The Thesis theme has a problem with the <input > field that is required for the rating system. (nonce field).
The rating system should be disabled in that case (using Thesis).


= 3.0.2 =

= New features = 

* The Settings page has been rewritten to make it more user friendly. 
All settinges are grouped into tables, and are identifiable by its table number and item number.
* Increased link configurability. You can link mphotos and thumbnails now also to the plain file. 
You can define photo specific links: All photos can have a unique link url and title. 
You can choose to use that link in all 5 different places where a photo link can be configured. 
Please check the link settings in the Settings screen, Table VI. You might want to change something there.
* Additionally to the family and size you can now also set the colors for the fonts used in wppa+.

= Bug fixes =

* Includes all hot-fixes since 3.0.1.000.
* The mouseover effect now also works on TopTen thumbnail images.
* Fix for Column width = auto. This works now the same like %%size=auto%%

= Hot fixes after initial release =

* 001: Made noncefield conditional to rating system enabled
* 002: Admin functions now also work in SSL admin
* 003: If an image has a link configured, the cursor will be a pointer (hand).


= 3.0.1 =

= New features =

* WPPA+ Now supports Multi language sites that use qTranslate. 
Both album and photo names and descriptions follow the qTranslate multilanguage rules.
In the Album Admin page all fields that are multilingual have separate edit fields for each activated language.
For more information on multilanguage sites, see the documentation of the qTranslate plugin.

= Enhancements =

* You can link media-like photos (those made with %%mphoto=..%%) to a different (selectable) page, either to a full-size photo on its own or in a slideshow/browseable.
* You will now get a warning message inclusive an uncheck of the box if your jQuery version does not support delay and therefor not the fadein after fadeout feature.
* Improved consistency in the layout of the different types of navigation bars.

= Pending enhancement requests =

* Multisite support
* More than one photo of the day
* Fullscreen slideshow

= Known bugs =

* None, if you find one, please let me know and i will fix 'm

= Hot fixes since the initial release =

* 001: HTML in photo of the day widget fixed
* 002: Fixed 'Start undefined'
* 003: You can now rotate images when they are already uploaded
* 004: Photo of the day option change every pageview added
* 005: Photo of the day split padding top and left
* 006: If Filmstrip is off you can overrule display filmstrip by using %%slidef=.. and %%slideonlyf=..
* 007: Clear:both added to thumbnail area
* 008: Fixed a problem where photos were not found if the number of found photos was less than or equal to the photocount treshold value
* 009: You can now upload zipfiles with photos if your php version is at least 5.2.7.
* 010: Fixed a Invalid argument supplied for foreach() warning in upload.
* 011: Fixed a wrong link from thumbnail to slideshow.
* 012: Changed the check for minimal size of thumbnail frame.
* 013: Fixed a problem where a bullet was displayed as &bull in some browsers.
* 014: Fixed a problem where the navigation arrows in the filmstrip were not hidden if the startstop bar was disabled.
* 015: New feature: If slideshow is enabled, double clicks on filmthumbs toggles Start/stop running slideshow. Tooltip documents it.
* 016: Slides and filmthumbs have the same sequence now when ordering is Random.
* 017: Some people do not read the settings page and get in panic when they see two or three colums of album covers after an upgrade, so i changed the defaults for the columns tresholds to 1024.
* 018: TopTen widget initializes runtime also now, just in case it is the first.
* 019: Fixed alignment problem in multi column, unequal cover heights.
* 020: Photo of the day widget now also initializes runtime.
* 021: Fix for pre-rendering themes like thesis.

= 3.0.0 =

= New features =

* You can link thumbnails to different (selectable) page, either to a full-size photo on its own or in a slideshow/browseable.
* You can link the photo of the day to a full-size photo on its own or in a slideshow/browseable or to the current photos album contents display (thumbnails).
* You can set the thumbnail display type to --- none ---. This removes the 'View .. photos' link on album covers, while keeping the 'View .. albums' link.
* When the Slideshow is disabled and there are more than the photocount treshold photos, the 'Slideshow'-link is changed to 'Browse photos' with the corresponding action.
* The front end (theme) is now seperately translatable. Only 43 words/small sentences need translation. A potfile is included (wppa_theme.pot).
* You can now easy copy a single photo to an other album in the Photo Albums -> Edit album admin page.
* There is a new script command: %%mphoto=..%%. This is an alternative for %%photo=..%% and displays the single photo with the same style as normal media photos with background and caption. No associated links yet.

= Bug fixes =

* The 'Slideshow' and 'Browse photos' link now also point to the page selected in the edit album form.

= Hot fixes after initial release =

* 001: [caption] is not allowed to have html (wp restriction), tags are now removed from photo description for use with [caption]
* 002: Fixed a breadcrumb nav that did not want to hide itself when Display breadcrumb was unchecked
* 003: You can now import media photos from the upload directory you specified in the wp media settings page also when it is not the default dir.
* 004: Fixed a problem where, when pagination is off, in a mixed display of covers and thumbs, the covers were not shown.
* 005: added class size-medium to mphotos ([caption])


= Notes =

* Due to internal changes, there is a speed-up of apprix 30% with respect to earlier versions.
* Due to internal changes, you will have to re-modify wppa_theme.php if you used a modified one. wppa_theme is now a function.
* Due to internal changes, it is most likely that this problem will be fixed: http://wordpress.org/support/topic/plugin-wp-photo-album-plus-page-drops-when-activated-on-page?replies=24#post-1965780
* If you had set *No Links* for thumbnails, you will have to set it again.

= 2.5.1 =

= New features =
* A 'General purpose' Sidebar Widget has been added. It is a text widget wherein you can use wppa+ script commands just like in pages or posts.

= Bug Fixes =
* This version includes all 21 hot fixes released after the initial release of version 2.5.0 and some minor cosmetic ccorrections.

= Hot fixes after initial release =
* 001: Changed the way new settings get their default values during plugin activation.
* 002: Reset %%size=.. at end of widget code to prevent inheritage of wrong size in case widget is rendered before main column.
* 003: Added vertical alignment and text above photos in slideshow widget.
* 004: The words Start and Stop are now translatable.

= 2.5.0 =

= New Features =
* A rating system has been implemented.
* A TopTen Sidebar Widget has been added.
* You can enter a custom url and title for the link from the Photo of the day.
* There is a new module: Export. This enables you to transfer entire treestructures of albums with photos from one WP installlation to another.
It is also usefull for backup purposes. If your php version is at least 5.2.7, a zip-file will be created that contains all the required information and photos.
If your php version is older, a bunch of files is created, `.pmf` being 'photo meta file', containing name and description of the photo and the album it belongs to. `.amf` being 'album meta files' that hold the album name and description. `.jpg/.png/.gif` being the pictures.
Because it is allowed for a photo to not have a name, and it is allowed for multiple photos to have the same name within an album, 
it is not possible to check for duplicate photos.
So, be carefull not to import transferred photos more than once or you will end up with duplicate pictures.
A spin off of this module is that you can now upload a zip-file that contains photos only that can be imported.
The import of zip files is done in two passes, in the first pass you can select the zipfiles to be extracted, 
the second pass enables you to select the photos and albums you want to import.

= Enhancements =
* All three widgets are now dynamic. You will need to re-activate the Photo of the Day and the Search widget after upgrade.
* An attempt will be made to recover from a manually performed update without de- and re-activation the first time you enter a WPPA+ addmin page.
* Added thumbnail text font-family and size settable in the Settings screen.

= Bug Fixes =
* Fixed an errormessage on activation of the plugin in debug mode.
* Fixed W3C Validator errors and warnings.
* Fixed a bug where thumbnail text did not recognize html while html allowed in descriptions.

= Hot fixes after initial release =
* 001: Fix empty thumbnail page when n <= treshold.
* 002: Fix parse error during installation due to conflict with other plugin or theme changed ZipArchive::CREATE to 1.
* 003: Added CHMOD support in settings page.
* 004: Do not set default rights on creation of dirs, use settings page instead.
* 005: Slideonly defined. %%slideonly=.. is like %%slide=.. but without nav bars and always running.
* 006: Patch for IE, give calculated width to textframe in covers, float and position.
* 007: Patch to work patch 006 in variable column width.
* 008: You can display covers in 2 or 3 columns if the display area is wider than given numbers of pixels.
* 009: Fixed a warning in import photos where .pmf files do not exist. Added margin:0 for cover images for patch 006 to work in certain themes.
* 010: Protect rating system with nonce field. Moved 2 sec delay from js to php, to work in refresh page.
* 011: Fixed slashes in thumbnail popup descriptions.
* 012: Thumbnails can now be auto spaced while margin is a minimum.
* 013: You can now import photos from any (sub)directory starting at wp-content/uploads, hence from the wp media dir.
* 014: Fixed Album not found err during import when there are quotes in the album and the parents album name. 
       Import will now attempt to use old album and photo id's when previously exported. 
       This improves the usability of the export/import mechanism as a backup tool.
* 015: Fixed missing slideonly in RSS.
* 016: New widget added: Slideshow Widget. For clearity: all WPPA+ widgets have 'WPPA+' in their description.
* 017: The slideshow widget is expanded width: link url, tooltip text, own timeout timer and subtitle.
       Fixed a w3c validation errror.
* 018: Appearence setting did not work on fullsize name and description. Fixed.
* 019: %%wppa%% %%photo=..%% %%size=..%% %%align=..%% now also works as expected for single portrait images. size = width when single photo.
* 020: Fixed a layout problem in RSS that was a side effect of a patch for IE.
* 021: Suppressed a warning message when the php config does not allow you to change the time limit.
   
= 2.4.4 =

* Improved RSS behaviour.
* Less 'Out of memory' errors when uploading or importing photos.
* Improved error messages and error handling.
* Improved performance.
* You need no longer modify the loop. Corresponding files are removed.
* You can now configure a custom breadcrumb separator, either select a pre-defined one, a html sequence or an image.
* Conversion to utf-8 was broken. Fixed.
* The albumlinks under the thumbnails in the search results did not work. Fixed.
* In a virgin new wp installation it is no longer needed to upload anything throug a core wp procedure before you can use wppa+, the plugin will attempt to create the uploads directory.


= 2.4.3 =

* You can now set Width and Height for FullSize images independantly.
* All albums have an owner (by user name). If the administrator enables 'Restrict album access to owners only' 
the albums can only be edited, uploaded to and imported to by their owner and by an administrator. 
An owner of an album can give it away to an other user.
An administrator can change the owner of an album.
On upgrading to this rev, the user that performs the upgrade will become the owner of the existing albums.
* The import depot is now setup on a per username basis. 
E.g.: Joe will find his upload directory as .../wp-content/wppa-depot/Joe to be created at his first attempt to import.
This is to prevent the import of another users photos while importing photos at the same time. 
* The Last Album Used is remembered on a per username basis.
* You can setup the sidebar widget to use photos from any combination of albums. 
You can select multiple albums to any available number, select All albums, 
All albums with the parent set to -separate- or All albums except the separate ones.
* If the linkpage of the sidebar widget is set to ---none--- clicking the image opens a new browser window with the full size image.

= 2.4.2 =

* You can set the display of name and description under a thumbnail now independently.
* Fixed typographic issue on album cover display: View .. albumsand .. photos
* Dramatically improvement of rss feeds to mail programs
* You can switch off the links from thumbnails to fullsize images
* Performance improvements at client side
* More detailed hard-coded style information generated to cope with themes that exhibit the 'unwanted tt-tag phenomenon' as described in this support-topic: http://wordpress.org/support/topic/unwanted-tt-tags?replies=1

= 2.4.1 =

= New features =
* You can set the location of the coverphotos and the thumbnail images when set to 'as covers' independently to the left hand side or the (default) right hand side on the settings page.

= Bug fixes =
* Various minor fixes in behaviour and lay-out.

= 2.4.0 =

= New Features =
* There is a filmstrip-navigation bar.
* You can set the column width to 'auto' for themes with floating main columns.
* You can decide to hide the display of name and description under the full size images and slideshows.
* You can decide to hide the navigation bars over and under the full size images and slideshows.
* You can set the initial timeout time for slideshows.
* You can set the fading to fadein after fadeout as opposed to fadein and fadeout simultanuously.
* You can specify padding for the picture of the day widget in the widget settings page.
* You can align a photo, slideshow, cover or thumbnail display which is smaller than the display column, 
including the navigation bars and descriptions to left, center or right.

= Bug fixes =
* Hotfix since first release of 2.3.0: Popup on top (z-index: 1000) to avoid truncation.
* The thumbnail images in the standard layout are shown properly, even in IE 6 and 7. The popup window is still on the wrong place and damages the layout area a little, but it's much better now.
* Fixed another IE<8 incompatibility issue: next photo link is now on the same line as previous photo link. Same for next page link.
* Fixed a layout problem for fullsize images when vertical align was not 'fit' and when %%size= %% used.
* Fixed a horizontal alignment problem where photos wer smaller than the Full Size and stretching was off.

= Other changes =
* Changed the names of the Dutch language files to the new standard (wppa-nl.mo wppa-nl.po)
* Dropped tags.txt. The documentation and a tutorial is on http://wppa.opajaap.nl/

= 2.3.2 =
* IMPORTANT FIX for missing file extensions.

= 2.3.0 =

= New Features =
* There is now a search widget to search for words in album and photo names and decriptions.
* Full size photos may be smaller than the theme's display column. You can horizontally align them in that case to left, center or right.

= Enhancements =
* Even more configurable settings in the Settings panel. There is a very good chance that you do no longer need to customize wppa_style.css.
* Even the overlapping of thumbnail images can be avoided by the use of settings in the settings page!
* Better automatic repair of failed uploads and imports.
* Further split up code into functional parts (more files).
* Better error handling and reporting.
* Small cosmetic changes in admin and theme displays.

= Bug fixes =
* Thumbnail images will now be created correctly even when coverphoto is smaller than thumbnail image. Smallest size will be 100 px. (Hotfix 2.2.0)
* Fix for 'Album could not be updated' error message for certain sql configurations when coverphoto was 'random'.
* You will no longer get error message on not existing thumbnail when looking for image attributes.
* Only image files and all of them will be shown in 'import photos' regardless of the servers php implementation of glob('*.*').
* Thumbnails will be displayed even when pagination is switched off for both albums and thumbnails (!)

= 2.2.0 =
= New Features =

* The ability to give manage albums rights to selected roles.
* The ability to give upload rights to selected roles.
* The ability to give manage sidebar widget rights to selected roles.
* The settings admin page can only be managed by administrators.
* You can now decide to resize photos at upload time to the currently specified full size.
* You can now bulk upload photos. Use an FTP program to upload files and use the new tab 'Import photos' to import them to wppa.
* A link to ---nothing--- for album cover photo and title.
* The ability to disable slideshows.
* You can now decide to show photo name and description under the (standard) thumbnail images

= Bug fixes =
* Added "clear:none" to H2 album title and thumb title when in display thumbs like covers mode. This repairs the cover layout in some themes.

= Known issues =
* See: 2.1.0

= Pending enhancement requests =
* A nuber of thumbnails in the sidebar widget with links to full images.
* Additional animation effects in slideshows and browse full-size images.* A display alternative to have fullsize images together with a list of thumbnail images.
* The possibility to include one or more sub-albums to be used in the sidebar widget.
* Search the photo names and descriptions.

= 2.1.0 =
= New Features =
* Pagination of album covers and thumbnail images can be configured.
* In order to reduce the need for editing wppa_style.css after almost every update, the most of the appearance attributes can now be set in the albums settings admin page. New file: wppa_theme.js.
* Implementation of direct slideshow. I.e. `%%slide=nn%%` like `%%album=nn%%` and `%%cover=nn%%`
* The ability to use a single photo in a post or page. I.e. `%%photo=nn%%` The appropriate insertion codes can be retrieved from the edit album admin page.
* There is the ability to convert the wppa database tables to UTF-8 characters. This enables a.o. Turkish character support.

= Bug fixes =

= Enhancements =
* Thumbnail images will now be displayed together with name and description when pop-up is enabled.
* There is a new select option for the vertical alignment of fullsize images: 'fit' that leaves no superfluous space before or after full size images.
* You can now - systemwide - decide wheter or not to start the slideshow to run at invocation.
* Breadcrumb display will now include nested pages.

= Known issues =
* There is a conflict with the plugin called "n3rdskwat-mp3player".
* There may be conflicts with the themes: "Thesis" revision 7, "Coral" and "Montezuma".
* There are layout issues for thumbnails and thumbnail popups in IE6 and IE7.
* When activating the plugin, you may see the warning message: "The plugin generated 255 characters of unexpected output during activation. (etc)".
This message seems to be a WP issue rather than a wppa issue and can be ignored.

= Pending enhancement requests =
* The ability to SELECT multiple files to upload. Currently selection must be done one by one, upload can be done in batches of up to 15 files.
* Additional animation effects in slideshows and browse full-size images.
* The ability to give upload rights to selected roles/users.
* A display alternative to have fullsize images together with a list of thumbnail images.
* The possibility to include one or more sub-albums to be used in the sidebar widget.

= 2.0.2 =
= New Features =

= Bug fixes =
* (2.0.2) Definition of PLUGIN_PATH changed to WPPA_PLUGIN_PATH for compatibiity reasons with other plugins that use PLUGIN_PATH
* (2.0.1) When mouseover effect on coverphotos was switched off, the photo disappears after mouseover. Fixed.

= Enhancements =
* Japanese language support added.
* Options that do not have effect are now hidden in the settings screen.

= Known issues =
* There is a conflict with the plugin called "n3rdskwat-mp3player", it destroys the slideshow javascript data.
* Althoug I tested it and could not reproduce it, some users report a conflict with the theme called "Thesis" revision 7. It displays the albums prior to the main body (in a deviant format) as well as inside the body.
Same with "Coral" and "Montezuma".
* Althoug I tested it and could not reproduce it, some users report that the theme TwentyTen 1.0 (by the Wordpress team) as well as the theme Thesis should screws up the form security in this plugin in a way that nobody is allowed to save any changes.
There is a workaround available by copying the file wppa_no_nonce.txt from the plugins theme directory to the users theme directory. Warning: This will affect form scurity.
* There are layout issues for thumbnails and thumbnail popups in IE6 and IE7.
* When activating the plugin, you may see the warning message: "The plugin generated 255 characters of unexpected output during activation. (etc)".
This message seems to be a WP issue rather than a wppa issue and can be ignored.

= Pending enhancement requests =
* The ability to SELECT multiple files to upload. Currently selection must be done one by one, upload can be done in batches of up to 15 files.
* Additional animation effects in slideshows and browse full-size images.
* Implementation of direct slideshow. I.e. `%%slide=nn%%` like `%%album=nn%%` and `%%cover=nn%%`
* The ability to give upload rights to selected roles/users.
* The ability to use a single photo in a post or page, just like the normal media.
* Turkish character support.

= 2.0.0 =
= New Features =
= Important notice if you changed wppa_theme.php: You will need to use the new wppa_theme.php and modify as desired. =
* You can now decide to allow HTML in album and photo descriptions. This is intented for links and linebreaks.
* You can now use fading effects in the slideshow and browse full size images.
* You can select an alternative way to display thumbnails. They will appear like album covers.

= Bug Fixes =
* Fix for "Unable to create album" for some server configurations.
* Fix for intermittant too large coverphoto when coverphoto random and both landscape and portrait images in album.

= Enhancements =
* Changed some CSS and defaults to get seemless integration with theme TwentyTen.
* Various security, performance and cosmetic enhancements.
* Uses role/capabilities rather than userlevel.
* French language support added.

= Known issues =
* There is a conflict with the plugin called "n3rdskwat-mp3player", it destroys the slideshow javascript data.
* Althoug I tested it and could not reproduce it, some users report a conflict with the theme called "Thesis" revision 7. It displays the albums prior to the main body (in a deviant format) as well as inside the body.
Same with "Coral" and "Montezuma".
* Althoug I tested it and could not reproduce it, some users report that the theme TwentyTen 1.0 (by the Wordpress team) as well as the theme Thesis should screws up the form security in this plugin in a way that nobody is allowed to save any changes.
There is a workaround available by copying the file wppa_no_nonce.txt from the plugins theme directory to the users theme directory. Warning: This will affect form scurity.
* There are layout issues for thumbnails and thumbnail popups in IE6 and IE7.
* When activating the plugin, you may see the warning message: "The plugin generated 255 characters of unexpected output during activation. (etc)".
This message seems to be a WP issue rather than a wppa issue and can be ignored.

= Pending enhancement requests =
* The ability to SELECT multiple files to upload. Currently selection must be done one by one, upload can be done in batches of up to 15 files.
* Additional animation effects in slideshows and browse full-size images.
* Implementation of direct slideshow. I.e. `%%slide=nn%%` like `%%album=nn%%` and `%%cover=nn%%`
* The ability to give upload rights to selected roles/users.
* The ability to use a single photo in a post or page, just like the normal media.

= 1.9.1 =
= New Features =
* You can now add mouseover effect to coverphotos and thumbnail images.

= Bug Fixes =
Fix for Warning: URL file-access is disabled in the server configuration in ../wp-content/plugins/wp-photo-album-plus/wppa_functions.php on line 612

= Enhancements =
* Security enhancement for the sidebar widget.
* Improved documentation of wppa_style.css.
* Improved error/warning reporting.

= 1.9 =
* IF YOU COPIED/MODIFIED WPPA_THEME.PHP AND WPPA_STYLE.CSS Please study the files that come with this version and review your modifications!
* You can now have multiple sequences of `%%wppa.. %%cover.. %%size=..` tags.
You need no longer put the tags on one line, all information inside a tag sequence will be removed. Between the tag sequenses as well as before the first and after the last there may be content that will be displayed.
You can also call the album more then once in a page template.

Example#1 text in Post or Page:

`
	Text Before
	%%wppa%%
	%%cover=10%%
	%%size=150%%
	Text Between 1
	%%wppa%%
	%%album=19%%
	%%size=350%%
	Text Between 2
	%%wppa%%
	%%cover=1%%
	Text After	
`

Example#2 php code in page template:

`
<?php 
	if (function_exists('wppa_albums')) {
	echo('Text Before<br />');
	wppa_albums(10, 'cover', 150);
	echo('<br />Text Between 1<br />');
	wppa_albums(19, 'album', 350); 
	echo('<br />Text Between 2<br />');
	wppa_albums(1, 'cover');
	echo('<br />Text After<br />');
	} 
	else echo('Photo software currently unavailable, sorry'); 
?>
`

As nothing in life is permanent, this enhancement revokes the previously announced permanent restriction of having only one occurence of an album or cover in a page or post.

= More in 1.9 =
* You can now have different sizes for thumbnail images and cover photos. The regeneration of thumbnails now only occurs when the largest of the two enters a diffrent range of 25 pixels.
* Fix for sizing problem in IE6 by removing max-width and max-height from the style attributes.
* Fixed rounding error in calculating thumbnail size. This may result in new thumbnails being one pixel larger. Regeneration of thumbnails will overcome this.
* The 'Enlarge fullsize photos' switch in the admin settings page now functions as expected.
* Various small fixes.
* Added wrappers for coverphoto and thumbnails.
* You can now align thumbnails at top, center or bottom.
* Split up the code in functional pieces.
* Added functions: wppa_get_total_album_count(), wppa_get_youngest_album_id() and wppa_get_youngest_album_name() that are simple but not yet documented. For more info see the sourcecode: wppa_functions.php

= 1.8.5 =
* This is an intermediate version that never was released.

= 1.8.4 =
* Browsing photos and the slideshow are merged. You can now easily switch between them. This also fixes the scrolling issue.
* Fix to prevent loading stylesheet more than once.
* Fixed an HTML error where photo description or name contained newline characters.
* Added optional parameters to wppa_get_albums() to make its use more flexible. See: tags.txt.
* Improved security of wppa_get_albums()

= 1.8.3 =
* You can now link a album title and coverphoto to a WP page as opposed to the album's content. The album's content will still be reacheable by the View- and Slideshow links.
* You can now decide not to include a homelink in the breadcrumb navigation.
* Fixed some incomplete / erroneous links.
* While browsing full size images, the double arrow brackets will now have a transparent background.
* Minor fixes and improved error handling.
* There is now a way to automatically scroll back to the desired position when browsing full scale images. For more info: See the section Frequently asked questions.
If you made a copy of wppa_theme.php or wppa_style.css into your theme directory and you want to make full use of the new improvements, please redo your modifications (if still needed) using a fresh copy of the original files.

= 1.8.2 =
* You can now configure a link to a page from the sidebar widget photo.
* The widget will now display the correct subtitle in all cases.
* Security issue: Silence is golden index.php added to all directories.

= 1.8.1 =
* Fixed a fatal error after regeneration of thumbnails

= 1.8 =
* An optional Sidebar Widget has been added.
* A complete re-write of the help and info section.
* Increased configurability for the use of single albums in posts and pages.
* The plugin is now translatable. Dutch language files are included.
* There is now a way to configure the number of album cover photos for albums that contain sub albums only.
* Re-designed admin options page. It is now called: Settings.
* Various cosmetic changes to admin pages.

= 1.7.2 =
* Fixed the problem that in IE the accesslevel could not be changed and the options could not be saved.

= 1.7.1 =
* There is now a simple self-explaining way to recover from an interrupted regeneration of thumbnail images.
* Fixed a typo in admin_styles.css.
* Parent album Names rather than Id's are listed in the Manage Albums table.
* Parent Id's can be --- separate ---. They do not appear in the generic album where all albums are listed with a parent set to --- none ---, and are especially ment for use as parent albums (covers) for single albums in posts or pages with correct breadcrumb display.
* When displaying a blog with more than one post using an album, only the first album was displayed. This has been fixed. You can still have only one album per page/post.
* When displaying an album in a page or post, you can now overrule the default full size.
* You can now set the order of photos in an album to the default setting from the Options page.
* You can now specify whether photos may be enlarged to meet the Full Size crteria. Turning off this feature (recommended) will speed up the start of slideshows with hundreds of photos.

= 1.7 =
* Slideshow did not work when having non-standard permalink structure.
* Fixed a layout issue around page header and breadcrumb.
* Added functionality: You can have a single album in a post or page.

= 1.6.1 =
* Minor cosmetic corrections.
* Updated tags.txt to reflect new and changed tags as per version 1.6.
* Updated readme.txt
* Included sample file: page-photo-album.php.

= 1.6 =
* Converted WP Photo Album 1.5.1 to WP Photo Album Plus. Extended database.
* Added various sort order options for albums and photos.
* Added slideshow.

== About and Credits ==

* WP Photo Album Plus is extended with many new features and is maintained by J.N. Breetvelt, ( http://www.opajaap.nl/ ) a.k.a. OpaJaap
* Thanx to R.J. Kaplan for WP Photo Album 1.5.1, the basis of this plugin.

== Licence ==

WP Photo Album is released under the GNU GPL licence. ( http://www.gnu.org/copyleft/gpl.html )