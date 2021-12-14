# Change Log
### 1.77 14th December 2021
- [x] Fix landowner not being able to be removed from a records spatial data

### 1.76 7th December 2021
- [x] Fix Google Analytics cookies rejected issues

### 1.75 29th November 2021
- [x] Update code of practice video links

### 1.74 1st November 2021
- [x] Add code of practice video to PAS homepage, replacing Treasure20

### 1.73 18th October 2021
- [x] Fix for Captcha overflow on mobile devices
- [x] Fix images for users with research role

### 1.72 14th October 2021
- [x] Added captcha to the following pages
  - Login
  - Forgot password
  - Forgot username
  - Change password
  - Success page
- [x] Added password autocomplete attributes
- [x] Applied caps lock alert PR, and added to success page 
- [x] Form consistency changes
- [x] Better password validation 
- [x] Added confirm password field to missing forms 

### 1.71 6th October 2021
- [x] Fix issue with red records appearing in searches for non-authorised users
- [x] Change hardcoded email references from info@finds.org.uk to past@finds.org.uk

### 1.70 18th August 2021
- [x] Fix missing region name and certainty type on CSV export
- [x] Fix for mobile menu being unclickable
- [x] Sketch lab 3D model URL
- [x] Data protection issue – Remove name from save searches
- [x] Show "Finds recorded for me by FLOs" link to all non-admins
- [x] Twitter re-added to the home page
- [x] Home page banner text changes
- [x] Changed member welcome page URL
- [x] Contact form presentation improvements
- [x] Changed wording of "Search database for all examples recorded" on view user page (Admin section)

### 1.69 5th Mar 2020
- [x] Finder details are now hidden for people not logged in and members which cannot record.
- [x] PDF export is hidden from the Research user as they are not allowed to export that.
- [x] Send this search to someone and Save this search option can be seen by the research user.

### 1.68 11th Feb 2020
- [x] When finds record is updated, do not add abbr tag each time.

### 1.67 7th Jan 2020
- [x] Text amended for the page: Volunteering with the Portable Antiquities Scheme.
- [x] CKEditor upgraded (4.11 -> 4.13).
- [x] Fix to allow the addition of FLOs to the rallies.

### 1.66 15th Oct 2019
- [x] Fix for the broken URL on the search map for the object type as a Hoard.

### 1.65 14th Oct 2019
- [x] Fix to search on parish for the public user for the map.

### 1.64 30th Sept 2019
- [x] Removed the reference to the Lost Change Website from the home page.

### 1.63 26th Sept 2019
- [x] Fix to add the role 'Senior Treasure Registrar' to Contacts Page.

### 1.62 5th September 2019
- [x] Fix replace the ctype_digit with the is_numeric.
- [x] Fix for the redirect URL for the denomination page.
- [x] Set the institution in the bold on the account page.
- [x] New Button to copy the last reference.

### 1.61 24th July 2019
- [x] Fix for the Greek and Roman Provincial search.

### 1.60 17th July 2019
- [x] Route added to open the SSL certification URL.

### 1.59 27th June 2019
- [x] Audit the deletion of the record.
- [x] Fix to add the staff profile image.

### 1.58 16th May 2019
- [x] Fix to the KML file for public/member user.
- [x] Changed the accredited museums page.

### 1.57 9th May 2019
- [x] Fix to add the FLO with 'Historic Environment' to Contacts Page.
- [x] Fix to search the URL bug bounty.

### 1.56 10th April 2019
- [x] Fix to handle if the volunteer role does not exist.

### 1.55 2nd April 2019
- [x] Fix to add the discovery method.

### 1.54 29th March 2019
- [x] Staff user can upload their profile pictures.
- [x] Fix to insert record into mints_rulers table.
- [x] Fix for the default image on the user's profile.
- [x] Fix for userAgent of the search table.

### 1.53 7th March 2019
- [x] Users can strip off the finder, secondary finder, recorder, identifier and second identifier, if those were added.
- [x] Fix for reCaptcha used for Contact Us, register, reset password forms.

### 1.52 26th February 2019
- [x] Fix to Delete Button of Role and Content.
- [x] Replaced texarea with CKEditor for Description of Role form.
- [x] Fix to display issuer reece period.
- [x] User can add their social account.
- [x] Added max length attribute to all the coin forms.
- [x] Fix to canRecord of Activate Button.
- [x] Fix to display Discovery Metadata as string for Hoard records.

### 1.51 8th January 2019
- [x] Added Activate/Deactivate button.

### 1.50 19th December 2018
- [x] Amended CKEditor to make default language as British English.
- [x] Fix to CKEditor to count html elements/tags.
- [x] Fix to Roman Mints page.

### 1.49 05th December 2018
- [x] CKEditor is upgraded (4.2 -> 4.11).
- [x] Fix to view Treasure minutes in chronological order with expected names.
- [x] Fix to Timeline of associated dates.
- [x] Fix to Delete button of Research Project.
- [x] Latitude and longitude details are removed from the geojson format.

### 1.48 12th November 2018
- [x] Fix to Obverse-Inscription.
- [x] Fix to login without issue of userAgent length.
- [x] Timeout has now been changed to NOT go to the user login page.
- [x] Updated not to show Red and Quarantine records as part of Similar objects to Public.

### 1.47 19th October 2018
- [x] Fix to Republic Moneyer, Emperor page.
- [x] Fix to truncation error for Reference.
- [x] Donation page added to the Treasure.
- [x] Fix to Staff and Coroners map.
- [x] Fix to view users social account.
- [x] Abatement page added to the Treasure.

### 1.46 17th August 2018
- [x] Fix to Nomisma reference.

### 1.45 9th July 2018
- [x] Added hoards publication lists.
- [x] Fixed help controller @ /admin/help.
- [x] Fixed institutional stats to include final day in period.

### 1.44 : 22nd June 2018
- [x] Lots of small changes but mainly (recently) a fix to county statistics.

### 1.43 : 7th February 2017
- [x] Updated AWS links for data downloads.

### 1.42 : 28th November 2017

- [x] Updated sub modules.
- [x] Fixed easyrdf classes for name spaces.

### 1.41 : 27th November 2017

- [x] Fixed latest tweets helper.

### 1.40 : 23rd November 2017

- [x] Added hoard search.
- [x] Added code of conduct.

### 1.39 : 21st November 2017

- [x] Added better version of time ago in words view helper by @portableant.

### 1.38 : 20th November 2017

- [x] Added logic for images to be added for hoards and artefacts *Major upgrade*  by @portableant.

### 1.37 : 17th November 2017

All changes by @portableant

- [x] Added session timeout modal and associated functions.
- [x] Fixed view helper in statistics module.

### 1.36 : 16th November 2017
- [x] Added jQuery caps lock detection for password on login form by @portableant.

### 1.35 : 15th November 2017

All changes by @portableant

- [x] Amended view helper for CC license render.
- [x] Removed Pelagios view helper from numismatics.
- [x] Fixed bug with image addition.
- [x] Upgraded Imagick classes for correct method calls for PHP 5.6 +.
- [x] Hide What 3 Words and WOEID from public view.
- [x] Fix exposure of records in quarantine and review in the more like this feature.
- [x] Added CRUD for copyright management.
- [x] Fix exposure of records in quarantine and review to researchers and heros.
- [x] Fix access to KML files for public.
- [x] Merged ImageBot optimised images.
- [x] Fix erroneous date entry.

### 1.34 : 6th November 2017

All changes by @portableant

- [x] Added new class for rendering licenses for Creative Commons with logo (view helper).
- [x] Amended thumbs partial to reflect above.
- [x] Amended humans.txt to reflect contributions and staff changes.
- [x] Amended CC license URL to 4.0 from 2.0.
- [x] Added CONTRIBUTING.MD file to tell people how to add their own code.
- [x] Added Image Rights field to data sent to SOLR (for BMCo to track down usage of their images in PAS dataset).
- [x] Added code for Image Rights facet to appear on image searches.

### 1.33 : 3rd October 2017

All changes by @portableant

- [x] Added new class for rendering RDF to /library/Pas/RDF/.
- [x] Amended Nomisma class in models for changes to RDF model.

### 1.32 : 22nd September 2017

All changes by @portableant

- [x] Updated library for mpdf path.
- [x] Added new SQL files for correct data structure.
- [x] Fixed license rendering.
- [x] Fixed RDF exception error for emperors.
- [x] Fixed duplicated head title.
- [x] Fixed insecure maps on emperors.
- [x] Upgraded zend framework to last supported version.
- [x] Fixed admin for content deletion and editing.
- [x] Removed LAWD.js.
- [x] Fixed missing key in KML file.
- [x] Added ACKNOWLEDGEMENTS.md file.

### 1.30 : 27th June, 2017
- [x] Change to bounding box.

### 1.29 : 24th June, 2017
- [x] Fix license file for rendering error.

### 1.28 : 13th April, 2017
- [x] Added new Treasure 20 page plus link to it from main page.

### 1.27 : 1st February, 2017
- [x] Added new Treasure document to menus (Treasure Valuation).

### 1.26 : 18th January, 2017
- [x] Fix for the hoards deletion bug where records (not search index) was removed only.

### 1.25 : 22nd December, 2016
- [x] Fix to the research pages to sort links and remove any errors.

### 1.24 : 12th December, 2016
- [x] Jpeg handle added to htaccess file.

### 1.23 : 25th November, 2016
- [x] Add new recording guide into docs and tidy up some guide titles.

### 1.22 : 23rd November, 2016
- [x] Fix to What3Words API update (v1 -> v2).

### 1.21 : 24th October, 2016
- [x] Removed GeoPlanet references.

### 1.20 : 14th September, 2016
- [x] Changed welcome message for new registrants.

### 1.19 : 13th September, 2016
- [x] Added Andrew Brown as FA to Roman Coins.

### 1.18 : 4th August, 2016
- [x] Update to 3D model licensing text.

### 1.17 : 29th July, 2016
- [x] Updated text for Treasure Minutes page.

### 1.16 : 21st July, 2016
- [x] Allow publication search term to be changed whilst on the page.
 
### 1.15 : 20th July, 2016
- [x] Removed reference to Amazon on publications page.

### 1.14 : 27th June, 2016
- [x] VOID file updated for RIC & RRC by Ethan Gruber.

### 1.13 : 14th April, 2016
- [x] Delicious API deprecated for vacancies.

### 1.12 : 31st March, 2016
- [x] Fix find spot code for newer geoJSON.
- [x] Added new sub module for findsorguk-geodata.
- [x] Rebuilt converted geoJSON as WGS84.

### 1.11 : 21st March, 2016
- [x] Fix to interface with OpenDomesday allowing it to fail gracefully.

### 1.10 : 4th March, 2016
- [x] Added tooltip to What 3 Words to explain it's function.

### 1.09 : 1st March 2016
- [x] Added redirect for outdated link.
- [x] Fixed index.rdf for Reece periods.

### 1.08 : 25th February, 2016
- [x] Merged fix for front page image stretch.

### 1.07 : 12th February, 2016
- [x] Added hoard group to list of those allowed to zoom the most.
- [x] Fix to hamburger menu bar not showing.

### 1.06 : 28th January, 2016
- [x] Fix to allow Map Origins to be edited (#1031).
- [x] Fix to allow Land Use terminology to be edited (#1017).
- [x] Fix to problem being unable to edit materials (#1030).
- [x] Small typos fixed on user page.
- [x] Fix to Add Image button being obscured.
- [x] Branding removed (#1024).

### 1.05 : 7th January, 2016
- [x] Removed staff region from JSON.
- [x] Fixed Broken link on contactus (#1020).
- [x] Removed spaces from tooltip on names in people list.

### 1.04 : 17th December, 2015
- [x] Fix to not show Other as a title.

### 1.03 : 15th December, 2015
- [x] Comments form removed.
- [x] Public search links fixed.
- [x] Fix to Amazon book references.
- [x] PDF forms can now be up to 1000 records long. Fix to limit bug.

### 1.02 : 17th November, 2015
- [x] Added DOI.
- [x] Updated License.
- [x] Added note about out of date data.
- [x] Merged pull request #997 from findsorguk/accreditedMuseums.
- [x] Changed submit button message to -Save details-.
- [x] Merged pull request #1000 from s-moon/999.  
- [x] Changes to readme structure. 
- [x] Updated README.md. 

### Releases
* 1.01
  * 23-OCT-15 [Development release] (https://github.com/findsorguk/findsorguk/releases/tag/v1.01.development)
* 1.00
  * 23-OCT-15 [Stable release] (https://github.com/findsorguk/findsorguk/releases/tag/v1)
